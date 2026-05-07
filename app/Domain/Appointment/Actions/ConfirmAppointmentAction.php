<?php

namespace App\Domain\Appointment\Actions;

use App\Domain\Appointment\Models\Appointment;
use App\Domain\Appointment\Services\AvailabilityService;
use Exception;

class ConfirmAppointmentAction
{
    public function __construct(private AvailabilityService $availabilityService) {}

    public function execute(Appointment $appointment, int $userId): Appointment
    {
        if ($appointment->status !== Appointment::STATUS_PENDING) {
            throw new Exception("Seul un rendez-vous en attente peut être confirmé.");
        }

        if (!$this->availabilityService->isAvailable($appointment->doctor_id, $appointment->date_time, $appointment->duration, $appointment->id)) {
            throw new Exception("Ce créneau est désormais occupé par un autre rendez-vous.");
        }

        $appointment->update([
            'status' => Appointment::STATUS_CONFIRMED,
            'confirmed_by' => $userId,
            'confirmed_at' => now(),
        ]);

        return $appointment;
    }
}
