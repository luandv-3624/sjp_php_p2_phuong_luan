<?php

namespace App\Repositories\Notification;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function listByUser(User $user, ?int $pageSize = null): LengthAwarePaginator
    {
        return Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($pageSize ?? 10);
    }

    public function countUnreadByUser(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function markRead(Notification $notification): Notification
    {
        $notification->update(['is_read' => true]);
        return $notification;
    }
}
