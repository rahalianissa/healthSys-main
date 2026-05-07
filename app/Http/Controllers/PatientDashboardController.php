<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function index()
    {
        $user = auth()->user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient introuvable.');
        }

        // Core Stats
        $stats = [
            'appointments_count' => Appointment::where('patient_id', $patient->id)->count(),
            'prescriptions_count' => Prescription::where('patient_id', $patient->id)->count(),
            'invoices_count' => Invoice::where('patient_id', $patient->id)->count(),
            'unpaid_invoices' => Invoice::where('patient_id', $patient->id)->where('status', 'pending')->count(),
            'next_appointment' => Appointment::where('patient_id', $patient->id)
                ->where('date_time', '>', now())
                ->where('status', 'confirmed')
                ->orderBy('date_time', 'asc')
                ->first(),
        ];

        // Recent Activity
        $recentAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('date_time', 'desc')
            ->limit(5)
            ->get();

        $recentPrescriptions = Prescription::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('patient.dashboard', compact(
            'stats', 
            'recentAppointments',
            'recentPrescriptions'
        ));
    }
}
