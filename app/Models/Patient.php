<?php
// app/Models/Patient.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'user_id', 'insurance_number', 'insurance_company', 'emergency_contact',
        'emergency_phone', 'allergies', 'medical_history', 'blood_type', 'weight', 'height',
        // NEW FIELDS
        'has_cnam', 'cnam_number', 'cnam_expiry_date',
        'has_mutuelle', 'mutuelle_number', 'mutuelle_company', 'mutuelle_rate', 'mutuelle_expiry_date'
    ];

    protected $casts = [
        'has_cnam' => 'boolean',
        'has_mutuelle' => 'boolean',
        'cnam_expiry_date' => 'date',
        'mutuelle_expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function waitingRooms()
    {
        return $this->hasMany(WaitingRoom::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getAgeAttribute()
    {
        return $this->user->birth_date ? Carbon::parse($this->user->birth_date)->age : null;
    }

    /**
     * Check if patient has valid CNAM coverage
     */
    public function hasValidCnam(): bool
    {
        if (!$this->has_cnam || empty($this->cnam_number)) {
            return false;
        }
        
        if ($this->cnam_expiry_date) {
            return $this->cnam_expiry_date->isFuture();
        }
        
        return true;
    }

    /**
     * Check if patient has valid Mutuelle coverage
     */
    public function hasValidMutuelle(): bool
    {
        if (!$this->has_mutuelle || empty($this->mutuelle_number)) {
            return false;
        }
        
        if ($this->mutuelle_expiry_date) {
            return $this->mutuelle_expiry_date->isFuture();
        }
        
        return true;
    }

    /**
     * Get mutuelle coverage rate (percentage)
     */
    public function getMutuelleRate(): float
    {
        return $this->mutuelle_rate ?? 0;
    }

    /**
     * Calculate insurance breakdown for an invoice
     * Priority: CNAM → Mutuelle → Patient
     */
    public function calculateInsuranceBreakdown(float $total, ?float $customCnamAmount = null): array
    {
        // Default CNAM rate is 70% (Tunisia standard)
        $cnamRate = 0.70;
        
        // Step 1: Calculate CNAM amount
        $cnamAmount = 0;
        if ($this->hasValidCnam()) {
            $cnamAmount = $customCnamAmount ?? ($total * $cnamRate);
            $cnamAmount = min($cnamAmount, $total); // Can't exceed total
        }
        
        $remainingAfterCnam = $total - $cnamAmount;
        
        // Step 2: Calculate Mutuelle amount (based on remaining)
        $mutuelleAmount = 0;
        if ($this->hasValidMutuelle() && $remainingAfterCnam > 0) {
            $mutuelleRate = $this->getMutuelleRate() / 100;
            $mutuelleAmount = $remainingAfterCnam * $mutuelleRate;
            $mutuelleAmount = min($mutuelleAmount, $remainingAfterCnam);
        }
        
        $remainingAfterMutuelle = $remainingAfterCnam - $mutuelleAmount;
        
        // Step 3: Patient pays the rest
        $patientAmount = max(0, $remainingAfterMutuelle);
        
        return [
            'total' => round($total, 2),
            'cnam' => round($cnamAmount, 2),
            'mutuelle' => round($mutuelleAmount, 2),
            'patient' => round($patientAmount, 2),
            'cnam_rate' => $cnamRate * 100,
            'mutuelle_rate' => $this->getMutuelleRate(),
        ];
    }

    /**
     * Get insurance info for display
     */
    public function getInsuranceInfoAttribute(): array
    {
        return [
            'cnam' => [
                'active' => $this->hasValidCnam(),
                'number' => $this->cnam_number,
                'expiry' => $this->cnam_expiry_date?->format('d/m/Y'),
            ],
            'mutuelle' => [
                'active' => $this->hasValidMutuelle(),
                'company' => $this->mutuelle_company,
                'number' => $this->mutuelle_number,
                'rate' => $this->mutuelle_rate,
                'expiry' => $this->mutuelle_expiry_date?->format('d/m/Y'),
            ],
        ];
    }
}