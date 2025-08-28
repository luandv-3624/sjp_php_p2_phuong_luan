<?php

namespace App\Services\Booking;

use App\Helpers\ApiResponse;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Repositories\Booking\BookingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusChangedMail;
use App\Enums\HttpStatusCode;
use Illuminate\Support\Facades\DB;

class BookingService implements BookingServiceInterface
{
    public function __construct(private BookingRepositoryInterface $bookingRepo)
    {
    }

    public function create(array $data): JsonResponse
    {
        return ApiResponse::success(new BookingResource($this->bookingRepo->create($data)), __('booking.create_success'), Response::HTTP_CREATED);
    }

    public function findById(int $id): JsonResponse
    {
        return ApiResponse::success(new BookingResource($this->bookingRepo->findById($id)));
    }

    public function findAll(array $filters, ?int $pageSize): JsonResponse
    {
        return ApiResponse::success(new BookingCollection($this->bookingRepo->findAll($filters, $pageSize)));
    }

    public function updateStatus(Booking $booking, string $newStatus): JsonResponse
    {
        try {
            $booking = DB::transaction(function () use (&$booking, $newStatus) {
                $booking = $this->bookingRepo->findBookingForUpdate($booking->id);

                // business rule: prevent booking owner CANCEL after ACCEPTED/DONE
                if ($newStatus === BookingStatus::CANCELLED->value &&
                    in_array($booking->status, [BookingStatus::ACCEPTED->value, BookingStatus::DONE->value])) {
                    throw new \Exception(
                        __('booking.cannot_cancel_in_status', ['status' => $booking->status])
                    );
                }

                return $this->bookingRepo->updateStatus($booking, $newStatus);
            });

            if (in_array($newStatus, [
                BookingStatus::CONFIRMED_UNPAID->value,
                BookingStatus::REJECTED->value,
                BookingStatus::ACCEPTED->value,
            ])) {
                Mail::to($booking->user->email)
                    ->queue(new BookingStatusChangedMail($booking, $newStatus));
            }

            return ApiResponse::success($booking, __('booking.status_updated'));
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                [],
                HttpStatusCode::BAD_REQUEST
            );
        }
    }
}
