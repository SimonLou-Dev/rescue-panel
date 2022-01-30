<?php

namespace App\Http\Controllers\Discord;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedPosting;
use App\Jobs\ProcessEmbedUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordApiController extends Controller
{

    public static function CallPostJobs($channel,$embed,$model,$msg){
        dispatch(new ProcessEmbedPosting($channel,$embed,$model,$msg));
    }

    public static function CallUpdateJobs($channel, $id,$embed,$msg){
        dispatch(new ProcessEmbedUpdate($channel, $id,$embed,$msg));
    }

}
