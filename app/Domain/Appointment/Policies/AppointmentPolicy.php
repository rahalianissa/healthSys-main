<?php

namespace App\Domain\Appointment\Policies;

use App\Domain\User\Models\User;
use App\Domain\Appointment\Models\Appointment;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'secretaire') return true;
        if ($user->role === 'doctor') return $appointment->doctor_id === $user->doctor->id;
        if ($user->role === 'patient') return $appointment->patient_id === $user->patient->id;
        return false;
    }

    public function confirm(User $user, Appointment $appointment): bool
    {
        return $user->role === 'secretaire';
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'secretaire') return true;
        if ($user->role === 'patient') return $appointment->patient_id === $user->patient->id;
        return false;
    }
}
