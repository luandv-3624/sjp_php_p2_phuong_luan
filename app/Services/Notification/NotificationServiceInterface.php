<?php

namespace App\Services\Notification;

use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Notification;

interface NotificationServiceInterface
{
    public function listByUser(User $user, ?int $pageSize = null): JsonResponse;

    public function markRead(Notification $notification): JsonResponse;
}
