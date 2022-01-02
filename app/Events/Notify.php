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
     * @var string $title
     */
    public $title;

    /**
     * Notify constructor.
     * @var int $id
     */
    public $id;

    public function __construct(string $text, int $type, string $title = null)
    {
        $this->text = $text;
        $this->type = $type;
        $this->id = Auth::user()->id;
        $this->title = $title;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
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
            'title'=> $this->title
        ];
    }
}
