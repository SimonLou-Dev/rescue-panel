<?php

namespace App\Policies;

use App\Models\Rapport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RapportsPolicy
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
        if($user->isAdmin) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rapport  $rapport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Rapport $rapport)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($user->service == $rapport->service){
            if($user->OnService && $grade->rapport_view && $grade->rapport_modify) return true;
            if(!$user->OnService && $grade->rapport_view && $grade->rapport_HS && $grade->rapport_modify) return true;
        }
        if($user->id == $rapport->user_id){
            if($user->OnService && $grade->rapport_view && $grade->rapport_modify) return true;
            if(!$user->OnService && $grade->rapport_view && $grade->rapport_HS && $grade->rapport_modify) return true;
        }
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
        if($user->OnService && $grade->rapport_create) return true;
        if(!$user->OnService && $grade->rapport_create && $grade->rapport_HS) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rapport  $rapport
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Rapport $rapport)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($rapport->service == $user->service){
            if($user->OnService && $grade->rapport_modify) return true;
            if(!$user->OnService && $grade->rapport_modify && $grade->rapport_HS) return true;
        }
        return false;
    }




}
