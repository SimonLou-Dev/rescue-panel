<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UserUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $user,

    )
    {
        if($this->user->service === "SAMS"){
            $this->user->grade = $this->user->GetMedicGrade;
        }else if($this->user->service === "LSCoFD"){
            $this->user->grade = $this->user->GetFireGrade;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {

        return new PrivateChannel('User.'.env('APP_ENV').'.'.$this->user->id);

    }

    public function broadcastAs(): string
    {
        return 'UserUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'userId' => $this->user->id,
            'userInfos' => $this->user,
        ];
    }
}
