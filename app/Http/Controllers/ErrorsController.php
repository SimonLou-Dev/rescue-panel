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

    public function tunelSentry(request $request){

        $host = "sentry.io";
        $known_project_ids = ['6047890'];
        $envelope = $request->getContent();

        $pieces = explode("\n", $envelope,2);

        $header = json_decode($pieces[0], true);
        if (isset($header["dsn"])) {
            $dsn = parse_url($header["dsn"]);
            $project_id = intval(trim($dsn["path"], "/"));

            if (in_array($project_id, $known_project_ids)) {
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-sentry-envelope\r\n",
                        'method'  => 'POST',
                        'content' => $envelope
                    )
                );
                $cotent =  file_get_contents(
                    "https://$host/api/$project_id/envelope/",
                    false,
                    stream_context_create($options));
                return $cotent;
            }
        }
        return response()->json([],500);
    }
}
