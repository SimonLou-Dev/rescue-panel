<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ErrorsController extends Controller
{

    public function tunelSentry(request $request){

        $host = "sentry.simon-lou.com";
        $known_project_ids = ['3'];
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
