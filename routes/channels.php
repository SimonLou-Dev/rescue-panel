<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('User.'.env('APP_ENV').'.{userid}', function ($user, $userid){
   return $user->id == $userid;
});


Broadcast::channel('GlobalChannel.'.env('APP_ENV'), function ($user){
   return ['id'=>$user->id, 'name'=>$user->name];
});





