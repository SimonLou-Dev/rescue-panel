<?php

namespace App\Policies;

use App\Models\Prime;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrimePolicy
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
        if($grade->viewAll_prime_req) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewMy(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->view_prime_req) return true;
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
        if($grade->post_prime_req) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->modify_prime_req) return true;
        return false;
    }


}
