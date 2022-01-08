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

class Notify implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;




    public function __construct(
        public string $text,
        public int $type,
        public ?int $id = null

    )
    {
        if(is_null($id)){
            $this->id == Auth::user()->id;
        }

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //'User.'.env('APP_ENV').'.{userid}'
        return new PrivateChannel('User.'.env('APP_ENV').'.'.$this->id);

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
