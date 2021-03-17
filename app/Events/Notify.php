<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class Notify implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     * @var string $text
     */
    public $text;
    /**
     * @var int $type
     */
    public $type;

    /**
     * Notify constructor.
     * @var int $id
     */
    public $id;

    public function __construct(string $text, int $type)
    {
        dd(Auth::user());
        $this->text = $text;
        $this->type = $type;
        $this->id = Auth::user()->id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('UserChannel_'.$this->id);

    }

    public function broadcastAs(): string
    {
        return 'notify';
    }
}
