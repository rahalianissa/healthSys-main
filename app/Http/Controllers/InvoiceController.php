<?php

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

        $last = Invoice::latest()->first();
        $nextId = $last ? $last->id + 1 : 1;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Invoice::create([
            'invoice_number' => $invoiceNumber,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'paid_amount' => 0,
            'status' => 'pending',
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('patient.user', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Afficher le formulaire de modification (accessible à la secrétaire et admin)
     */
    public function edit(Invoice $invoice)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $patients = Patient::with('user')->get();
        return view('invoices.edit', compact('invoice', 'patients'));
    }

    /**
     * Mettre à jour la facture (accessible à la secrétaire et admin)
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        // Recalculer le statut en fonction du montant payé
        $paid = $invoice->paid_amount;
        $amount = $request->amount;

        if ($paid == 0) {
            $status = 'pending';
        } elseif ($paid >= $amount) {
            $status = 'paid';
        } else {
            $status = 'partially_paid';
        }

        $invoice->update([
            'patient_id' => $request->patient_id,
            'amount' => $amount,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => $request->status ?? $status,
            'description' => $request->description,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture modifiée avec succès');
    }

    public function destroy(Invoice $invoice)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire'])) {
            abort(403, 'Accès non autorisé');
        }
        
        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Impossible de supprimer une facture avec des paiements');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Facture supprimée avec succès');
    }

    // ================= PAIEMENTS MANUELS =================

    public function paymentPage(Invoice $invoice)
    {
        return view('invoices.pay', compact('invoice'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $remaining = $invoice->amount - $invoice->paid_amount;

        $request->validate([
            'amount' => "required|numeric|min:0.01|max:$remaining",
            'payment_method' => 'required|in:cash,card,check,transfer',
        ]);

        // Validation supplémentaire pour le paiement par carte
        if ($request->payment_method == 'card') {
            $request->validate([
                'card_number' => 'required|string',
                'exp_month' => 'required|string',
                'exp_year' => 'required|string',
                'cvv' => 'required|string',
                'card_holder' => 'required|string',
            ]);
        }

        DB::transaction(function () use ($request, $invoice) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'notes' => $request->notes . ($request->payment_method == 'card' ? ' | Carte: ' . $request->card_holder : ''),
                'status' => 'completed',
            ]);

            $newPaid = $invoice->paid_amount + $request->amount;

            if ($newPaid >= $invoice->amount) {
                $status = 'paid';
            } elseif ($newPaid == 0) {
                $status = 'pending';
            } else {
                $status = 'partially_paid';
            }

            $invoice->update([
                'paid_amount' => $newPaid,
                'status' => $status,
            ]);
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
    
    // ================= IMPRESSION =================
    
    /**
     * Afficher la version imprimable de la facture
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['patient.user', 'payments']);
        return view('invoices.print', compact('invoice'));
    }
    
    /**
     * Générer un PDF de la facture (via DomPDF)
     */
    public function pdfInvoice(Invoice $invoice)
    {
        $invoice->load(['patient.user', 'payments']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('facture_' . $invoice->invoice_number . '.pdf');
    }
}