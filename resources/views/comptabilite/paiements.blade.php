@extends('layouts.app')

@section('page_title', 'Historique des paiements')
@section('page_subtitle', 'Suivi de tous les encaissements effectués')

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
    
    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payment-table th {
        text-align: left;
        padding: 16px 20px;
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .payment-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .method-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .method-cash { background: #ecfdf5; color: #059669; }
    .method-card { background: #eff6ff; color: #2563eb; }
    .method-check { background: #fff7ed; color: #ea580c; }
    .method-transfer { background: #f5f3ff; color: #7c3aed; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
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

<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-money-bill-wave text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">COMPTABILITÉ</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Historique des paiements</h1>
            <p class="text-white/60 text-sm">Suivi des encaissements patients et assurances</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 bg-white/10 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-white/20 transition-all">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Factures</span>
            </a>
            <a href="{{ route('secretaire.comptabilite') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.1s">
    <div class="overflow-x-auto">
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Facture</th>
                    <th>Montant</th>
                    <th>Mode de paiement</th>
                    <th>Référence / Notes</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="text-sm text-slate-600">
                        <div class="font-bold text-slate-700">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : $payment->created_at->format('d/m/Y') }}</div>
                        <div class="text-[10px] text-slate-400">{{ $payment->created_at->format('H:i') }}</div>
                    </td>
                    <td>
                        <div class="font-bold text-slate-800">{{ $payment->invoice->patient->user->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-primary-blue font-mono text-sm hover:underline">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </td>
                    <td>
                        <div class="font-black text-slate-800">{{ number_format($payment->amount, 2) }} DT</div>
                    </td>
                    <td>
                        @php
                            $methodClass = match($payment->payment_method) {
                                'cash' => 'method-cash',
                                'card' => 'method-card',
                                'check' => 'method-check',
                                'transfer' => 'method-transfer',
                                default => 'bg-slate-100 text-slate-600'
                            };
                            $methodIcon = match($payment->payment_method) {
                                'cash' => 'fa-money-bill-wave',
                                'card' => 'fa-credit-card',
                                'check' => 'fa-money-check',
                                'transfer' => 'fa-university',
                                default => 'fa-wallet'
                            };
                        @endphp
                        <span class="method-badge {{ $methodClass }}">
                            <i class="fas {{ $methodIcon }}"></i>
                            {{ $payment->payment_method_label }}
                        </span>
                    </td>
                    <td>
                        <div class="text-xs text-slate-500 max-w-[200px] truncate" title="{{ $payment->notes }}">
                            @if($payment->transaction_id)
                                <span class="font-mono text-slate-700">Ref: {{ $payment->transaction_id }}</span>
                            @endif
                            @if($payment->notes)
                                <div class="mt-0.5">{{ $payment->notes }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-blue hover:text-white inline-flex items-center justify-center transition-all">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-400">
                        <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-20"></i>
                        <p>Aucun paiement enregistré pour le moment</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $payments->links() }}
</div>

@endsection
