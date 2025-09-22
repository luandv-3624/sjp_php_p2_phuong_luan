<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentUpdated implements ShouldBroadcast
{
    public $requestId;
    public $status;

    public function __construct($requestId, $status)
    {
        $this->requestId = $requestId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('payment.' . $this->requestId);
    }
}
