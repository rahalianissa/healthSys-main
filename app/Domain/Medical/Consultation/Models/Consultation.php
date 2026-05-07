<?php

namespace App\Domain\Medical\Consultation\Models;

use App\Domain\Appointment\Models\Appointment;
use App\Domain\User\Models\Doctor;
use App\Domain\User\Models\Patient;
use App\Domain\Medical\Prescription\Models\Prescription;
use App\Domain\Billing\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id', 'consultation_date',
        'symptoms', 'diagnosis', 'treatment', 'weight', 'height',
        'blood_pressure', 'temperature', 'heart_rate', 'notes'
    ];

    protected $casts = ['consultation_date' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
