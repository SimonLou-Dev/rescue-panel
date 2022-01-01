<?php

namespace App\Providers;


use App\Events\UserRegisterEvent;
use App\Listeners\UserLogInListener;
use App\Listeners\UserLogOutListener;
use App\Listeners\UserRegisterListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\Discord\DiscordExtendSocialite::class.'@handle',
        ],
        Login::class => [
            UserLogInListener::class
        ],
        Logout::class => [
            UserLogOutListener::class
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {


    }
}
