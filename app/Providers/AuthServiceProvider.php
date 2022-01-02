<?php

namespace App\Providers;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use function React\Promise\Stream\first;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access', function (User $user){

            return $user->isAdmin() ? true : $user->GetGrade->access();
        });

        Gate::define('having_matricule', function (User $user){
            return $user->GetGrade()->first()->having_matricule;
        });

    }
}
