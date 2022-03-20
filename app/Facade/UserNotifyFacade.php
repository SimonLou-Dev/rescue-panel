<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class UserNotifyFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return UserNotificationInteractor::class;
    }

}
