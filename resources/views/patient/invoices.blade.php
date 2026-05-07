@extends('layouts.app')

@section('page_title', 'Mes factures')
@section('page_subtitle', 'Suivi de vos paiements et factures')

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

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .invoice-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .invoice-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-paid {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-partial {
        background: #fffbeb;
        color: #d97706;
    }
    
    .status-pending {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: scale(1.05);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-state-icon i {
        font-size: 36px;
        color: var(--primary-blue);
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
    
    .progress-bar-custom {
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--primary-light), var(--primary-lighter));
        transition: width 0.5s ease;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-file-invoice-dollar text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">ESPACE PATIENT</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Mes factures</h1>
        <p class="text-white/60 text-sm">Consultez l'historique de vos factures et suivez vos paiements</p>
    </div>
</div>

<!-- Statistiques Cards -->
@php
    $totalAmount = $invoices->sum('amount');
    $totalPaid = $invoices->sum('paid_amount');
    $totalRemaining = $totalAmount - $totalPaid;
    $paidPercentage = $totalAmount > 0 ? round(($totalPaid / $totalAmount) * 100, 1) : 0;
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total facturé</div>
                <div class="stats-value">{{ number_format($totalAmount, 2) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total payé</div>
                <div class="stats-value text-success">{{ number_format($totalPaid, 2) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Reste à payer</div>
                <div class="stats-value text-danger">{{ number_format($totalRemaining, 2) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progression des paiements -->
@if($totalAmount > 0)
<div class="bg-white rounded-xl p-5 mb-8 border border-slate-100 animate-fade-up" style="animation-delay: 0.2s">
    <div class="flex justify-between items-center mb-3">
        <div>
            <h3 class="text-sm font-semibold text-slate-700">Progression des paiements</h3>
            <p class="text-xs text-slate-400">{{ $paidPercentage }}% du montant total réglé</p>
        </div>
        <div class="text-primary-blue font-bold text-sm">{{ number_format($totalPaid, 2) }} DT / {{ number_format($totalAmount, 2) }} DT</div>
    </div>
    <div class="progress-bar-custom">
        <div class="progress-fill" style="width: {{ $paidPercentage }}%"></div>
    </div>
</div>
@endif

<!-- Liste des factures -->
@if($invoices->count() > 0)
    <div class="space-y-4" id="invoicesList">
        @foreach($invoices as $invoice)
        @php
            $remaining = $invoice->amount - $invoice->paid_amount;
            $isOverdue = $invoice->due_date < now() && $remaining > 0;
        @endphp
        <div class="invoice-card animate-fade-up" style="animation-delay: {{ 0.25 + ($loop->iteration * 0.03) }}s">
            <div class="p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    
                    <!-- Info facture -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center">
                                <i class="fas fa-receipt text-primary-blue text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $invoice->invoice_number }}</h3>
                                <div class="flex items-center gap-3 text-xs text-slate-400 mt-0.5">
                                    <span><i class="far fa-calendar-alt mr-1"></i>{{ $invoice->created_at->format('d/m/Y') }}</span>
                                    @if($invoice->due_date)
                                    <span><i class="far fa-hourglass-half mr-1"></i>Échéance: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Montants -->
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="text-xs text-slate-400 uppercase">Montant</div>
                            <div class="font-bold text-slate-800 text-lg">{{ number_format($invoice->amount, 2) }} DT</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-slate-400 uppercase">Payé</div>
                            <div class="font-bold text-success">{{ number_format($invoice->paid_amount, 2) }} DT</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-slate-400 uppercase">Reste</div>
                            <div class="font-bold {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($remaining, 2) }} DT
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statut et Actions -->
                    <div class="flex items-center gap-3">
                        <div>
                            @if($invoice->status == 'paid')
                                <span class="status-badge status-paid"><i class="fas fa-check-circle mr-1"></i> Payée</span>
                            @elseif($invoice->status == 'partially_paid')
                                <span class="status-badge status-partial"><i class="fas fa-clock mr-1"></i> Partielle</span>
                            @else
                                <span class="status-badge status-pending"><i class="fas fa-exclamation-circle mr-1"></i> En attente</span>
                            @endif
                            @if($isOverdue && $remaining > 0)
                                <span class="status-badge status-pending ml-2"><i class="fas fa-calendar-times mr-1"></i> En retard</span>
                            @endif
                        </div>
                        
                        <div class="flex gap-2 items-center">
                            <a href="{{ route('patient.invoices.show', $invoice) }}" class="btn-action bg-slate-100 text-slate-600 hover:bg-primary-bg hover:text-primary-blue" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($remaining > 0)
                            <a href="{{ route('patient.invoices.pay', $invoice) }}" class="btn-action bg-success/10 text-success hover:bg-success hover:text-white" title="Payer">
                                <i class="fas fa-credit-card"></i>
                            </a>
                            @endif

                            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn-action bg-slate-100 text-slate-600 hover:bg-primary-bg hover:text-primary-blue" title="Imprimer">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Détail des prises en charge (si disponible) -->
                @if($invoice->cnam_amount > 0 || $invoice->mutuelle_amount > 0)
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @if($invoice->cnam_amount > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-building text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400">CNAM</div>
                                <div class="text-sm font-semibold text-slate-700">{{ number_format($invoice->cnam_amount, 2) }} DT</div>
                            </div>
                            @if($invoice->cnam_paid)
                            <span class="text-success text-xs"><i class="fas fa-check-circle"></i> Payé</span>
                            @endif
                        </div>
                        @endif
                        @if($invoice->mutuelle_amount > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-handshake text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400">Mutuelle</div>
                                <div class="text-sm font-semibold text-slate-700">{{ number_format($invoice->mutuelle_amount, 2) }} DT</div>
                            </div>
                            @if($invoice->mutuelle_paid)
                            <span class="text-success text-xs"><i class="fas fa-check-circle"></i> Payé</span>
                            @endif
                        </div>
                        @endif
                        @if($invoice->patient_amount > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400">Patient</div>
                                <div class="text-sm font-semibold text-slate-700">{{ number_format($invoice->patient_amount, 2) }} DT</div>
                            </div>
                            @if($invoice->patient_paid)
                            <span class="text-success text-xs"><i class="fas fa-check-circle"></i> Payé</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($invoices, 'links'))
    <div class="mt-8">
        {{ $invoices->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade-up">
        <div class="empty-state-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune facture</h3>
        <p class="text-slate-500 mb-6">Vous n'avez pas encore de factures</p>
        <a href="{{ route('patient.appointments') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-calendar-plus"></i>
            <span>Prendre rendez-vous</span>
        </a>
    </div>
@endif

<style>
    /* Styles pour la pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .pagination .page-link:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
        color: var(--primary-blue);
    }
    
    .pagination .active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
    }
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@endsection