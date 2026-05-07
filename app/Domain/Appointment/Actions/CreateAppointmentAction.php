<?php

namespace App\Domain\Appointment\Actions;

use App\Domain\Appointment\DTO\AppointmentData;
use App\Domain\Appointment\Models\Appointment;
use App\Domain\Appointment\Services\AvailabilityService;
use Exception;

class CreateAppointmentAction
{
    public function __construct(private AvailabilityService $availabilityService) {}

    public function execute(AppointmentData $data): Appointment
    {
        if (!$this->availabilityService.isAvailable($data->doctor_id, $data->date_time, $data->duration)) {
            throw new Exception("Ce créneau horaire n'est pas disponible.");
        }

        return Appointment::create([
            'patient_id' => $data->patient_id,
            'doctor_id'  => $data->doctor_id,
            'date_time'  => $data->date_time,
            'duration'   => $data->duration,
            'type'       => $data->type,
            'reason'     => $data->reason,
            'notes'      => $data->notes,
            'created_by' => $data->created_by,
            'status'     => Appointment::STATUS_PENDING,
        ]);
    }
}
