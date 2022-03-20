<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;


class TimeCalculatorFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return TimeInteractor::class;
    }

}
