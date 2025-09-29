<?php

namespace App\Services\Notification;

use App\Helpers\ApiResponse;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\Notification\NotificationResource;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\Response;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepo
    ) {
    }

    public function listByUser(User $user, ?int $pageSize = null): JsonResponse
    {
        $notifications = $this->notificationRepo->listByUser($user, $pageSize);
        $unreadCount   = $this->notificationRepo->countUnreadByUser($user);

        return ApiResponse::success(
            (new NotificationCollection($notifications))
               ->additional(['unread_count' => $unreadCount])
        );
    }

    public function markRead(Notification $notification): JsonResponse
    {
        $notification = $this->notificationRepo->markRead($notification);
        return ApiResponse::success(new NotificationResource($notification), __('notification.mark_read_success'));
    }
}
