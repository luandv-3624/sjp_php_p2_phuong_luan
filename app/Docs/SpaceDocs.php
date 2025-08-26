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
 */
class SpaceDocs {}
