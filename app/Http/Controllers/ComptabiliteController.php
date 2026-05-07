<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\WaitingRoom;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ComptabiliteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:secretaire,chef_medecine');
    }

    public function index()
    {
        // Real statistics
        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('date_time', today())->count(),
            'waiting_patients' => WaitingRoom::where('status', 'waiting')->count(),
            'monthly_revenue' => Payment::whereMonth('payment_date', now()->month)
                                      ->whereYear('payment_date', now()->year)
                                      ->sum('amount'),
            'pending_payment' => Invoice::where('status', '!=', 'paid')->get()->sum(function($invoice) {
                return $invoice->amount - $invoice->paid_amount;
            }),
        ];
        
        // Data for charts (Last 12 months)
        $monthly_revenue_data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            
            $monthly_revenue_data[] = Payment::whereMonth('payment_date', $month)
                                           ->whereYear('payment_date', $year)
                                           ->sum('amount');
            
            $labels[] = $date->translatedFormat('M');
        }

        // Payment methods breakdown
        $payment_methods = Payment::select('payment_method', \DB::raw('count(*) as count'))
                                   ->groupBy('payment_method')
                                   ->get()
                                   ->pluck('count', 'payment_method')
                                   ->toArray();
        
        $total_payments = array_sum($payment_methods);
        $methods_stats = [
            'cash' => $total_payments > 0 ? round(($payment_methods['cash'] ?? 0) / $total_payments * 100) : 0,
            'card' => $total_payments > 0 ? round(($payment_methods['card'] ?? 0) / $total_payments * 100) : 0,
            'check' => $total_payments > 0 ? round(($payment_methods['check'] ?? 0) / $total_payments * 100) : 0,
            'transfer' => $total_payments > 0 ? round(($payment_methods['transfer'] ?? 0) / $total_payments * 100) : 0,
        ];
        
        // Latest invoices (limit to 5 for dashboard)
        $invoices = Invoice::with('patient.user', 'consultation.doctor.user')
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();
        
        return view('secretaire.comptabilite', compact('stats', 'monthly_revenue_data', 'labels', 'methods_stats', 'invoices'));
    }

    // Méthode pour afficher toutes les factures
    public function allInvoices()
    {
        return redirect()->route('invoices.index');
    }

    // Méthode pour afficher les paiements
    public function paiements()
    {
        $payments = Payment::with('invoice.patient.user')->latest()->paginate(20);
        return view('comptabilite.paiements', compact('payments'));
    }

    public function createFacture()
    {
        return redirect()->route('invoices.create');
    }
}