<?php

namespace App\Providers;

use App\Models\AbsencesList;
use App\Models\BCList;
use App\Models\Facture;
use App\Models\Grade;
use App\Models\ModifyServiceReq;
use App\Models\Prime;
use App\Models\Rapport;
use App\Models\TestPoudre;
use App\Models\User;
use App\Policies\AbsencesPolicy;
use App\Policies\BlackCodePolicy;
use App\Policies\FacturesPolicy;
use App\Policies\PouderTestPolicy;
use App\Policies\PrimePolicy;
use App\Policies\RapportsPolicy;
use App\Policies\ServiceReqPolicy;
use App\Policies\UserPolicy;
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
        Rapport::class => RapportsPolicy::class,
        BCList::class => BlackCodePolicy::class,
        TestPoudre::class => PouderTestPolicy::class,
        Facture::class => FacturesPolicy::class,
        User::class => UserPolicy::class,
        AbsencesList::class, AbsencesPolicy::class,
        Prime::class, PrimePolicy::class,
        ModifyServiceReq::class, ServiceReqPolicy::class,
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
            if(is_null($user->GetFireGrade) ||  is_null($user->GetMedicGrade)) return false;
            return $user->isAdmin() || $user->GetFireGrade->access || $user->GetMedicGrade->access;
        });

        Gate::define('having_matricule', function (User $user){
            return ($user->GetFireGrade->having_matricule || $user->GetMedicGrade->having_matricule);
        });

        Gate::define('patient-edit', function (User $user){
            $grade = $user->getUserGradeInService();
           return ($user->isAdmin() || $grade->patient_edit) ;
        });

    }
}
