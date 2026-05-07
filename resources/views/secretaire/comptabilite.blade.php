@extends('layouts.app')

@section('page_title', 'Comptabilité & Finances')
@section('page_subtitle', 'Gestion des factures et suivi financier')

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
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .invoice-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        background: #f8fafc;
        padding: 16px;
        text-align: left;
    }
    
    .invoice-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-paid { background: #ecfdf5; color: #059669; }
    .status-pending { background: #fffbeb; color: #d97706; }
    .status-partially_paid { background: #eef2ff; color: #4f46e5; }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-radius: 16px;
        background: #f8fafc;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    
    .quick-action:hover {
        background: var(--primary-bg);
        transform: translateX(6px);
    }
    
    .quick-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .eye-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f1f5f9;
        color: #4f46e5;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .eye-icon:hover {
        background: #4f46e5;
        color: white;
        transform: scale(1.1);
    }
    
    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary-blue);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-view-all:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }
    
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

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-chart-line text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">FINANCES & COMPTABILITÉ</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Comptabilité</h1>
            <p class="text-white/60 text-sm">Gestion des factures et suivi financier</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
            <i class="fas fa-plus-circle"></i>
            <span>Nouvelle facture</span>
        </a>
    </div>
</div>

<!-- Statistiques Clés -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-primary-blue">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Revenus (Ce mois)</h3>
        <div class="text-2xl font-black text-slate-800">{{ number_format($stats['monthly_revenue'] ?? 0, 2) }} DT</div>
    </div>

    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-warning">
                <i class="fas fa-clock fa-lg"></i>
            </div>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">En attente</h3>
        <div class="text-2xl font-black text-slate-800">{{ number_format($stats['pending_payment'] ?? 0, 2) }} DT</div>
    </div>

    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-success">
                <i class="fas fa-user-check fa-lg"></i>
            </div>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Patients Total</h3>
        <div class="text-2xl font-black text-slate-800">{{ $stats['total_patients'] ?? 0 }}</div>
    </div>

    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
                <i class="fas fa-calendar-day fa-lg"></i>
            </div>
        </div>
        <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">RDV Aujourd'hui</h3>
        <div class="text-2xl font-black text-slate-800">{{ $stats['today_appointments'] ?? 0 }}</div>
    </div>
</div>

<!-- Graphiques & Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 chart-card animate-fade-up" style="animation-delay: 0.25s">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Chiffre d'affaires</h3>
                <p class="text-xs text-slate-400">Évolution des 12 derniers mois (DT)</p>
            </div>
        </div>
        <div style="height: 250px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="space-y-6">
        <div class="chart-card animate-fade-up" style="animation-delay: 0.3s">
            <h3 class="text-base font-bold text-slate-800 mb-4">Actions rapides</h3>
            
            <a href="{{ route('invoices.create') }}" class="quick-action">
                <div class="quick-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="flex-1"><div class="font-bold text-slate-800">Nouvelle facture</div></div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </a>
            
            <!-- CORRECTION: Lien direct vers la liste des factures -->
            <a href="{{ url('/invoices') }}" class="quick-action">
                <div class="quick-icon" style="background: #10B981;"><i class="fas fa-list"></i></div>
                <div class="flex-1"><div class="font-bold text-slate-800">Liste des factures</div></div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </a>
            
            <a href="{{ url('/secretaire/claims/cnam') }}" class="quick-action">
                <div class="quick-icon" style="background: #3B82F6;"><i class="fas fa-building"></i></div>
                <div class="flex-1"><div class="font-bold text-slate-800">Dossiers CNAM</div></div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </a>
            
            <a href="{{ url('/secretaire/claims/mutuelle') }}" class="quick-action">
                <div class="quick-icon" style="background: #10B981;"><i class="fas fa-handshake"></i></div>
                <div class="flex-1"><div class="font-bold text-slate-800">Dossiers Mutuelle</div></div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </a>
        </div>

        <div class="chart-card animate-fade-up" style="animation-delay: 0.35s">
            <h3 class="text-base font-bold text-slate-800 mb-4">Modes de paiement</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-slate-50 rounded-2xl text-center">
                    <div class="text-[10px] uppercase font-black text-slate-400">Espèces</div>
                    <div class="text-lg font-bold text-slate-700">{{ $methods_stats['cash'] ?? 0 }}%</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl text-center">
                    <div class="text-[10px] uppercase font-black text-slate-400">Carte</div>
                    <div class="text-lg font-bold text-slate-700">{{ $methods_stats['card'] ?? 0 }}%</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl text-center">
                    <div class="text-[10px] uppercase font-black text-slate-400">Chèque</div>
                    <div class="text-lg font-bold text-slate-700">{{ $methods_stats['check'] ?? 0 }}%</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl text-center">
                    <div class="text-[10px] uppercase font-black text-slate-400">Virement</div>
                    <div class="text-lg font-bold text-slate-700">{{ $methods_stats['transfer'] ?? 0 }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau Dernières factures -->
<div class="chart-card animate-fade-up" style="animation-delay: 0.4s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Dernières factures</h3>
        <!-- CORRECTION: Lien direct vers la liste des factures -->
        <a href="{{ url('/invoices') }}" class="text-primary-blue font-bold text-sm hover:underline">
            Voir toutes les factures <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full invoice-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Payé</th>
                    <th>Reste</th>
                    <th>Statut</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices->take(5) as $invoice)
                    @php
                        $remaining = $invoice->amount - $invoice->paid_amount;
                        $statusClass = match($invoice->status) {
                            'paid' => 'status-paid',
                            'partially_paid' => 'status-partially_paid',
                            default => 'status-pending'
                        };
                        $statusLabel = match($invoice->status) {
                            'paid' => 'PAYÉE',
                            'partially_paid' => 'PARTIELLE',
                            default => 'EN ATTENTE'
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="font-bold text-slate-700">{{ $invoice->invoice_number }}</td>
                        <td>
                            <span class="font-bold text-slate-800">{{ $invoice->patient->user->name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-slate-500">{{ $invoice->created_at->format('d/m/Y') }}</td>
                        <td class="font-bold">{{ number_format($invoice->amount, 2) }} DT</td>
                        <td class="text-emerald-500">{{ number_format($invoice->paid_amount, 2) }} DT</td>
                        <td class="text-rose-500">{{ number_format($remaining, 2) }} DT</td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-center">
                            <!-- CORRECTION: Lien direct vers show invoice -->
                            <a href="{{ route('invoices.show', $invoice) }}" class="eye-icon" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">Aucune facture enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels ?? []) !!},
                    datasets: [{
                        label: 'Revenus',
                        data: {!! json_encode($monthly_revenue_data ?? []) !!},
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toLocaleString() + ' DT';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' DT';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

@endsection