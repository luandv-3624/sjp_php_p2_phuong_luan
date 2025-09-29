<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'message'   => $this->message,
            'data'      => $this->data,
            'is_read'   => $this->is_read,
            'created_at' => $this->created_at,
        ];
    }
}
