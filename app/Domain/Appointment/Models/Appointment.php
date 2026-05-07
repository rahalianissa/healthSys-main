<?php

namespace App\Domain\Appointment\Models;

use App\Domain\User\Models\Doctor;
use App\Domain\User\Models\Patient;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

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
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
