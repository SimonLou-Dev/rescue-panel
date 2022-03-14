<?php

namespace App\Events;

use App\Models\Grade;
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

        if(is_null($this->user->service)){
            $this->user->grade = Grade::first();
        }else{
            if($this->user->service === 'SAMS'){
                $this->user->grade = Grade::where('id', $this->user->medic_grade_id)->first();
            }else{
                $this->user->grade = Grade::where('id', $this->user->fire_grade_id)->first();
            }
        }


        $collect = collect($this->user->grade->getAttributes());
        $collect = $collect->except(['service','name','power','discord_role_id','id']);
        foreach ($collect as $key => $item){
            $b = $this->user->grade->getAttributeValue($key);
            $this->user->grade[$key] = ($b === "1" || $b === true || $b === 1 );
        }
        $fireGrade = Grade::where('id', $this->user->fire_grade_id)->first();
        $medicGrade = Grade::where('id', $this->user->medic_grade_id)->first();
        $this->user->fire_grade_name = $fireGrade->name;
        $this->user->medic_grade_name = $medicGrade->name;
        $this->user->sanctions = json_decode($this->user->sanctions);
        $this->user->materiel = json_decode($this->user->materiel);
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
