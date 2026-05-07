<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SecretaryDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:secretaire,chef_medecine']);
    }

    public function index()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Core Stats
        $stats = [
            'today_appointments' => Appointment::whereDate('date_time', Carbon::today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'new_patients_today' => Patient::whereDate('created_at', Carbon::today())->count(),
            'waiting_room_count' => WaitingRoom::where('status', 'waiting')->count(),
            'today_revenue' => Invoice::whereDate('created_at', Carbon::today())->sum('amount'),
            'appointments_growth' => $this->getGrowth(
                Appointment::where('created_at', '>=', $thisMonth)->count(),
                Appointment::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count()
            ),
            'patients_growth' => $this->getGrowth(
                Patient::where('created_at', '>=', $thisMonth)->count(),
                Patient::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count()
            ),
        ];

        // ✅ AJOUT : Rendez-vous du jour
        $todayAppointments = Appointment::whereDate('date_time', Carbon::today())
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('date_time', 'asc')
            ->get();

        // ✅ AJOUT : Patients en salle d'attente
        $waitingPatients = WaitingRoom::where('status', 'waiting')
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('priority', 'desc')
            ->orderBy('arrival_time', 'asc')
            ->get();

        // Monthly Data
        $months = collect(range(0, 11))->map(function($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        $monthlyAppointments = $months->map(function($month) {
            return Appointment::where('date_time', 'like', $month . '%')->count();
        });

        $monthlyInvoices = $months->map(function($month) {
            return Invoice::where('created_at', 'like', $month . '%')->count();
        });

        $chartLabels = $months->map(fn($m) => Carbon::parse($m)->translatedFormat('M'))->values();

        return view('secretaire.dashboard', compact(
            'stats', 
            'todayAppointments',
            'waitingPatients',
            'chartLabels',
            'monthlyAppointments',
            'monthlyInvoices'
        ));
    }

    private function getGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
}