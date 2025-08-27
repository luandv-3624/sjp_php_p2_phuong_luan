<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\MomoPayRequest;
use App\Http\Requests\Payment\MomoIpnRequest;

class PaymentController extends Controller
{
    private PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function payWithMomo(MomoPayRequest $request)
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        return $this->paymentService->payWithMomo($data, $userId);
    }

    public function momoIpn(MomoIpnRequest $request)
    {
        $data = $request->validated();
        return $this->paymentService->handleMomoIpn($data);
    }

    public function momoRedirect(Request $request)
    {
        return redirect('/')->with('success', __('payment.payment_success'));
    }
}
