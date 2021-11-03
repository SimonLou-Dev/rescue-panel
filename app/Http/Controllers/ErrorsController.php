<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ErrorsController extends Controller
{
    public function frontErrors(request $request){
        Log::error('[FRONT ERROR] cf discord');


        Http::post(env('WEBHOOK_ERRORS'),[
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                'title'=>'Erreur de front',
                'color'=>'10368531',
                'description'=> implode(' | ', $request->error),
                'footer'=> [
                    'text' => 'Information de : ' . Auth::user()->name
                ]
            ],[
                'title'=>'Erreur de front (infos)',
                'color'=>'10368531',
                'description'=>implode(' | ', $request->errorInfo),
                'footer'=> [
                    'text' => 'Information de : ' . Auth::user()->name
                ],
            ]
        ]);
    }
}
