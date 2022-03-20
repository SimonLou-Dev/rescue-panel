<?php

namespace App\Policies;

use App\Models\User;
use App\Models\facture;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($user->OnService && $grade->facture_view) return true;
        if(!$user->OnService && $grade->facture_view && $grade->facture_HS) return true;
        return false;
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($this->viewAny($user) && $grade->facture_create) return true;
        return false;
    }

    /**
     * Determine whether the user can paye facture.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function paye(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($this->viewAny($user) && $grade->facture_paye) return true;
        return false;
    }

    /**
     * Determine whether the user can export the factures.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function export(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($this->viewAny($user) && $grade->facture_export) return true;
        return false;
    }


}
