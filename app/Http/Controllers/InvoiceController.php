<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================= ADMIN / SECRETAIRE =================

    public function index(Request $request)
    {
        // Si c'est un patient, le rediriger vers sa propre liste
        if (auth()->user()->role === 'patient') {
            return redirect()->route('patient.invoices');
        }

        $query = Invoice::with('patient.user')->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient.user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $invoices = $query->paginate(15);
        
        if ($request->wantsJson()) {
            return response()->json($invoices);
        }
        
        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $this->authorizeAccess();
        $patients = Patient::with('user')->get();
        
        $prefilled = [
            'patient_id' => $request->get('patient_id'),
            'consultation_id' => $request->get('consultation_id'),
            'amount' => $request->get('amount'),
        ];
        
        return view('invoices.create', compact('patients', 'prefilled'));
    }

    /**
     * STORE INVOICE - VERSION CORRIGÉE
     */
    public function store(Request $request)
    {
        Log::info('STORE INVOICE START', $request->all());
        
        try {
            $this->authorizeAccess();
        } catch (\Exception $e) {
            Log::error('AUTHORIZATION FAILED: ' . $e->getMessage());
            return abort(403, $e->getMessage());
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        if ($validator->fails()) {
            Log::warning('INVOICE VALIDATION FAILED', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);
            return back()->withErrors($validator)->withInput()->with('error', '❌ Veuillez corriger les erreurs dans le formulaire.');
        }
        
        try {
            DB::beginTransaction();
            
            $patient = Patient::with('user')->findOrFail($request->patient_id);
            Log::info('Patient found for invoice', ['id' => $patient->id]);

            // Calculer le breakdown des assurances
            $breakdown = $patient->calculateInsuranceBreakdown($request->amount);
            Log::info('Insurance breakdown calculated', $breakdown);

            // Générer un numéro de facture unique
            // On utilise une approche plus robuste
            $today = date('Ymd');
            $countToday = Invoice::whereDate('created_at', today())->count();
            $invoiceNumber = 'INV-' . $today . '-' . str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            
            // Vérifier si ce numéro existe déjà (sécurité)
            while (Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                $countToday++;
                $invoiceNumber = 'INV-' . $today . '-' . str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            }

            // Créer la facture
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'patient_id' => $request->patient_id,
                'consultation_id' => $request->consultation_id,
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
            
            DB::commit();
            Log::info('Invoice created successfully', ['id' => $invoice->id, 'number' => $invoiceNumber]);
            
            return redirect()->route('invoices.show', $invoice)
                ->with('success', '✅ Facture créée avec succès !');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('INVOICE CREATION FATAL ERROR: ' . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);
            
            return back()->withInput()->with('error', '❌ Erreur lors de la création de la facture: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        // Sécurité : Un patient ne peut voir que ses propres factures
        if (auth()->user()->role === 'patient') {
            if ($invoice->patient_id !== auth()->user()->patient?->id) {
                abort(403, 'Vous n\'êtes pas autorisé à voir cette facture.');
            }
        }

        $invoice->load('patient.user', 'payments');

        if (request()->wantsJson()) {
            return response()->json($invoice);
        }

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorizeAccess();
        $patients = Patient::with('user')->get();
        return view('invoices.edit', compact('invoice', 'patients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeAccess();
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        try {
            DB::beginTransaction();

            // Recalculer le breakdown si le patient ou le montant a changé
            if ($request->amount != $invoice->amount || $request->patient_id != $invoice->patient_id) {
                $patient = Patient::findOrFail($request->patient_id);
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
            
            $invoice->syncPaidAmount();
            
            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', '✅ Facture modifiée avec succès');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur modification facture: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la modification');
        }
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeAccess();
        
        if ($invoice->payments()->exists()) {
            return back()->with('error', '❌ Impossible de supprimer une facture avec des paiements');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', '✅ Facture supprimée avec succès');
    }

    // ================= PAYMENTS =================

    public function paymentPage(Invoice $invoice, Request $request)
    {
        // Sécurité : Un patient ne peut payer que ses propres factures
        if (auth()->user()->role === 'patient') {
            if ($invoice->patient_id !== auth()->user()->patient?->id) {
                abort(403, 'Vous n\'êtes pas autorisé à payer cette facture.');
            }
        }

        $invoice->load('patient.user', 'payments');
        
        // Récupérer le type depuis l'URL (default: patient)
        $type = $request->get('type', 'patient');
        
        // Calculer les montants restants basés sur les paiements réels
        $paidCnam = $invoice->getPaidByType('cnam');
        $paidMutuelle = $invoice->getPaidByType('mutuelle');
        $paidPatient = $invoice->getPaidByType('patient');

        $remainingCnam = max(0, $invoice->cnam_amount - $paidCnam);
        $remainingMutuelle = max(0, $invoice->mutuelle_amount - $paidMutuelle);
        $remainingPatient = max(0, $invoice->patient_amount - $paidPatient);
        
        // Fallback pour les anciennes factures sans breakdown
        if ($invoice->patient_amount <= 0 && $invoice->cnam_amount <= 0 && $invoice->mutuelle_amount <= 0) {
            $remainingPatient = max(0, $invoice->amount - $invoice->paid_amount);
        }
        
        $totalRemaining = round($remainingCnam + $remainingMutuelle + $remainingPatient, 2);
        
        // Déterminer le montant maximum selon le type
        $maxAmount = 0;
        $paymentTypeLabel = '';
        switch ($type) {
            case 'cnam':
                $maxAmount = $remainingCnam;
                $paymentTypeLabel = 'CNAM';
                break;
            case 'mutuelle':
                $maxAmount = $remainingMutuelle;
                $paymentTypeLabel = 'Mutuelle';
                break;
            case 'patient':
                $maxAmount = $remainingPatient;
                $paymentTypeLabel = 'Patient';
                break;
            default:
                $maxAmount = $remainingPatient;
                $paymentTypeLabel = 'Patient';
                $type = 'patient';
        }
        
        // Si le montant maximum est 0, rediriger
        if ($maxAmount <= 0) {
            $routeName = auth()->user()->role === 'patient' ? 'patient.invoices.show' : 'invoices.show';
            return redirect()->route($routeName, $invoice)
                ->with('warning', '⚠️ Cette facture est déjà entièrement payée pour cette partie.');
        }
        
        return view('invoices.pay', compact('invoice', 'type', 'maxAmount', 'paymentTypeLabel', 
                                            'remainingCnam', 'remainingMutuelle', 'remainingPatient', 'totalRemaining'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        // Sécurité : Un patient ne peut payer que ses propres factures
        if (auth()->user()->role === 'patient') {
            if ($invoice->patient_id !== auth()->user()->patient?->id) {
                abort(403, 'Opération non autorisée.');
            }
            // Forcer le type à 'patient' si c'est un patient
            $request->merge(['payment_type' => 'patient']);
        }

        $request->validate([
            'payment_type' => 'required|in:cnam,mutuelle,patient',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,check,transfer',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Calculer le montant maximum autorisé
        $maxAmount = 0;
        switch ($request->payment_type) {
            case 'cnam':
                $maxAmount = max(0, $invoice->cnam_amount - $invoice->getPaidByType('cnam'));
                break;
            case 'mutuelle':
                $maxAmount = max(0, $invoice->mutuelle_amount - $invoice->getPaidByType('mutuelle'));
                break;
            case 'patient':
                $maxAmount = max(0, $invoice->patient_amount - $invoice->getPaidByType('patient'));
                // Fallback pour les anciennes factures
                if ($invoice->patient_amount <= 0 && $invoice->cnam_amount <= 0 && $invoice->mutuelle_amount <= 0) {
                    $maxAmount = max(0, $invoice->amount - $invoice->paid_amount);
                }
                break;
        }
        
        if (round($request->amount, 2) > round($maxAmount, 2)) {
            return back()->with('error', "❌ Le montant ne peut pas dépasser " . number_format($maxAmount, 2) . " DT");
        }

        try {
            DB::transaction(function () use ($request, $invoice) {
                // Créer le paiement avec le type
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $request->amount,
                    'payment_type' => $request->payment_type,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now(),
                    'notes' => $request->notes . ($request->reference_number ? " | Réf: " . $request->reference_number : ""),
                    'transaction_id' => $request->reference_number,
                    'status' => 'completed',
                ]);
                
                // Mettre à jour les indicateurs de la facture si le bucket est maintenant plein
                // On recharge les relations pour inclure le nouveau paiement dans le calcul
                $invoice->load('payments');
                
                if ($invoice->isBucketPaid('cnam')) {
                    $invoice->cnam_paid = true;
                    if ($request->payment_type === 'cnam' && $request->reference_number) {
                        $invoice->cnam_reference = $request->reference_number;
                    }
                    if (!$invoice->cnam_claim_date) $invoice->cnam_claim_date = now();
                }
                
                if ($invoice->isBucketPaid('mutuelle')) {
                    $invoice->mutuelle_paid = true;
                    if ($request->payment_type === 'mutuelle' && $request->reference_number) {
                        $invoice->mutuelle_reference = $request->reference_number;
                    }
                    if (!$invoice->mutuelle_claim_date) $invoice->mutuelle_claim_date = now();
                }
                
                if ($invoice->isBucketPaid('patient')) {
                    $invoice->patient_paid = true;
                }
                
                // Mettre à jour le montant payé total et le statut global
                $invoice->paid_amount = $invoice->calculateTotalPaid();
                $invoice->updateOverallStatus();
                $invoice->save();
            });
            
            $routeName = auth()->user()->role === 'patient' ? 'patient.invoices.show' : 'invoices.show';
            return redirect()->route($routeName, $invoice)
                ->with('success', '✅ Paiement de ' . number_format($request->amount, 2) . ' DT effectué avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur paiement: ' . $e->getMessage());
            return back()->with('error', '❌ Erreur lors du traitement du paiement: ' . $e->getMessage());
        }
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
        // Sécurité : Un patient ne peut imprimer que ses propres factures
        if (auth()->user()->role === 'patient') {
            if ($invoice->patient_id !== auth()->user()->patient?->id) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $invoice->load(['patient.user', 'payments']);
        return view('invoices.print', compact('invoice'));
    }
    
    public function pdfInvoice(Invoice $invoice)
    {
        // Sécurité : Un patient ne peut télécharger que ses propres factures
        if (auth()->user()->role === 'patient') {
            if ($invoice->patient_id !== auth()->user()->patient?->id) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $invoice->load(['patient.user', 'payments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download('facture_' . $invoice->invoice_number . '.pdf');
    }
    
    // ================= INSURANCE CLAIMS =================
    
    public function cnamClaims()
    {
        $this->authorizeAccess();
        
        $claims = Invoice::where('cnam_amount', '>', 0)
            ->where('cnam_paid', false)
            ->with('patient.user')
            ->latest()
            ->get();
            
        return view('invoices.cnam-claims', compact('claims'));
    }
    
    public function mutuelleClaims()
    {
        $this->authorizeAccess();
        
        $claims = Invoice::where('mutuelle_amount', '>', 0)
            ->where('mutuelle_paid', false)
            ->with('patient.user')
            ->latest()
            ->get();
            
        return view('invoices.mutuelle-claims', compact('claims'));
    }
    
    // ================= METHODES PRIVEES =================
    
    private function authorizeAccess()
    {
        if (!in_array(auth()->user()->role, ['chef_medecine', 'secretaire', 'doctor'])) {
            abort(403, 'Accès non autorisé - Seuls les médecins, chefs de médecine et secrétaires peuvent accéder.');
        }
    }
}