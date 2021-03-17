<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Seld\JsonLint\JsonParser;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {});
    }

    public function report(Throwable $e)
    {
        dd($e);
        $req = Http::post('https://discordapp.com/api/webhooks/821726372019830815/i1bx5rHXPfna6L8L8DDIEMXWgmrGDC_rjoay3q8qERqD-LD7oitfGhASX_fLQib1Gwvi',[
            'embeds'=>[
                [
                    'title'=>'Erreur',
                    'fields'=>[
                        [
                            'name'=>'Patient : ',
                            'value'=>'a',
                            'inline'=>false
                        ],[
                            'name'=>'Message : ',
                            'value'=>'',
                            'inline'=>false
                        ]
                    ],
                    'color'=>'15158332',

                ]
            ]
        ]);
        /*
                   /* 'embeds'=>[
                [
                    'title'=>'Erreur ' . 'J',
                    'fields' => [
                        [
                            'name'=>'',
                            'value'=>'',
                            'inline'=>false,
                        ]
                    ],
                    'color'=>'15158332',
                ]
            ],*/





        dd($req);

        event(new \App\Events\Notify('Error interne ! :(',4));

        return parent::report($e);
    }

}
