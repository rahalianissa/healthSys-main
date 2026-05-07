<?php

namespace App\Domain\Appointment\DTO;

use Carbon\Carbon;

class AppointmentData
{
    public function __construct(
        public int $patient_id,
        public int $doctor_id,
        public Carbon $date_time,
        public int $duration = 30,
        public ?string $type = 'general',
        public ?string $reason = null,
        public ?string $notes = null,
        public ?int $created_by = null
    ) {}

    public static function fromRequest(array $validated, int $userId): self
    {
        return new self(
            patient_id: $validated['patient_id'],
            doctor_id: $validated['doctor_id'],
            date_time: Carbon::parse($validated['date_time']),
            duration: $validated['duration'] ?? 30,
            type: $validated['type'] ?? 'general',
            reason: $validated['reason'] ?? null,
            notes: $validated['notes'] ?? null,
            created_by: $userId
        );
    }
}
