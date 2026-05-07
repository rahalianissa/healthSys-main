<?php

namespace App\Domain\Medical\Prescription\Models;

use App\Domain\User\Models\Doctor;
use App\Domain\User\Models\Patient;
use App\Domain\Medical\Consultation\Models\Consultation;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id', 'doctor_id', 'medications',
        'instructions', 'prescription_date', 'valid_until', 'status'
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'valid_until' => 'date',
        'medications' => 'array',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function consultation() { return $this->belongsTo(Consultation::class); }
}
