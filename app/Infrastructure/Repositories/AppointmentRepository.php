<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Appointment\Models\Appointment;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentRepository
{
    public function getForUser($user, int $perPage = 15): LengthAwarePaginator
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        if ($user->role === 'patient') {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->role === 'doctor') {
            $query->where('doctor_id', $user->doctor->id);
        }

        return $query->latest('date_time')->paginate($perPage);
    }

    public function findById(int $id): ?Appointment
    {
        return Appointment::with(['doctor.user', 'patient.user'])->find($id);
    }
}
