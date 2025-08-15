<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Localhost server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Bearer Token dùng cho xác thực",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 *
 * @OA\Post(
 *     path="/api/auth/login",
 *     tags={"Auth"},
 *     summary="Đăng nhập tài khoản",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Đăng nhập thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string"),
 *             @OA\Property(property="refresh_token", type="string"),
 *             @OA\Property(property="token_type", type="string"),
 *             @OA\Property(property="access_token_expires_at", type="string", format="date-time"),
 *             @OA\Property(property="refresh_token_expires_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Đăng nhập thất bại"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/auth/refresh",
 *     tags={"Auth"},
 *     summary="Làm mới access token",
 *     description="Dùng refresh_token hợp lệ để lấy access_token mới và refresh_token mới.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string"),
 *             @OA\Property(property="refresh_token", type="string"),
 *             @OA\Property(property="access_token_expires_at", type="string", format="date-time"),
 *             @OA\Property(property="refresh_token_expires_at", type="string", format="date-time"),
 *             @OA\Property(property="token_type", type="string", example="Bearer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Refresh token không hợp lệ hoặc hết hạn"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/auth/logout",
 *     tags={"Auth"},
 *     summary="Đăng xuất tài khoản",
 *     description="Xóa toàn bộ token của người dùng và đăng xuất khỏi hệ thống.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Logout successful"),
 *             @OA\Property(property="data", type="object", example={})
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Không có quyền hoặc token không hợp lệ"
 *     )
 * )
 */
class AuthDocs
{
}
