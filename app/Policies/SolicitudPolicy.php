<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Solicitud;

class SolicitudPolicy
{
    /**
     * Determine if the user can view any solicitudes.
     */
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red']);
    }

    /**
     * Determine if the user can view the solicitud.
     */
    public function view(User $user, Solicitud $solicitud): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red']);
    }

    /**
     * Determine if the user can create solicitudes.
     */
    public function create(User $user): bool
    {
        // Cualquiera puede crear una solicitud (formulario público)
        return true;
    }

    /**
     * Determine if the user can update the solicitud.
     */
    public function update(User $user, Solicitud $solicitud): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red']);
    }

    /**
     * Determine if the user can delete the solicitud.
     */
    public function delete(User $user, Solicitud $solicitud): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can crear usuario desde solicitud.
     */
    public function crearUsuario(User $user, Solicitud $solicitud): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red']);
    }
}


