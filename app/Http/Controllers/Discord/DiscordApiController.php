<?php

namespace App\Http\Controllers\Discord;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordApiController extends Controller
{

    public static function CallJobs($channel,$embed,$model,$msg){
        dispatch(new ProcessEmbedPosting($channel,$embed,$model,$msg));
    }

}
