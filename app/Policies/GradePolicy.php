<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradePolicy
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
        if($grade->view_grade_list) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Grade $grade)
    {
        if($user->isAdmin()) return true;
        $requesterGrade = $user->getUserGradeInService();
        if($requesterGrade->power > $grade->power) return true;
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
        if($grade->modify_grade && $this->viewAny($user))return  true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Grade $newGrade)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->modify_grade && $this->view($user, $newGrade) && !$newGrade->admin)return  true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        $grade = $user->getUserGradeInService();
        if($user->isAdmin()) return true;
        if($grade->modify_grade && $this->viewAny($user))return  true;
        return false;
    }

}
