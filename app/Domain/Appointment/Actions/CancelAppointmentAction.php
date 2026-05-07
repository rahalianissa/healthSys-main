<?php

namespace App\Domain\Appointment\Actions;

use App\Domain\Appointment\Models\Appointment;
use Exception;

class CancelAppointmentAction
{
    public function execute(Appointment $appointment, ?string $reason, int $userId): Appointment
    {
        if ($appointment->status === Appointment::STATUS_COMPLETED) {
            throw new Exception("Impossible d'annuler un rendez-vous déjà terminé.");
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
            'cancelled_by' => $userId,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        return $appointment;
    }
}
