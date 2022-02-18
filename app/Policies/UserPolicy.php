<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewRapportHoraire(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->view_rappportHoraire) return true;
        return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewPersonnelList(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->view_PersonnelList) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        $grade = $user->getUserGradeInService();
        $OtherGrade = $model->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->power > $OtherGrade->power) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setOtherService(User $user, User $model)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($this->view($user, $model) && $grade->set_other_service) return true;
        return false;

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function setPilote(User $user, User $model)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($this->view($user, $model) && $grade->set_pilote) return true;
        return false;

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\user  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, user $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\user  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, user $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\user  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, user $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\user  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, user $model)
    {
        //
    }
}
