<?php

namespace App\Http\Controllers\Discord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordApiController extends Controller
{




    public static function getIdAndReactOnMsg(string $channel, string $message): array
    {
        $req = Http::withHeaders([
            'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
        ])->get('https://discord.com/api/v9/channels/'.$channel.'/messages/'.$message)->json();
        $reaction = array();

        if(isset($req['reactions'])){
            foreach($req['reactions'] as $react){
                $emoji = $react['emoji']['name'];
                $reaction[$emoji] = [];

                $request = Http::withHeaders([
                    'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
                ])->get('https://discord.com/api/v9/channels/'.$channel."/messages/" .$message.'/reactions/'.urlencode($emoji))->json();
                foreach($request as $reqst){
                    array_push($reaction[$emoji], $reqst['id']);
                }
            }
        }

        return $reaction;
    }

    public static function getUserOnReactList(array $reactList): array
    {
        //get user on react list
    }



}
