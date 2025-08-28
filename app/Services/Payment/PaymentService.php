<?php

namespace App\Services\Payment;

use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Services\Payment\PaymentServiceInterface;
use Carbon\Carbon;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Enums\BookingPaymentStatus;
use App\Enums\BookingStatus;
use App\Enums\HttpStatusCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\Booking\BookingRepositoryInterface;
use App\Services\Momo\MomoClient;

class PaymentService implements PaymentServiceInterface
{
    protected PaymentRepositoryInterface $paymentRepo;
    protected BookingRepositoryInterface $bookingRepo;
    protected MomoClient $momoClient;

    public function __construct(PaymentRepositoryInterface $paymentRepo, BookingRepositoryInterface $bookingRepo, MomoClient $momoClient)
    {
        $this->paymentRepo = $paymentRepo;
        $this->bookingRepo = $bookingRepo;
        $this->momoClient = $momoClient;
    }

    public function payWithMomo(array $data, int $userId): JsonResponse
    {
        $booking = $this->bookingRepo->findById($data['booking_id']);

        // Check ownership
        if ($booking->user_id !== $userId) {
            return ApiResponse::error(__('booking.access_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        // Check booking status - allow payment when:
        // 1. confirmed-unpaid: owner/manager has confirmed, but not yet paid
        // 2. partial-pending: Partially paid, waiting for owner/manager confirmation
        // 3. accepted: owner/manager has accepted, but additional payment is still allowed (if not fully paid)
        if (!in_array($booking->status, [
            BookingStatus::CONFIRMED_UNPAID->value,
            BookingStatus::PARTIAL_PENDING->value,
            BookingStatus::ACCEPTED->value
        ])) {
            return ApiResponse::error(__('payment.invalid_booking_status'), [], HttpStatusCode::BAD_REQUEST);
        }

        // check valid status payment booking can create payment: only unpaid or partial
        if ($booking->status_payment == BookingPaymentStatus::PAID->value) {
            return ApiResponse::error(__('payment.booking_already_paid'), [], HttpStatusCode::BAD_REQUEST);
        }

        // Check outstanding
        // Calculate paid payment
        $paidAmount = $this->paymentRepo->getPaidAmountByBookingId($booking->id);
        $outstanding = $booking->total_price - $paidAmount;

        if ($data['amount'] > $outstanding) {
            return ApiResponse::error(
                __('payment.amount_exceeds_outstanding', ['max' => $outstanding]),
                [],
                HttpStatusCode::BAD_REQUEST
            );
        }

        $bookingData = [
            'id' => $booking->id,
            'amount' => $data['amount'],
        ];

        $momoResponse = $this->momoClient->createPaymentRequest($bookingData, $userId);

        if (!$momoResponse['success']) {
            return ApiResponse::error($momoResponse['message'], $momoResponse['data'] ?? [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

        $result = [
            'payUrl'    => $momoResponse['data']['payUrl'],
            'qrCode'    => $momoResponse['data']['qrCodeUrl'] ?? null,
            'orderId'   => $momoResponse['orderId'],
            'requestId' => $momoResponse['requestId'],
        ];

        return ApiResponse::success($result, __('payment.created_successfully'), HttpStatusCode::CREATED);
    }

    public function handleMomoIpn(array $data): JsonResponse
    {
        $validation = $this->momoClient->validateIpn($data);

        if (!$validation['isValid']) {
            return ApiResponse::error(__('payment.invalid_signature'), [], HttpStatusCode::BAD_REQUEST);
        }

        if (!$validation['isSuccess']) {
            // Transaction failed but signature is valid, but still return 200 so MoMo does not resend
            return ApiResponse::success([], __('payment.transaction_not_successful'), HttpStatusCode::OK);
        }

        preg_match('/booking_id:(\d+)/', $data['orderInfo'], $matches);
        $bookingId = $matches[1] ?? null;

        if (!$bookingId) {
            Log::warning('MoMo IPN missing booking_id in orderInfo', ['orderInfo' => $data['orderInfo']]);
            return ApiResponse::error(__('payment.invalid_booking'), [], HttpStatusCode::UNPROCESSABLE_ENTITY);
        }

        if ($this->paymentRepo->existsByTransId((string)$data['transId'])) {
            Log::info('MoMo IPN already processed (transId exists)', ['transId' => $data['transId']]);
            return ApiResponse::success([], __('payment.already_processed'), HttpStatusCode::OK);
        }

        try {
            DB::transaction(function () use ($bookingId, $data) {
                $booking = $this->bookingRepo->findBookingForUpdate($bookingId);

                if (!$booking) {
                    throw new \RuntimeException('Booking not found');
                }

                if (!empty($data['amount']) && $data['amount'] > 0) {
                    $paidAmount = $this->paymentRepo->getPaidAmountByBookingId($booking->id);
                    $outstanding = $booking->total_price - $paidAmount;

                    if ($data['amount'] > $outstanding) {
                        Log::warning("MoMo payment exceeds outstanding", [
                            'booking_id' => $booking->id,
                            'amount'     => $data['amount'],
                            'outstanding' => $outstanding
                        ]);

                        throw new \DomainException(
                            __('payment.amount_exceeds_outstanding', ['max' => $outstanding])
                        );
                    }

                    $bookingUpdateData = $this->determineBookingPaymentStatus($data['amount'], $outstanding);

                    $this->bookingRepo->updateBookingPaymentStatus($booking, $bookingUpdateData);
                }

                $paidAt = !empty($data['responseTime'])
                ? Carbon::createFromTimestamp((int)$data['responseTime'] / 1000)
                : Carbon::now();

                $this->paymentRepo->createPayment([
                    'booking_id' => $booking->id,
                    'method'     => PaymentMethod::MOMO->value,
                    'amount'     => $data['amount'],
                    'status'     => PaymentStatus::SUCCESS->value,
                    'paid_at'    => $paidAt,
                    'order_id'   => $data['orderId'],
                    'trans_id'   => $data['transId'],
                ]);
            }, 5);
        } catch (\DomainException $e) {
            return ApiResponse::error($e->getMessage(), [], HttpStatusCode::BAD_REQUEST);
        } catch (\Throwable $e) {
            Log::error('Momo payment transaction failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            return ApiResponse::error(
                __('payment.transaction_failed'),
                [],
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }

        return ApiResponse::success($data, __('payment.created_successfully'));
    }

    private function determineBookingPaymentStatus(int $amount, int $outstanding): array
    {
        if ($amount < $outstanding) {
            return [
                'status_payment' => BookingPaymentStatus::PARTIAL->value,
                'status'         => BookingStatus::PARTIAL_PENDING->value,
            ];
        }

        return [
            'status_payment' => BookingPaymentStatus::PAID->value,
            'status'         => BookingStatus::PAID_PENDING->value,
        ];
    }
}
