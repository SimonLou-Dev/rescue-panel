<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private array $finalList;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Collection $users,
        public string $service,
    )
    {
        $this->finalList = [];

        foreach ($users as $user){
            array_push($this->finalList, ['name'=>$user->name, 'id'=>$user->id]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|PresenceChannel|array
    {
        return new PresenceChannel('GlobalChannel.'.env('APP_ENV'));
    }

    public function broadcastAs(): string
    {
        return 'ServiceUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'users' => $this->finalList,
            'service' => $this->service,
        ];
    }
}
