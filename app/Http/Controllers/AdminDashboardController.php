<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:chef_medecine']);
    }

    public function index()
    {
        // Core Stats
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $stats = [
            'doctors_count' => User::where('role', 'doctor')->count(),
            'secretaries_count' => User::where('role', 'secretaire')->count(),
            'patients_count' => Patient::count(),
            'appointments_count' => Appointment::count(),
            'new_patients_month' => Patient::whereMonth('created_at', now()->month)->count(), // ✅ AJOUTÉ
            'patients_growth' => $this->getGrowth(
                Patient::where('created_at', '>=', $thisMonth)->count(),
                Patient::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count()
            ),
            'total_revenue' => Invoice::sum('amount') ?? 0,
            'revenue_growth' => $this->getGrowth(
                Invoice::where('created_at', '>=', $thisMonth)->sum('amount'),
                Invoice::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->sum('amount')
            ),
            'today_appointments' => Appointment::whereDate('date_time', Carbon::today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_consultations' => Consultation::count(),
            'paid_revenue' => Invoice::sum('paid_amount') ?? 0,
        ];

        // Monthly Data (Last 12 months)
        $months = collect(range(0, 11))->map(function($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        $monthlyAppointments = $months->map(function($month) {
            return Appointment::where('date_time', 'like', $month . '%')->count();
        });

        $monthlyRevenue = $months->map(function($month) {
            return Invoice::where('created_at', 'like', $month . '%')->sum('amount');
        });

        // Recent Activity
        $recentDoctors = User::where('role', 'doctor')
            ->with('specialite')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPatients = Patient::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $chartLabels = $months->map(fn($m) => Carbon::parse($m)->translatedFormat('M'))->values();

        return view('admin.dashboard', compact(
            'stats', 
            'recentDoctors', 
            'recentPatients', 
            'recentAppointments',
            'chartLabels',
            'monthlyAppointments',
            'monthlyRevenue'
        ));
    }

    private function getGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
}