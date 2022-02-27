<?php

namespace App\Http\Controllers;

use App\Enums\DiscordChannel;
use App\Models\User;
use Illuminate\Http\Request;

class DiscordChannelController extends Controller
{

    public function getChannel(){
        $this->authorize('modify_discord',User::class);
       return response()->json([
            'channels'=>$this->getCachedChannel(),
        ]);
    }

    public function postChannel(Request $request){
        $this->authorize('modify_discord',User::class);
        $newChannels = $request->channel;
        $lastChannel = $this->getCachedChannel();
        $channelsList = array();
        foreach (DiscordChannel::asArray() as $chann){
            array_push($channelsList, $chann);
        }


        for ($a = 0; $a < count($newChannels); $a++){
            $item = $channelsList[$a];
            if($newChannels[$item] != $lastChannel[$item]){
                \Discord::chanUpdate($item, $newChannels[$item]);
            }
        }

        return self::getChannel();
    }

    private function getCachedChannel(){
        $channels = DiscordChannel::asArray();
        $finalArray = array();
        foreach ($channels as $value){
            $finalArray[$value] = \Discord::chanGet($value);
        }
        return $finalArray;
    }

}
