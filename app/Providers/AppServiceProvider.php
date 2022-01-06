<?php

namespace App\Providers;

use App\Blade\ViteAssetLoader;
use App\Facade\DiscordInteractor;
use App\Facade\TimeInteractor;
use App\Facade\UserNotificationInteractor;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\LegacyUserProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ViteAssetLoader::class, function (Application $app){
            return new ViteAssetLoader(
                env('FRONT_DEBUG'),
                public_path('assets/manifest.json'),
                $app->get('cache.store')
            );
        });

        $this->app->singleton( DiscordInteractor::class, function(Application $app){
            return new DiscordInteractor(
                $app->get('cache.store')
            );
        });

        $this->app->singleton( TimeInteractor::class, function(Application $app){
            return new TimeInteractor(

            );
        });

        $this->app->singleton( UserNotificationInteractor::class, function(Application $app){
            return new UserNotificationInteractor(

            );
        });

        if($this->app->environment('local')){
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        //https://laracasts.com/discuss/channels/laravel/how-to-disable-password-hasing-of-auth
        /*
        Auth::provider('legacy', function ($app, array $config) {
            return new LegacyUserProvider($config['model']);
        });*/
        Auth::provider('legacy', function ($app, array $config) {
            return new LegacyUserProvider($config['model']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(141);
    }
}
