<?php

namespace App\Services\Momo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MomoClient
{
    private string $partnerCode;
    private string $accessKey;
    private string $secretKey;
    private string $endpoint;
    private string $redirectUrl;
    private string $ipnUrl;

    public function __construct()
    {
        $this->partnerCode = config('services.momo.partner_code');
        $this->accessKey = config('services.momo.access_key');
        $this->secretKey = config('services.momo.secret_key');
        $this->endpoint = config('services.momo.endpoint');
        $this->redirectUrl = config('services.momo.redirect_url');
        $this->ipnUrl = config('services.momo.ipn_url');
    }

    public function createPaymentRequest(array $bookingData, int $userId): array
    {
        $requestId = (string) Str::uuid();
        $orderId = (string) Str::uuid();
        $amount = (int) $bookingData['amount'];

        $orderInfo = "booking_id:{$bookingData['id']}";
        $extraData = base64_encode(json_encode(['user_id' => $userId]));

        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}"
            . "&ipnUrl={$this->ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}"
            . "&partnerCode={$this->partnerCode}&redirectUrl={$this->redirectUrl}"
            . "&requestId={$requestId}&requestType=captureWallet";

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'accessKey'   => $this->accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl'      => $this->ipnUrl,
            'extraData'   => $extraData,
            'requestType' => 'captureWallet',
            'signature'   => $signature,
            'lang'        => 'vi',
        ];

        try {
            $response = Http::timeout(30)->post("{$this->endpoint}/v2/gateway/api/create", $payload)->json();

            if (empty($response['payUrl'])) {
                Log::error('MoMo create order missing payUrl', ['resp' => $response, 'payload' => $payload]);
                return ['success' => false, 'message' => __('payment.momo_response_invalid'), 'data' => $response];
            }

            return ['success' => true, 'data' => $response, 'orderId' => $orderId, 'requestId' => $requestId];

        } catch (\Throwable $e) {
            Log::error('MoMo create order failed', ['exception' => $e->getMessage(), 'payload' => $payload]);
            return ['success' => false, 'message' => __('payment.momo_request_failed')];
        }
    }

    public function validateIpn(array $ipnData): array
    {
        $rawHash = "accessKey={$this->accessKey}"
            . "&amount={$ipnData['amount']}"
            . "&extraData={$ipnData['extraData']}"
            . "&message={$ipnData['message']}"
            . "&orderId={$ipnData['orderId']}"
            . "&orderInfo={$ipnData['orderInfo']}"
            . "&orderType={$ipnData['orderType']}"
            . "&partnerCode={$ipnData['partnerCode']}"
            . "&payType={$ipnData['payType']}"
            . "&requestId={$ipnData['requestId']}"
            . "&responseTime={$ipnData['responseTime']}"
            . "&resultCode={$ipnData['resultCode']}"
            . "&transId={$ipnData['transId']}";

        $calculatedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);

        if ($calculatedSignature !== $ipnData['signature']) {
            Log::error('MoMo IPN: Invalid signature', ['data' => $ipnData]);
            return ['isValid' => false, 'isSuccess' => false, 'message' => 'invalid_signature'];
        }

        if ((int) $ipnData['resultCode'] !== 0) {
            Log::warning('MoMo IPN: Transaction was not successful', ['data' => $ipnData]);
            return ['isValid' => true, 'isSuccess' => false, 'message' => 'payment_failed'];
        }

        return ['isValid' => true, 'isSuccess' => true, 'message' => 'success'];
    }
}
