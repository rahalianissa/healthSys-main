<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:doctor,chef_medecine']);
    }

    public function index()
    {
        $user = auth()->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin introuvable.');
        }

        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Core Stats
        $stats = [
            'appointments_count' => Appointment::where('doctor_id', $doctor->id)->count(),
            'today_appointments' => Appointment::where('doctor_id', $doctor->id)->whereDate('date_time', Carbon::today())->count(),
            'patients_count' => Appointment::where('doctor_id', $doctor->id)->select('patient_id')->distinct()->count(),
            'consultations_count' => Consultation::where('doctor_id', $doctor->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'appointments_growth' => $this->getGrowth(
                Appointment::where('doctor_id', $doctor->id)->where('created_at', '>=', $thisMonth)->count(),
                Appointment::where('doctor_id', $doctor->id)->where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count()
            ),
        ];

        // Monthly Data (Last 12 months)
        $months = collect(range(0, 11))->map(function($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        $monthlyAppointments = $months->map(function($month) use ($doctor) {
            return Appointment::where('doctor_id', $doctor->id)->where('date_time', 'like', $month . '%')->count();
        });

        $monthlyConsultations = $months->map(function($month) use ($doctor) {
            return Consultation::where('doctor_id', $doctor->id)->where('consultation_date', 'like', $month . '%')->count();
        });

        // Recent Activity
        $recentAppointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('date_time', 'desc')
            ->limit(5)
            ->get();

        $chartLabels = $months->map(fn($m) => Carbon::parse($m)->translatedFormat('M'))->values();

        return view('doctor.dashboard', compact(
            'stats', 
            'recentAppointments',
            'chartLabels',
            'monthlyAppointments',
            'monthlyConsultations'
        ));
    }

    private function getGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
    
    
}
