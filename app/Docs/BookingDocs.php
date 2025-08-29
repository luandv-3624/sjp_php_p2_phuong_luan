<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Bookings",
 *     description="API endpoints for managing bookings"
 * )
 *
 * @OA\Post(
 *     path="/api/bookings",
 *     tags={"Bookings"},
 *     summary="Create a booking",
 *     description="Người dùng có thể đặt chỗ cho không gian có sẵn.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"space_id","start_time","end_time"},
 *             @OA\Property(property="space_id", type="integer", example=1, description="ID của không gian"),
 *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-08-22T10:00:00Z"),
 *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-08-22T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tạo booking thành công",
 *         @OA\JsonContent(ref="#/components/schemas/BookingResource")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Space not found"
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Space unavailable / invalid time / overlap"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/bookings",
 *     tags={"Bookings"},
 *     summary="Get list of bookings",
 *     description="Chỉ admin hoặc moderator mới được gọi API này. Có hỗ trợ filter, sort, pagination.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="userId", in="query", required=false, @OA\Schema(type="integer"), description="Filter theo user"),
 *     @OA\Parameter(name="spaceId", in="query", required=false, @OA\Schema(type="integer"), description="Filter theo space"),
 *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"pending","confirmed-unpaid","paid-pending","partial-pending","accepted","done"})),
 *     @OA\Parameter(name="statusPayment", in="query", required=false, @OA\Schema(type="string", enum={"unpaid","partial","paid"})),
 *     @OA\Parameter(name="startTime", in="query", required=false, @OA\Schema(type="string", format="date-time")),
 *     @OA\Parameter(name="endTime", in="query", required=false, @OA\Schema(type="string", format="date-time")),
 *     @OA\Parameter(name="sortBy", in="query", required=false, @OA\Schema(type="string", enum={"user_id","space_id","status","status_payment","total_price","created_at","updated_at"})),
 *     @OA\Parameter(name="sortOrder", in="query", required=false, @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Parameter(name="perPage", in="query", required=false, @OA\Schema(type="integer", minimum=1, maximum=100)),
 *     @OA\Response(
 *         response=200,
 *         description="Danh sách bookings phân trang",
 *         @OA\JsonContent(ref="#/components/schemas/BookingCollection")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/bookings/{booking}",
 *     tags={"Bookings"},
 *     summary="Get booking details",
 *     description="Người dùng chỉ có thể xem booking của mình. Admin/moderator có thể xem tất cả.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="booking",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Chi tiết booking",
 *         @OA\JsonContent(ref="#/components/schemas/BookingResource")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Booking not found"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookingResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="space", ref="#/components/schemas/SpaceResource"),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-08-22T10:00:00Z"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-08-22T12:00:00Z"),
 *     @OA\Property(property="check_in", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="check_out", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="status_payment", type="string", example="unpaid"),
 *     @OA\Property(property="payments", type="array", @OA\Items(ref="#/components/schemas/Payment")),
 *     @OA\Property(property="total_price", type="number", format="float", example=200.50),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="BookingCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BookingResource")),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="total", type="integer"),
 *     ),
 *     @OA\Property(property="links", type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true),
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="phone_number", type="string", example="+84912345678"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="SpaceResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=2),
 *     @OA\Property(property="venue_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Meeting Room A"),
 *     @OA\Property(property="capacity", type="integer", example=10),
 *     @OA\Property(property="price", type="number", format="float", example=50),
 *     @OA\Property(property="description", type="string", example="Spacious meeting room"),
 *     @OA\Property(property="status", type="string", example="available"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="Payment",
 *   type="object",
 *   title="Payment Resource",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="method", type="string", example="credit_card"),
 *   @OA\Property(property="amount", type="number", format="float", example=120.50),
 *   @OA\Property(property="status", type="string", example="paid"),
 *   @OA\Property(property="paid_at", type="string", format="date-time", nullable=true, example="2025-08-28T12:30:00Z")
 * )
 *
 * @OA\Put(
 *     path="/api/bookings/{booking}/status",
 *     tags={"Bookings"},
 *     summary="Update booking status",
 *     description="Cập nhật trạng thái của một booking.
 *     - Người dùng chỉ có thể **cancel** booking của chính mình.
 *     - Venue owner/manager có thể cập nhật sang các trạng thái khác (confirmed-unpaid, accepted, rejected...).",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="booking",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=12)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"pending","confirmed-unpaid","paid-pending","partial-pending","accepted","rejected","cancelled","done"},
 *                 example="accepted",
 *                 description="Trạng thái mới của booking"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cập nhật trạng thái thành công",
 *         @OA\JsonContent(ref="#/components/schemas/BookingResource")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - chưa đăng nhập"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - không có quyền cập nhật booking này"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error - trạng thái không hợp lệ"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/bookings/{booking}/check-in",
 *     tags={"Bookings"},
 *     summary="Check-in booking",
 *     description="Người dùng thực hiện check-in cho booking đã **được chấp nhận (accepted)**.
 *     - Chỉ được check-in trong khoảng thời gian booking có hiệu lực.
 *     - Không thể check-in nếu đã check-in trước đó.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="booking",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Check-in thành công",
 *         @OA\JsonContent(ref="#/components/schemas/BookingResource")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Lỗi nghiệp vụ (ví dụ: chưa tới giờ check-in, đã check-in, booking chưa được accepted...)"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/bookings/{booking}/check-out",
 *     tags={"Bookings"},
 *     summary="Check-out booking",
 *     description="Người dùng thực hiện check-out cho booking đã check-in.
 *     - Booking phải có **status = accepted**.
 *     - Không thể check-out nếu chưa check-in hoặc đã check-out trước đó.
 *     - Thời gian check-out phải nằm trong thời gian booking.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="booking",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Check-out thành công",
 *         @OA\JsonContent(ref="#/components/schemas/BookingResource")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Lỗi nghiệp vụ (ví dụ: chưa check-in, đã check-out, booking chưa được accepted...)"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class BookingDocs
{
}
