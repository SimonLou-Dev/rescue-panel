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
use Illuminate\Support\Facades\Auth;

class NotifyForAll implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public string $text,
        public int $type,
    )
    {}

    public function broadcastOn()
    {
        return new PresenceChannel('GlobalChannel.'.env('APP_ENV'));
    }

    public function broadcastAs(): string
    {
        return 'notify';
    }

    public function broadcastWith(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->text,
        ];
    }
}
