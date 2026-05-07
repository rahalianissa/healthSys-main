@extends('layouts.app')

@section('page_title', 'Détails de la facture')
@section('page_subtitle', 'Facture #' . $invoice->invoice_number)

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-soft: #48CAE4;
        --primary-bg: #CAF0F8;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
    }

    .invoice-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .invoice-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 8px;
    }
    
    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .insurance-bar {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .insurance-progress {
        height: 32px;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        margin-top: 12px;
    }
    
    .progress-cnam {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
    }
    
    .progress-mutuelle {
        background: linear-gradient(135deg, #10B981, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
    }
    
    .progress-patient {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
    }
    
    .payment-item {
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px;
        transition: all 0.2s;
    }
    
    .payment-item:hover {
        background: #f1f5f9;
    }
    
    .status-paid {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-pending {
        background: #fffbeb;
        color: #d97706;
    }
    
    .status-partial {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .action-btn {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .payment-btn-cnam {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .payment-btn-mutuelle {
        background: #ecfdf5;
        color: #059669;
    }
    
    .payment-btn-patient {
        background: #fffbeb;
        color: #d97706;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.5s ease forwards;
    }
</style>

<!-- En-tête de la facture -->
<div class="invoice-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-file-invoice-dollar text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">FACTURE</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Facture #{{ $invoice->invoice_number }}</h1>
            <p class="text-white/60 text-sm">Générée le {{ $invoice->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="bg-white/10 rounded-2xl px-5 py-3 text-center backdrop-blur-sm">
            <div class="text-white/70 text-xs mb-1">Statut</div>
            <div class="text-white font-bold text-lg">
                @if($invoice->status == 'paid')
                    <span><i class="fas fa-check-circle mr-1"></i> Payée</span>
                @elseif($invoice->status == 'partially_paid')
                    <span><i class="fas fa-clock mr-1"></i> Partiellement payée</span>
                @else
                    <span><i class="fas fa-hourglass-half mr-1"></i> En attente</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Informations patient -->
    <div class="info-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center text-primary-blue">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="font-bold text-slate-800">Informations patient</h3>
        </div>
        <div class="space-y-3">
            <div>
                <div class="info-label">Nom complet</div>
                <div class="info-value">{{ $invoice->patient->user->name ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="info-label">Email</div>
                <div class="info-value">{{ $invoice->patient->user->email ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $invoice->patient->user->phone ?? 'Non renseigné' }}</div>
            </div>
        </div>
    </div>
    
    <!-- Informations facture -->
    <div class="info-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center text-primary-blue">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3 class="font-bold text-slate-800">Détails facture</h3>
        </div>
        <div class="space-y-3">
            <div>
                <div class="info-label">Numéro de facture</div>
                <div class="info-value font-mono">{{ $invoice->invoice_number }}</div>
            </div>
            <div>
                <div class="info-label">Date d'émission</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="info-label">Date d'échéance</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</div>
            </div>
            @if($invoice->description)
            <div>
                <div class="info-label">Description</div>
                <div class="info-value text-sm">{{ $invoice->description }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Montants -->
    <div class="info-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center text-primary-blue">
                <i class="fas fa-calculator"></i>
            </div>
            <h3 class="font-bold text-slate-800">Récapitulatif</h3>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <span class="text-slate-500">Montant total</span>
                <span class="font-bold text-slate-800">{{ number_format($invoice->amount, 2) }} DT</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <span class="text-slate-500">Déjà payé</span>
                <span class="font-bold text-success">{{ number_format($invoice->paid_amount, 2) }} DT</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Reste à payer</span>
                <span class="font-bold text-danger">{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</span>
            </div>
        </div>
    </div>
</div>

<!-- Détail des prises en charge -->
<div class="info-card animate-fade-up" style="animation-delay: 0.2s">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center text-primary-blue">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h3 class="font-bold text-slate-800">Détail des prises en charge</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- CNAM -->
        <div class="insurance-bar text-center">
            <i class="fas fa-building text-primary-blue text-xl mb-2"></i>
            <div class="font-bold text-slate-800">CNAM</div>
            <div class="text-2xl font-bold text-primary-blue mt-1">{{ number_format($invoice->cnam_amount, 2) }} DT</div>
            @if($invoice->cnam_paid)
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-paid"><i class="fas fa-check-circle mr-1"></i> Payé</span>
            @else
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-pending"><i class="fas fa-clock mr-1"></i> En attente</span>
            @endif
        </div>
        
        <!-- Mutuelle -->
        <div class="insurance-bar text-center">
            <i class="fas fa-handshake text-success text-xl mb-2"></i>
            <div class="font-bold text-slate-800">Mutuelle</div>
            <div class="text-2xl font-bold text-success mt-1">{{ number_format($invoice->mutuelle_amount, 2) }} DT</div>
            @if($invoice->mutuelle_paid)
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-paid"><i class="fas fa-check-circle mr-1"></i> Payé</span>
            @else
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-pending"><i class="fas fa-clock mr-1"></i> En attente</span>
            @endif
        </div>
        
        <!-- Patient -->
        <div class="insurance-bar text-center">
            <i class="fas fa-user text-warning text-xl mb-2"></i>
            <div class="font-bold text-slate-800">Patient</div>
            <div class="text-2xl font-bold text-warning mt-1">{{ number_format($invoice->patient_amount, 2) }} DT</div>
            @if($invoice->patient_paid)
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-paid"><i class="fas fa-check-circle mr-1"></i> Payé</span>
            @else
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold status-pending"><i class="fas fa-clock mr-1"></i> En attente</span>
            @endif
        </div>
    </div>
    
    <!-- Barre de progression -->
    @php
        $totalAmount = $invoice->amount;
        $cnamPercent = $totalAmount > 0 ? round(($invoice->cnam_amount / $totalAmount) * 100, 1) : 0;
        $mutuellePercent = $totalAmount > 0 ? round(($invoice->mutuelle_amount / $totalAmount) * 100, 1) : 0;
        $patientPercent = $totalAmount > 0 ? round(($invoice->patient_amount / $totalAmount) * 100, 1) : 0;
    @endphp
    
    <div class="mt-3">
        <div class="flex justify-between text-xs font-semibold mb-1">
            <span class="text-primary-blue">CNAM ({{ $cnamPercent }}%)</span>
            <span class="text-success">Mutuelle ({{ $mutuellePercent }}%)</span>
            <span class="text-warning">Patient ({{ $patientPercent }}%)</span>
        </div>
        <div class="insurance-progress">
            @if($cnamPercent > 0)
            <div class="progress-cnam" style="width: {{ $cnamPercent }}%">
                {{ $cnamPercent > 15 ? number_format($invoice->cnam_amount, 0) : '' }}
            </div>
            @endif
            @if($mutuellePercent > 0)
            <div class="progress-mutuelle" style="width: {{ $mutuellePercent }}%">
                {{ $mutuellePercent > 15 ? number_format($invoice->mutuelle_amount, 0) : '' }}
            </div>
            @endif
            @if($patientPercent > 0)
            <div class="progress-patient" style="width: {{ $patientPercent }}%">
                {{ $patientPercent > 15 ? number_format($invoice->patient_amount, 0) : '' }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Historique des paiements -->
@if($invoice->payments && $invoice->payments->count() > 0)
<div class="info-card animate-fade-up mt-6" style="animation-delay: 0.25s">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center text-primary-blue">
            <i class="fas fa-history"></i>
        </div>
        <h3 class="font-bold text-slate-800">Historique des paiements</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase">Date</th>
                    <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase">Montant</th>
                    <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase">Mode</th>
                    <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase">Type</th>
                    <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase">Référence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $payment)
                <tr class="border-b border-slate-50">
                    <td class="py-3 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                    <td class="py-3 text-sm font-semibold text-success">{{ number_format($payment->amount, 2) }} DT</td>
                    <td class="py-3 text-sm">
                        @if($payment->payment_method == 'cash')
                            <span><i class="fas fa-money-bill mr-1"></i> Espèces</span>
                        @elseif($payment->payment_method == 'card')
                            <span><i class="fas fa-credit-card mr-1"></i> Carte bancaire</span>
                        @elseif($payment->payment_method == 'check')
                            <span><i class="fas fa-check-circle mr-1"></i> Chèque</span>
                        @elseif($payment->payment_method == 'transfer')
                            <span><i class="fas fa-university mr-1"></i> Virement</span>
                        @endif
                    </td>
                    <td class="py-3 text-sm">
                        @if(str_contains($payment->notes ?? '', 'CNAM'))
                            <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:#eef2ff; color:#4f46e5;">CNAM</span>
                        @elseif(str_contains($payment->notes ?? '', 'MUTUELLE'))
                            <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:#ecfdf5; color:#059669;">Mutuelle</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:#fffbeb; color:#d97706;">Patient</span>
                        @endif
                    </td>
                    <td class="py-3 text-sm text-slate-500">{{ $payment->transaction_id ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50">
                    <td class="py-3 font-bold">Total payé</td>
                    <td class="py-3 font-bold text-success">{{ number_format($invoice->payments->sum('amount'), 2) }} DT</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

<!-- Boutons d'action -->
<div class="flex flex-wrap justify-center gap-3 mt-8 animate-fade-up" style="animation-delay: 0.3s">
    <button onclick="window.print()" class="action-btn bg-slate-100 text-slate-700 hover:bg-slate-200">
        <i class="fas fa-print"></i> Imprimer
    </button>
    
    @if(auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire')
    <a href="{{ route('invoices.edit', $invoice) }}" class="action-btn bg-amber-100 text-amber-700 hover:bg-amber-200">
        <i class="fas fa-edit"></i> Modifier
    </a>
    @endif
    
    @if(auth()->user()->role === 'patient')
    <a href="{{ route('patient.invoices') }}" class="action-btn bg-slate-100 text-slate-700 hover:bg-slate-200">
        <i class="fas fa-arrow-left"></i> Retour à mes factures
    </a>
    @else
    <a href="{{ route('invoices.index') }}" class="action-btn bg-slate-100 text-slate-700 hover:bg-slate-200">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
    @endif
</div>

<!-- Boutons de paiement avec redirection vers /invoices/{id}/pay -->
@php 
    $remainingCnam = $invoice->cnam_paid ? 0 : $invoice->cnam_amount;
    $remainingMutuelle = $invoice->mutuelle_paid ? 0 : $invoice->mutuelle_amount;
    $remainingPatient = $invoice->patient_paid ? 0 : $invoice->patient_amount;
@endphp

@if($remainingCnam > 0 || $remainingMutuelle > 0 || $remainingPatient > 0)
<div class="mt-6 pt-4 border-t border-slate-200 text-center animate-fade-up" style="animation-delay: 0.35s">
    <p class="text-slate-500 text-sm mb-3">Enregistrer un paiement</p>
    <div class="flex flex-wrap justify-center gap-3">
        @php
            $payRoute = auth()->user()->role === 'patient' ? 'patient.invoices.pay' : 'invoices.pay';
        @endphp
        
        @if($remainingCnam > 0 && (auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire'))
        <a href="{{ route($payRoute, ['invoice' => $invoice->id, 'type' => 'cnam']) }}" class="action-btn payment-btn-cnam">
            <i class="fas fa-building"></i> Encaisser CNAM ({{ number_format($remainingCnam, 2) }} DT)
        </a>
        @endif
        
        @if($remainingMutuelle > 0 && (auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire'))
        <a href="{{ route($payRoute, ['invoice' => $invoice->id, 'type' => 'mutuelle']) }}" class="action-btn payment-btn-mutuelle">
            <i class="fas fa-handshake"></i> Encaisser Mutuelle ({{ number_format($remainingMutuelle, 2) }} DT)
        </a>
        @endif
        
        @if($remainingPatient > 0)
        <a href="{{ route($payRoute, ['invoice' => $invoice->id, 'type' => 'patient']) }}" class="action-btn payment-btn-patient">
            <i class="fas fa-user"></i> Paiement patient ({{ number_format($remainingPatient, 2) }} DT)
        </a>
        @endif
    </div>
</div>
@endif

<style>
    @media print {
        .no-print, .action-btn, button, .btn, form, .modal-footer, .modal, [onclick] {
            display: none !important;
        }
        .invoice-header, .info-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

@endsection