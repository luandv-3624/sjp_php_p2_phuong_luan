<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCreated implements ShouldBroadcast
{
    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->notification->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'data' => $this->notification->data,
        ];
    }
}
