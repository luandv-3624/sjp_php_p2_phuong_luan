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
 *
 * Signup
 *
 * @OA\Post(
 *     path="/api/auth/signup",
 *     tags={"Auth"},
 *     summary="Đăng ký tài khoản mới",
 *     description="Tạo tài khoản người dùng mới, sau đó hệ thống sẽ gửi email xác minh.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "role_id"},
 *             @OA\Property(property="name", type="string", example="Nguyen Van A"),
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="phone_number", type="string", example="0987654321"),
 *             @OA\Property(property="password", type="string", format="password", example="123456"),
 *             @OA\Property(property="role_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Đăng ký thành công, vui lòng kiểm tra email để xác minh",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=10),
 *                 @OA\Property(property="name", type="string", example="Nguyen Van A"),
 *                 @OA\Property(property="email", type="string", example="user@example.com"),
 *                 @OA\Property(property="phone_number", type="string", example="0987654321"),
 *                 @OA\Property(property="role_id", type="integer", example=2)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Email đã tồn tại hoặc dữ liệu không hợp lệ"
 *     )
 * )
 *
 * Verify account
 *
 * @OA\Get(
 *     path="/api/auth/verify/{id}",
 *     tags={"Auth"},
 *     summary="Xác minh tài khoản",
 *     description="Xác minh tài khoản sau khi người dùng nhấn vào link xác minh trong email.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID của user cần xác minh",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Xác minh thành công"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Xác minh thất bại"
 *     )
 * )
 *
 * Forgot Password
 *
 * @OA\Post(
 *     path="/api/auth/password/forgot-password",
 *     tags={"Auth"},
 *     summary="Yêu cầu đặt lại mật khẩu",
 *     description="Người dùng nhập email, hệ thống sẽ gửi mail chứa link reset password kèm token.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gửi email reset password thành công"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Gửi email thất bại hoặc request quá nhanh"
 *     )
 * )
 *
 * Reset Password
 *
 * @OA\Post(
 *     path="/api/auth/password/reset",
 *     tags={"Auth"},
 *     summary="Đặt lại mật khẩu bằng email và token",
 *     description="Người dùng nhận link qua email (chứa email & token). Sau đó gửi request kèm mật khẩu mới.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "token", "password", "password_confirmation"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="token", type="string", example="nwWKVHuGaweC9GQZp5YThd7IYsovSdg2bpbMt8dwT7qECrMKa1Qd8wJPiroB8zSI"),
 *             @OA\Property(property="password", type="string", format="password", example="newPassword123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="newPassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Đổi mật khẩu thành công"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Token không hợp lệ hoặc đã hết hạn"
 *     )
 * )
 */
class AuthDocs
{
}
