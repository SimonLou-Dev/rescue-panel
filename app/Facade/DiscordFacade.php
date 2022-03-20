<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class DiscordFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return DiscordInteractor::class;
    }

}
