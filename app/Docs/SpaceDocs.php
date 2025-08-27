<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/venues/{venue}/spaces",
 *     tags={"Spaces"},
 *     summary="Tạo không gian mới trong một venue",
 *     description="Chỉ chủ sở hữu hoặc quản lý của venue mới có quyền tạo space.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="venue",
 *         in="path",
 *         required=true,
 *         description="ID của venue nơi tạo space",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","space_type_id","capacity","price_type_id","price","status"},
 *             @OA\Property(property="name", type="string", example="Phòng họp A"),
 *             @OA\Property(property="space_type_id", type="integer", example=1),
 *             @OA\Property(property="capacity", type="integer", example=10),
 *             @OA\Property(property="price_type_id", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=500000),
 *             @OA\Property(property="description", type="string", example="Phòng họp hiện đại, đầy đủ thiết bị"),
 *             @OA\Property(property="status", type="string", enum={"available","unavailable"}, example="available")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tạo space thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=201),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="venue_id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Phòng họp A"),
 *                 @OA\Property(property="capacity", type="integer", example=10),
 *                 @OA\Property(property="price", type="number", example=500000),
 *                 @OA\Property(property="description", type="string", example="Phòng họp hiện đại"),
 *                 @OA\Property(property="status", type="string", example="available"),
 *                 @OA\Property(property="space_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Meeting Space"),
 *                     @OA\Property(property="description", type="string", example="Không gian họp nhóm")
 *                 ),
 *                 @OA\Property(property="price_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="code", type="string", example="hourly"),
 *                     @OA\Property(property="name", type="string", example="Theo giờ"),
 *                     @OA\Property(property="name_en", type="string", example="Hourly")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=403, description="Không có quyền tạo space"),
 *     @OA\Response(response=422, description="Dữ liệu không hợp lệ")
 * )
 *
 * @OA\Get(
 *     path="/api/venues/{venue}/spaces",
 *     tags={"Spaces"},
 *     summary="Danh sách không gian của một venue",
 *     description="Trả về tất cả spaces thuộc về một venue.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="venue",
 *         in="path",
 *         required=true,
 *         description="ID của venue",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Danh sách spaces",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="venue_id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Phòng họp A"),
 *                     @OA\Property(property="capacity", type="integer", example=10),
 *                     @OA\Property(property="price", type="number", example=500000),
 *                     @OA\Property(property="description", type="string", example="Phòng họp hiện đại"),
 *                     @OA\Property(property="status", type="string", example="available"),
 *                     @OA\Property(property="space_type", type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Meeting Space"),
 *                         @OA\Property(property="description", type="string", example="Không gian họp nhóm")
 *                     ),
 *                     @OA\Property(property="price_type", type="object",
 *                         @OA\Property(property="id", type="integer", example=2),
 *                         @OA\Property(property="code", type="string", example="hourly"),
 *                         @OA\Property(property="name", type="string", example="Theo giờ"),
 *                         @OA\Property(property="name_en", type="string", example="Hourly")
 *                     ),
 *                     @OA\Property(property="created_at", type="string", format="date-time"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time")
 *                 )
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/spaces/{space}",
 *     tags={"Spaces"},
 *     summary="Chi tiết một space",
 *     description="Trả về thông tin chi tiết của một space.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="space",
 *         in="path",
 *         required=true,
 *         description="ID của space",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Thông tin chi tiết space",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="venue_id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Phòng họp A"),
 *                 @OA\Property(property="capacity", type="integer", example=10),
 *                 @OA\Property(property="price", type="number", example=500000),
 *                 @OA\Property(property="description", type="string", example="Phòng họp hiện đại"),
 *                 @OA\Property(property="status", type="string", example="available"),
 *                 @OA\Property(property="space_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Meeting Space"),
 *                     @OA\Property(property="description", type="string", example="Không gian họp nhóm")
 *                 ),
 *                 @OA\Property(property="price_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="code", type="string", example="hourly"),
 *                     @OA\Property(property="name", type="string", example="Theo giờ"),
 *                     @OA\Property(property="name_en", type="string", example="Hourly")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Space không tồn tại")
 * )
 * 
 * @OA\Put(
 *     path="/api/spaces/{space}",
 *     tags={"Spaces"},
 *     summary="Cập nhật space",
 *     description="Chỉ chủ sở hữu hoặc quản lý của venue mới có quyền cập nhật space.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="space",
 *         in="path",
 *         required=true,
 *         description="ID của space cần cập nhật",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Phòng họp B"),
 *             @OA\Property(property="space_type_id", type="integer", example=1),
 *             @OA\Property(property="capacity", type="integer", example=20),
 *             @OA\Property(property="price_type_id", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=700000),
 *             @OA\Property(property="description", type="string", example="Phòng họp mới được cải tạo"),
 *             @OA\Property(property="status", type="string", enum={"available","unavailable"}, example="available")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cập nhật space thành công",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="venue_id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Phòng họp B"),
 *                 @OA\Property(property="capacity", type="integer", example=20),
 *                 @OA\Property(property="price", type="number", example=700000),
 *                 @OA\Property(property="description", type="string", example="Phòng họp mới được cải tạo"),
 *                 @OA\Property(property="status", type="string", example="available"),
 *                 @OA\Property(property="space_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Meeting Space"),
 *                     @OA\Property(property="description", type="string", example="Không gian họp nhóm")
 *                 ),
 *                 @OA\Property(property="price_type", type="object",
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="code", type="string", example="hourly"),
 *                     @OA\Property(property="name", type="string", example="Theo giờ"),
 *                     @OA\Property(property="name_en", type="string", example="Hourly")
 *                 ),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=403, description="Không có quyền cập nhật space"),
 *     @OA\Response(response=422, description="Dữ liệu không hợp lệ")
 * )
 * 
 * @OA\Get(
 *     path="/api/spaces",
 *     tags={"Spaces"},
 *     summary="Danh sách không gian",
 *     description="Lấy danh sách các không gian (spaces) với phân trang, sắp xếp và bộ lọc.",
 *     @OA\Parameter(
 *         name="sortBy",
 *         in="query",
 *         required=false,
 *         description="Trường để sắp xếp",
 *         @OA\Schema(type="string", enum={"venue_id","name","space_type_id","capacity","price_type_id","price","status","created_at","updated_at"})
 *     ),
 *     @OA\Parameter(
 *         name="sortOrder",
 *         in="query",
 *         required=false,
 *         description="Thứ tự sắp xếp",
 *         @OA\Schema(type="string", enum={"asc","desc"})
 *     ),
 *     @OA\Parameter(
 *         name="perPage",
 *         in="query",
 *         required=false,
 *         description="Số bản ghi mỗi trang (mặc định: 10)",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(name="venueId", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="wardId", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="provinceId", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="name", in="query", required=false, description="Tìm kiếm theo tên", @OA\Schema(type="string")),
 *     @OA\Parameter(name="spaceTypeId", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="priceTypeId", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="minCapacity", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="maxCapacity", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="minPrice", in="query", required=false, @OA\Schema(type="number", format="float")),
 *     @OA\Parameter(name="maxPrice", in="query", required=false, @OA\Schema(type="number", format="float")),
 *     @OA\Parameter(name="startTime", in="query", required=false, @OA\Schema(type="string", format="date-time")),
 *     @OA\Parameter(name="endTime", in="query", required=false, @OA\Schema(type="string", format="date-time")),
 *     @OA\Response(
 *         response=200,
 *         description="Danh sách không gian",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="statusCode", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="success"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer"),
 *                         @OA\Property(property="venue_id", type="integer"),
 *                         @OA\Property(property="venue", ref="#/components/schemas/Venue"),
 *                         @OA\Property(property="name", type="string"),
 *                         @OA\Property(property="space_type", ref="#/components/schemas/SpaceType"),
 *                         @OA\Property(property="capacity", type="integer"),
 *                         @OA\Property(property="price_type", ref="#/components/schemas/PriceType"),
 *                         @OA\Property(property="price", type="number", format="float"),
 *                         @OA\Property(property="description", type="string"),
 *                         @OA\Property(property="status", type="string", enum={"available","unavailable"}),
 *                         @OA\Property(property="created_at", type="string", format="date-time"),
 *                         @OA\Property(property="updated_at", type="string", format="date-time")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="meta",
 *                     type="object",
 *                     @OA\Property(property="current_page", type="integer"),
 *                     @OA\Property(property="last_page", type="integer"),
 *                     @OA\Property(property="per_page", type="integer"),
 *                     @OA\Property(property="total", type="integer")
 *                 ),
 *                 @OA\Property(
 *                     property="links",
 *                     type="object",
 *                     @OA\Property(property="first", type="string", format="uri"),
 *                     @OA\Property(property="last", type="string", format="uri"),
 *                     @OA\Property(property="prev", type="string", format="uri", nullable=true),
 *                     @OA\Property(property="next", type="string", format="uri", nullable=true)
 *                 )
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Venue",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="owner", ref="#/components/schemas/User"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="ward", ref="#/components/schemas/Ward"),
 *     @OA\Property(property="lat", type="number", format="float"),
 *     @OA\Property(property="lng", type="number", format="float"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="status", type="string", enum={"pending","approved","blocked"}),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Ward",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="name_en", type="string"),
 *     @OA\Property(property="full_name", type="string"),
 *     @OA\Property(property="full_name_en", type="string"),
 *     @OA\Property(property="province", ref="#/components/schemas/Province")
 * )
 * 
 * @OA\Schema(
 *     schema="Province",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="name_en", type="string"),
 *     @OA\Property(property="full_name", type="string"),
 *     @OA\Property(property="full_name_en", type="string")
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone_number", type="string", example="+84901234567"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-08-20 10:00:00"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(
 *         property="role",
 *         ref="#/components/schemas/Role",
 *         nullable=true
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-19 15:30:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-20 09:45:00")
 * )
 * 
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="venue_owner")
 * )
 *
 * @OA\Schema(
 *     schema="SpaceType",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string")
 * )
 *
 * @OA\Schema(
 *     schema="PriceType",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="name_en", type="string")
 * )
 */
class SpaceDocs {}
