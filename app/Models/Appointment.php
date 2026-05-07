<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\SystemNotification;
use Carbon\Carbon;

class Appointment extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'patient_id', 'doctor_id', 'created_by', 'date_time', 'duration',
        'status', 'type', 'reason', 'notes', 'reminder_sent',
        'confirmed_by', 'confirmed_at', 'cancelled_by', 'cancelled_at', 'cancellation_reason'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'reminder_sent' => 'boolean',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function hasOverlap($doctorId, $dateTime, $duration = 30, $excludeId = null)
    {
        $start = Carbon::parse($dateTime);
        $end = $start->copy()->addMinutes($duration);
        
        return self::where('doctor_id', $doctorId)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->where('date_time', '<', $end)
                      ->whereRaw('DATE_ADD(date_time, INTERVAL duration MINUTE) > ?', [$start->toDateTimeString()]);
                });
            })
            ->when($excludeId, function($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->exists();
    }

    /**
     * Vérifier si un créneau horaire est disponible
     */
    public static function isTimeSlotAvailable($doctorId, $dateTime, $excludeId = null)
    {
        $start = Carbon::parse($dateTime);
        $end = $start->copy()->addMinutes(30);
        
        $query = self::where('doctor_id', $doctorId)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('date_time', [$start, $end])
                  ->orWhereRaw('DATE_ADD(date_time, INTERVAL duration MINUTE) > ?', [$start]);
            });
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }
}