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
 */
class UserDocs {}
