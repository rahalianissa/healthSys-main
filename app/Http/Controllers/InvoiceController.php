<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================= ADMIN / SECRETAIRE =================

    public function index()
    {
        $invoices = Invoice::with('patient.user')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        return view('invoices.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        $patient = Patient::with('user')->find($request->patient_id);
        
        // Calculate insurance breakdown
        $breakdown = $patient->calculateInsuranceBreakdown($request->amount);
        
        $last = Invoice::latest()->first();
        $nextId = $last ? $last->id + 1 : 1;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'cnam_amount' => $breakdown['cnam'],
            'mutuelle_amount' => $breakdown['mutuelle'],
            'patient_amount' => $breakdown['patient'],
            'paid_amount' => 0,
            'status' => 'pending',
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'cnam_paid' => false,
            'mutuelle_paid' => false,
            'patient_paid' => false,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('patient.user', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $patients = Patient::with('user')->get();
        return view('invoices.edit', compact('invoice', 'patients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        // Recalculate insurance breakdown if amount changed
        if ($request->amount != $invoice->amount) {
            $patient = Patient::find($request->patient_id);
            $breakdown = $patient->calculateInsuranceBreakdown($request->amount);
            
            $invoice->cnam_amount = $breakdown['cnam'];
            $invoice->mutuelle_amount = $breakdown['mutuelle'];
            $invoice->patient_amount = $breakdown['patient'];
        }

        $invoice->update([
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture modifiée avec succès');
    }

    public function destroy(Invoice $invoice)
    {
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Impossible de supprimer une facture avec des paiements');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Facture supprimée avec succès');
    }

    // ================= PAYMENTS =================

    public function paymentPage(Invoice $invoice)
    {
        return view('invoices.pay', compact('invoice'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $remainingBreakdown = $invoice->remaining_breakdown;
        
        $request->validate([
            'payment_type' => 'required|in:cnam,mutuelle,patient',
            'amount' => "required|numeric|min:0.01|max:{$remainingBreakdown[$request->payment_type]}",
            'payment_method' => 'required_if:payment_type,patient|in:cash,card,check,transfer',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // Create payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method ?? 'transfer',
                'payment_date' => now(),
                'notes' => $request->notes . " | Paiement par: " . strtoupper($request->payment_type),
                'transaction_id' => $request->transaction_id,
                'status' => 'completed',
            ]);

            // Mark appropriate entity as paid
            switch ($request->payment_type) {
                case 'cnam':
                    $invoice->markCnamPaid($request->reference_number);
                    break;
                case 'mutuelle':
                    $invoice->markMutuellePaid($request->reference_number);
                    break;
                case 'patient':
                    $invoice->markPatientPaid();
                    break;
            }
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', '✅ Paiement effectué avec succès');
    }

    // ================= PATIENT =================

    public function patientInvoices()
    {
        $patient = auth()->user()->patient;
        
        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé');
        }
        
        $invoices = Invoice::where('patient_id', $patient->id)->latest()->get();
        return view('patient.invoices', compact('invoices'));
    }
    
    // ================= PRINT & PDF =================
    
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['patient.user', 'payments']);
        return view('invoices.print', compact('invoice'));
    }
    
    public function pdfInvoice(Invoice $invoice)
    {
        $invoice->load(['patient.user', 'payments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('facture_' . $invoice->invoice_number . '.pdf');
    }
    
    // ================= INSURANCE CLAIMS =================
    
    public function cnamClaims()
    {
        $claims = Invoice::where('cnam_amount', '>', 0)
            ->where('cnam_paid', false)
            ->with('patient.user')
            ->get();
        return view('invoices.cnam-claims', compact('claims'));
    }
    
    public function mutuelleClaims()
    {
        $claims = Invoice::where('mutuelle_amount', '>', 0)
            ->where('mutuelle_paid', false)
            ->with('patient.user')
            ->get();
        return view('invoices.mutuelle-claims', compact('claims'));
    }
}