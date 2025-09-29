<?php

namespace App\Repositories\Notification;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function listByUser(User $user, ?int $pageSize = null): LengthAwarePaginator;

    public function countUnreadByUser(User $user): int;

    public function create(array $data): Notification;

    public function markRead(Notification $notification): Notification;
}
