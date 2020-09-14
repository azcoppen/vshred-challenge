<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index (User $user)
    {
        return true;
    }

    public function store (User $user)
    {
        return $user->hasRole('admin');
    }

    public function show (User $user, User $target)
    {
        return $user->id === $target->id || $user->hasRole('admin');
    }

    public function update (User $user, User $target)
    {
        return $user->id === $target->id || $user->hasRole('admin');
    }

    public function destroy (User $user, User $target)
    {
        return $user->hasRole('admin');
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
