<?php

namespace App\Domain\Appointment\Services;

use App\Domain\Appointment\Models\Appointment;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Strict overlap detection with buffer support
     */
    public function isAvailable(int $doctorId, Carbon $start, int $duration, ?int $excludeId = null): bool
    {
        $end = $start->copy()->addMinutes($duration);

        return !Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('date_time', '<', $end)
                      ->whereRaw('DATE_ADD(date_time, INTERVAL duration MINUTE) > ?', [$start->toDateTimeString()]);
                });
            })
            ->exists();
    }
}
