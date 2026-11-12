<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    /**
     * Determina si el usuario puede cancelar la reserva (R7).
     */
    public function cancelar(User $user, Reserva $reserva): bool
    {
        // Un turista solo puede cancelar si es el dueño de la reserva y el estado es 'pendiente'
        return $user->id === $reserva->user_id && $reserva->estado === 'pendiente';
    }
}