<?php

namespace App\Policies;

use App\Models\TestPoudre;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PouderTestPolicy
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
        if($user->service && $grade->poudretest_view) return true;
        if(!$user->service && $grade->poudretest_HS) return true;
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
        if($this->viewAny($user) && $grade->poudretest_create) return true;
        return false;
    }


}
