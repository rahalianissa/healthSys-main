<?php

namespace App\Domain\User\Models;

use App\Domain\Appointment\Models\Appointment;
use App\Domain\Medical\Consultation\Models\Consultation;
use App\Domain\Medical\Prescription\Models\Prescription;
use App\Domain\Billing\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id', 'insurance_number', 'insurance_company', 'emergency_contact',
        'emergency_phone', 'allergies', 'medical_history', 'blood_type', 'weight', 'height',
        'has_cnam', 'cnam_number', 'cnam_expiry_date',
        'has_mutuelle', 'mutuelle_number', 'mutuelle_company', 'mutuelle_rate', 'mutuelle_expiry_date'
    ];

    protected $casts = [
        'has_cnam' => 'boolean',
        'has_mutuelle' => 'boolean',
        'cnam_expiry_date' => 'date',
        'mutuelle_expiry_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function consultations() { return $this->hasMany(Consultation::class); }
    public function prescriptions() { return $this->hasMany(Prescription::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}
