<?php

namespace App\Policies;

use App\Models\User;

class ControlPolicy
{
    /**
     * Determine if the user can view any controles.
     */
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can create controles.
     */
    public function create(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can update controles.
     */
    public function update(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can delete controles.
     */
    public function delete(User $user): bool
    {
        // Solo admin puede eliminar controles
        return $user->isAdmin();
    }
}


