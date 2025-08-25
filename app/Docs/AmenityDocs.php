<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
* @OA\Server(
*     url="http://localhost:8000",
*     description="Localhost server"
* )
*
* =============================
* 📌 Amenity API Documentation
* =============================
*
* Create Amenity
*
* @OA\Post(
*     path="/api/amenities",
*     tags={"Amenity"},
*     summary="Tạo tiện ích mới (chỉ Owner/Manager)",
*     security={{"bearerAuth":{}}},
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"code","name","venue_id"},
*             @OA\Property(property="code", type="string", example="WIFI"),
*             @OA\Property(property="name", type="string", example="Wifi tốc độ cao"),
*             @OA\Property(property="description", type="string", example="Wifi miễn phí toàn khu vực"),
*             @OA\Property(property="venue_id", type="integer", example=1)
*         )
*     ),
*     @OA\Response(response=201, description="Amenity created successfully"),
*     @OA\Response(response=400, description="Validation error"),
*     @OA\Response(response=403, description="Permission denied")
* )
*
* Update Amenity
*
* @OA\Put(
*     path="/api/amenities/{id}",
*     tags={"Amenity"},
*     summary="Cập nhật tiện ích (chỉ Owner/Manager)",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của amenity",
*         @OA\Schema(type="integer", example=10)
*     ),
*     @OA\RequestBody(
*         @OA\JsonContent(
*             @OA\Property(property="code", type="string", example="PARKING"),
*             @OA\Property(property="name", type="string", example="Bãi giữ xe"),
*             @OA\Property(property="description", type="string", example="Có bãi giữ xe máy và ô tô"),
*             @OA\Property(property="venue_id", type="integer", example=1)
*         )
*     ),
*     @OA\Response(response=200, description="Amenity updated successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Amenity not found")
* )
*
* Delete Amenity
*
* @OA\Delete(
*     path="/api/amenities/{id}",
*     tags={"Amenity"},
*     summary="Xóa tiện ích (chỉ Owner/Manager)",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của amenity",
*         @OA\Schema(type="integer", example=10)
*     ),
*     @OA\Response(response=200, description="Amenity deleted successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Amenity not found")
* )
*
* Get Amenity Detail
*
* @OA\Get(
*     path="/api/amenities/{id}",
*     tags={"Amenity"},
*     summary="Xem chi tiết tiện ích",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của amenity",
*         @OA\Schema(type="integer", example=10)
*     ),
*     @OA\Response(response=200, description="Get amenity detail successfully"),
*     @OA\Response(response=404, description="Amenity not found")
* )
*
* List Amenities by Venue
*
* @OA\Get(
*     path="/api/venues/{id}/amenities",
*     tags={"Amenity"},
*     summary="Danh sách tiện ích theo venue",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\Parameter(
*         name="sortBy",
*         in="query",
*         description="Sắp xếp theo field",
*         required=false,
*         @OA\Schema(type="string", example="name")
*     ),
*     @OA\Parameter(
*         name="sortOrder",
*         in="query",
*         description="Thứ tự sắp xếp asc/desc",
*         required=false,
*         @OA\Schema(type="string", example="asc")
*     ),
*     @OA\Parameter(
*         name="search",
*         in="query",
*         description="Tìm kiếm theo tên/code",
*         required=false,
*         @OA\Schema(type="string", example="wifi")
*     ),
*     @OA\Parameter(
*         name="code",
*         in="query",
*         description="Lọc theo mã code",
*         required=false,
*         @OA\Schema(type="string", example="WIFI")
*     ),
*     @OA\Parameter(
*         name="pageSize",
*         in="query",
*         description="Số lượng item mỗi trang",
*         required=false,
*         @OA\Schema(type="integer", example=10)
*     ),
*     @OA\Response(response=200, description="Get list amenities by venue successfully"),
*     @OA\Response(response=404, description="Venue not found")
* )
**/

class AmenityDocs
{
}
