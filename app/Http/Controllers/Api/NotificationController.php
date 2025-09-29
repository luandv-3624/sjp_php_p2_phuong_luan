<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\Notification\NotificationServiceInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationServiceInterface $service)
    {
    }

    public function indexByUser(Request $request)
    {
        $user = $request->user();
        return $this->service->listByUser($user, $request->get('pageSize'));
    }

    public function markRead(Notification $notification)
    {
        return $this->service->markRead($notification);
    }
}
