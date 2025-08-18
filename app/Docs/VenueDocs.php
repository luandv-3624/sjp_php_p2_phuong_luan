<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
* @OA\Server(
*     url="http://localhost:8000",
*     description="Localhost server"
* )
*
* Create Venue
*
* @OA\Post(
*     path="/api/venues",
*     tags={"Venue"},
*     summary="Tạo mới venue",
*     security={{"bearerAuth":{}}},
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"name", "address", "ward_id", "lat", "lng"},
*             @OA\Property(property="name", type="string", example="Sun Co-working Space"),
*             @OA\Property(property="address", type="string", example="123 Lê Lợi, Q1, HCM"),
*             @OA\Property(property="ward_id", type="integer", example=101),
*             @OA\Property(property="lat", type="number", format="float", example=10.762622),
*             @OA\Property(property="lng", type="number", format="float", example=106.660172),
*             @OA\Property(property="description", type="string", example="Không gian hiện đại cho startup")
*         )
*     ),
*     @OA\Response(response=201, description="Venue created successfully"),
*     @OA\Response(response=400, description="Validation error")
* )
*
*  Update Venue
*
* @OA\Put(
*     path="/api/venues/{id}",
*     tags={"Venue"},
*     summary="Cập nhật venue (chỉ Owner)",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             @OA\Property(property="name", type="string", example="Sun Coworking Space Updated"),
*             @OA\Property(property="address", type="string", example="456 Nguyễn Huệ, Q1, HCM"),
*             @OA\Property(property="ward_id", type="integer", example=102),
*             @OA\Property(property="lat", type="number", format="float", example=10.772622),
*             @OA\Property(property="lng", type="number", format="float", example=106.670172),
*             @OA\Property(property="description", type="string", example="Không gian mở, thêm nhiều phòng họp")
*         )
*     ),
*     @OA\Response(response=200, description="Venue updated successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Venue not found")
* )
*
* Delete Venue
*
* @OA\Delete(
*     path="/api/venues/{id}",
*     tags={"Venue"},
*     summary="Xóa venue (chỉ Owner)",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\Response(response=200, description="Venue deleted successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Venue not found")
* )
*
* Venue Detail
*
* @OA\Get(
*     path="/api/venues/{id}",
*     tags={"Venue"},
*     summary="Xem chi tiết venue",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\Response(response=200, description="Get venue detail successfully"),
*     @OA\Response(response=404, description="Venue not found")
* )
*
* @OA\Post(
*     path="/api/venues/{id}/managers",
*     tags={"Venue"},
*     summary="Thêm manager cho venue (chỉ Owner)",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"user_ids"},
*             @OA\Property(
*                 property="user_ids",
*                 type="array",
*                 @OA\Items(type="integer", example=5)
*             )
*         )
*     ),
*     @OA\Response(response=200, description="Managers added successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Venue not found")
* )
*
* Update Venue Status
*
* @OA\PUT(
*     path="/api/venues/{id}/status",
*     tags={"Venue"},
*     summary="Admin/Moderator cập nhật trạng thái venue",
*     security={{"bearerAuth":{}}},
*     @OA\Parameter(
*         name="id",
*         in="path",
*         required=true,
*         description="ID của venue",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"status"},
*             @OA\Property(property="status", type="string", example="APPROVED")
*         )
*     ),
*     @OA\Response(response=200, description="Venue status updated successfully"),
*     @OA\Response(response=403, description="Permission denied"),
*     @OA\Response(response=404, description="Venue not found")
* )
**/

class VenueDocs
{
}
