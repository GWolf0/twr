<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user) {
        return $user->is_admin();
    }

    public function create(User $user) {
        return $user->is_admin();
    }

    public function store(User $user, User $model) {
        return $user->is_admin();
    }

    public function show(User $user, User $model) {
        return $user->is_admin();
    }

    public function edit(User $user, User $model) {
        return $user->is_admin();
    }

    public function update(User $user, User $model) {
        return $user->is_admin();
    }

    public function destroy(User $user, User $model) {
        return $user->is_admin();
    }

}
