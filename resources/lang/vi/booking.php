<?php

return [
    'space_not_found' => 'Không tìm thấy không gian đã chọn.',
    'space_unavailable' => 'Không gian hiện không có sẵn để đặt.',
    'invalid_time' => 'Thời gian bắt đầu và kết thúc không hợp lệ.',
    'space_already_booked' => 'Không gian đã được đặt trong khoảng thời gian đã chọn.',
    'create_success' => 'Đặt chỗ thành công.',
    'booking_not_found' => 'Không tìm thấy đặt chỗ.',
    'venue_not_approved' => 'Địa điểm chưa được phê duyệt.',
    'hello'           => 'Xin chào',
    'thank_you'       => 'Cảm ơn bạn',
    'mail_subject'    => 'Thông báo cập nhật trạng thái đặt phòng',
    'mail_confirmed'  => 'Yêu cầu đặt phòng ":space" của bạn đã được xác nhận. Vui lòng tiến hành thanh toán.',
    'mail_rejected'   => 'Rất tiếc, yêu cầu đặt phòng ":space" của bạn đã bị từ chối.',
    'mail_accepted'   => 'Thanh toán cho yêu cầu đặt phòng ":space" của bạn đã được xác nhận. Bạn có thể đến nhận phòng.',
    'status_updated'  => 'Cập nhật trạng thái đặt phòng thành công.',
    'cannot_cancel_in_status' => 'Đơn hàng đã ở trạng thái :status nên bạn không thể hủy, hãy liên hệ với quản trị viên của space để được hỗ trợ.',
    'must_be_accepted_to_checkin' => 'Chỉ khi đơn đặt chỗ ở trạng thái ACCEPTED mới được check-in.',
    'already_checked_in'          => 'Bạn đã check-in rồi.',
    'invalid_checkin_time'        => 'Thời gian check-in phải nằm trong khoảng thời gian đặt chỗ.',
    'checkin_success'             => 'Check-in thành công.',
    'must_be_accepted_to_checkout' => 'Chỉ khi đơn đặt chỗ ở trạng thái ACCEPTED mới được check-out.',
    'must_checkin_first'           => 'Bạn cần check-in trước khi check-out.',
    'already_checked_out'          => 'Bạn đã check-out rồi.',
    'invalid_checkout_time'        => 'Thời gian check-out phải nằm giữa lúc check-in và thời gian kết thúc.',
    'checkout_success'             => 'Check-out thành công.',
    'notification' => [
        'confirmed_unpaid' => [
            'title' => 'Đặt chỗ đã được xác nhận',
            'message' => 'Đặt chỗ #:id của bạn đã được xác nhận. Vui lòng hoàn tất thanh toán.',
        ],
        'rejected' => [
            'title' => 'Đặt chỗ bị từ chối',
            'message' => 'Đặt chỗ #:id của bạn đã bị từ chối. Vui lòng liên hệ hỗ trợ để biết thêm chi tiết.',
        ],
        'accepted' => [
            'title' => 'Đặt chỗ được chấp nhận',
            'message' => 'Đặt chỗ #:id của bạn đã được chấp nhận. Chúng tôi rất hân hạnh phục vụ bạn.',
        ],
    ],
];
