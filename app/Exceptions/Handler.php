<?php

namespace App\Exceptions;

use App\Events\Notify;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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

    private array $SentryIgniored = [
        422,
    ];


    public function report(Throwable $exception)
    {
        if($exception->getMessage() == "This action is unauthorized."  && \Auth::check()){
            event(new Notify("Vous n'avez pas la permission",4, \Auth::user()->id));
        }

        if($exception->getCode() == 500 && \Auth::check()){
            event(new Notify("Ooops ! Une erreure est servenue",4, \Auth::user()->id));
        }

        if($exception->getCode() == 422 && \Auth::check()){
            event(new Notify("Le formulaire est mal rempli",4, \Auth::user()->id));
        }

        if (app()->bound('sentry')) {
            if(!in_array($exception->getCode(), $this->SentryIgniored)){
                app('sentry')->captureException($exception);
            }

        }

        return parent::report($exception);
    }
}
