<?php

namespace App\Policies;

use App\Models\LieuxSurvol;
use App\Models\User;
use App\Models\Vol;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolPolicy
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
        return ($user->isAdmin() || $user->pilote);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vol  $vol
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Vol $vol)
    {
        return ((($user->isAdmin() || $user->pilote) && $vol->service == $user->service) || $user->dev);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewPlace(User $user)
    {
        return (($user->isAdmin() || $user->pilote) || $user->dev);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return ($user->isAdmin() || $user->pilote);
    }


}
