<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $payload;
    private $pollId;

    public function __construct($payload, $pollId)
    {
        $this->payload = $payload;
        $this->pollId = $pollId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('poll.' . $this->pollId);
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
