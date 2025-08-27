<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class MomoIpnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'partnerCode'  => 'required|string',
            'orderId'      => 'required|string',
            'requestId'    => 'required|string',
            'amount'       => 'required|numeric',
            'orderInfo'    => 'required|string',
            'orderType'    => 'nullable|string',
            'transId'      => 'required|numeric',
            'resultCode'   => 'required|integer',
            'message'      => 'required|string',
            'payType'      => 'nullable|string',
            'responseTime' => 'required|numeric',
            'extraData'    => 'nullable|string',
            'signature'    => 'required|string',
        ];
    }
}
