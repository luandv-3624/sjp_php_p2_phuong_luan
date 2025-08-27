<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PaymentMomoRequest",
 *     type="object",
 *     required={"booking_id"},
 *     @OA\Property(property="booking_id", type="integer", example=123, description="ID of booking to pay"),
 *     @OA\Property(property="amount", type="integer", example=500000, description="Payment amount in VND (must be >= 1)")
 * )
 *
 * @OA\Schema(
 *     schema="PaymentMomoResponse",
 *     type="object",
 *     @OA\Property(property="payUrl", type="string", example="https://test-payment.momo.vn/pay/xyz"),
 *     @OA\Property(property="qrCode", type="string", nullable=true, example="https://momo.vn/qrcode/abc"),
 *     @OA\Property(property="orderId", type="string", example="order123"),
 *     @OA\Property(property="requestId", type="string", example="req456")
 * )
 *
 * @OA\Schema(
 *     schema="MomoIpnRequest",
 *     type="object",
 *     required={"partnerCode","orderId","requestId","amount","orderInfo","transId","resultCode","message","responseTime","signature"},
 *     @OA\Property(property="partnerCode", type="string", example="MOMO"),
 *     @OA\Property(property="orderId", type="string", example="order123"),
 *     @OA\Property(property="requestId", type="string", example="req456"),
 *     @OA\Property(property="amount", type="number", example=100000),
 *     @OA\Property(property="orderInfo", type="string", example="booking_id:123"),
 *     @OA\Property(property="transId", type="integer", example=987654321),
 *     @OA\Property(property="resultCode", type="integer", example=0, description="0 = success"),
 *     @OA\Property(property="message", type="string", example="Successful."),
 *     @OA\Property(property="responseTime", type="integer", example=1697644200000),
 *     @OA\Property(property="signature", type="string", example="generated-signature"),
 * )
 *
 * @OA\Post(
 *     path="/payment/momo",
 *     summary="Create MoMo payment request",
 *     description="User creates a payment request for a booking with MoMo",
 *     tags={"Payment"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PaymentMomoRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Payment request created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PaymentMomoResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid booking status or already paid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User not booking owner"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/payment/momo/ipn",
 *     summary="MoMo IPN callback",
 *     description="MoMo sends payment notification to server (async). No authentication required.",
 *     tags={"Payment"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/MomoIpnRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="IPN handled successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid signature"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Transaction failed on server"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/payment/momo/redirect",
 *     summary="MoMo Redirect URL",
 *     description="Endpoint called after user completes MoMo payment. Redirects to frontend.",
 *     tags={"Payment"},
 *     @OA\Response(
 *         response=302,
 *         description="Redirect to homepage with payment success message"
 *     )
 * )
 */
class PaymentDocs
{
}
