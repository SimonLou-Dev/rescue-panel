<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;
use App\Facades\TimeInteractor;

class TimeCalculatorFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return TimeInteractor::class;
    }

}
