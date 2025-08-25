<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"Users"},
 *     summary="Danh sách người dùng",
 *     description="Lấy danh sách người dùng với phân trang, sắp xếp, và tìm kiếm.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="perPage",
 *         in="query",
 *         required=false,
 *         description="Số lượng bản ghi trên mỗi trang",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="sortBy",
 *         in="query",
 *         required=false,
 *         description="Cột để sắp xếp (name, email, phone_number, password, status, role_id)",
 *         @OA\Schema(type="string", example="name")
 *     ),
 *     @OA\Parameter(
 *         name="sortOrder",
 *         in="query",
 *         required=false,
 *         description="Thứ tự sắp xếp (asc, desc)",
 *         @OA\Schema(type="string", example="asc")
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         required=false,
 *         description="Tìm kiếm theo tên hoặc email",
 *         @OA\Schema(type="string", example="john")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Danh sách người dùng thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="John Doe"),
 *                         @OA\Property(property="email", type="string", example="john@example.com"),
 *                         @OA\Property(property="phone_number", type="string", example="+84123456789"),
 *                         @OA\Property(property="status", type="string", example="active"),
 *                         @OA\Property(property="role", type="object",
 *                             @OA\Property(property="id", type="integer", example=2),
 *                             @OA\Property(property="name", type="string", example="Admin")
 *                         ),
 *                         @OA\Property(property="created_at", type="string", format="date-time"),
 *                         @OA\Property(property="updated_at", type="string", format="date-time")
 *                     )
 *                 ),
 *                 @OA\Property(property="meta", type="object",
 *                     @OA\Property(property="current_page", type="integer", example=1),
 *                     @OA\Property(property="last_page", type="integer", example=5),
 *                     @OA\Property(property="per_page", type="integer", example=10),
 *                     @OA\Property(property="total", type="integer", example=45)
 *                 ),
 *                 @OA\Property(property="links", type="object",
 *                     @OA\Property(property="first", type="string", example="http://localhost:8000/api/users?page=1"),
 *                     @OA\Property(property="last", type="string", example="http://localhost:8000/api/users?page=5"),
 *                     @OA\Property(property="prev", type="string", nullable=true, example=null),
 *                     @OA\Property(property="next", type="string", example="http://localhost:8000/api/users?page=2")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/users/{user}",
 *     tags={"Users"},
 *     summary="Cập nhật thông tin người dùng",
 *     description="Cập nhật các thông tin của một người dùng, chỉ admin mới có quyền.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="user",
 *         in="path",
 *         required=true,
 *         description="ID của người dùng cần cập nhật",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="role_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cập nhật thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="phone_number", type="string", example="+84123456789"),
 *                 @OA\Property(property="status", type="string", example="active"),
 *                 @OA\Property(property="role", type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="name", type="string", example="Admin")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Không có quyền cập nhật người dùng"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Dữ liệu không hợp lệ"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/users/{user}/status",
 *     tags={"Users"},
 *     summary="Cập nhật trạng thái người dùng",
 *     description="Cập nhật trạng thái của một người dùng. Chỉ admin/moderator mới có quyền.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="user",
 *         in="path",
 *         required=true,
 *         description="ID của người dùng cần cập nhật trạng thái",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(property="status", type="string", example="inactive")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cập nhật trạng thái thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="phone_number", type="string", example="+84123456789"),
 *                 @OA\Property(property="status", type="string", example="inactive"),
 *                 @OA\Property(property="role", type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="name", type="string", example="Admin")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Không có quyền cập nhật trạng thái người dùng"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Dữ liệu không hợp lệ"
 *     )
 * )
 */
class UserDocs {}
