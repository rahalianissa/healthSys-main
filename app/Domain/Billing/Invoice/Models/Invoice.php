<?php

namespace App\Domain\Billing\Invoice\Models;

use App\Domain\User\Models\Patient;
use App\Domain\Medical\Consultation\Models\Consultation;
use App\Domain\Billing\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id', 'consultation_id', 'invoice_number', 'total_amount',
        'cnam_amount', 'mutuelle_amount', 'patient_amount', 'status'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function consultation() { return $this->belongsTo(Consultation::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
