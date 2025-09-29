<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'  => NotificationResource::collection($this->collection),
            'unread_count' => $this->additional['unread_count'],
            'meta'  => [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'total'        => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last'  => $this->url($this->lastPage()),
                'prev'  => $this->previousPageUrl(),
                'next'  => $this->nextPageUrl(),
            ],
        ];
    }
}
