<?php

namespace App\Policies;

use App\Models\BCList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlackCodePolicy
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
        if($user->OnService) return true;
        if(!$user->OnService && $grade->BC_HS) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BCList  $bCList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BCList $bCList)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($bCList->service === 'SAMS' && $grade->BC_medic_view) return true;
        if($bCList->service === 'LSCoFD' && $grade->BC_fire_view) return true;
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
        if($user->isAdmin() || $grade->BC_open) return true;
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
        if($user->isAdmin() || $grade->BC_edit) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BCList  $bCList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function close(User $user, BCList $bCList)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin() || $grade->BC_close) return true;
        return false;
    }

    /**
     * Determine whether the user can add and remove Patient.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BCList  $bCList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function ModifyPatient(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin() || $grade->BC_modify_patient) return true;
        return false;
    }

    /**
     * Determine whether the user can add Personnel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BCList  $bCList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addPersonnel(User $user, BCList $bCList)
    {
        $grade = $user->getUserGradeInService();
        if($bCList->service === 'SAMS') return true;
        if($user->isAdmin() || $grade->BC_fire_personnel_add) return true;
        return false;
    }


}
