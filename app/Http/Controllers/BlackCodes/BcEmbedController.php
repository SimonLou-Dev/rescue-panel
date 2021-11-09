<?php

namespace App\Http\Controllers\BlackCodes;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedBCGenerator;
use App\Models\BCList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BcEmbedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function generateBCEndedEmbed(string $formated, object $patients, object $personnels,BCList $bc){
        dispatch( new ProcessEmbedBCGenerator($formated, $patients, $personnels, $bc, Auth::user()->name));
    }

}
