<?php

namespace App\Domain\User\Models;

use App\Domain\Appointment\Models\Appointment;
use App\Domain\Medical\Consultation\Models\Consultation;
use App\Domain\Medical\Prescription\Models\Prescription;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id', 'specialty', 'registration_number', 'consultation_fee',
        'diploma', 'cabinet_phone', 'schedule'
    ];

    protected $casts = ['schedule' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function consultations() { return $this->hasMany(Consultation::class); }
}
