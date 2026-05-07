@extends('layouts.app')

@section('page_title', 'Rapport mensuel')
@section('page_subtitle', 'Analyse détaillée du mois de ' . $stats['month'])

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

    .report-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--primary-light) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .report-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .progress-bar-custom {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .type-general { background: #e0f2fe; color: #0284c7; }
    .type-emergency { background: #fef2f2; color: #dc2626; }
    .type-follow-up { background: #fef3c7; color: #d97706; }
    .type-specialist { background: #e0e7ff; color: #4f46e5; }
    
    .btn-print {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-print:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
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

<!-- Report Header -->
<div class="report-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-chart-line text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">RAPPORT MENSUEL</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-2">{{ $stats['month'] }}</h1>
        <p class="text-white/60 text-sm">Analyse détaillée des activités du cabinet</p>
    </div>
</div>

<!-- Filtre Navigation -->
<div class="flex flex-wrap gap-3 mb-8 animate-fade-up" style="animation-delay: 0.05s">
    <a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-all">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 btn-print">
        <i class="fas fa-print"></i> Imprimer
    </button>
    <div class="flex-1"></div>
    <div class="text-sm text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200">
        <i class="far fa-calendar-alt mr-2 text-primary-light"></i>
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</div>

<!-- Cartes Statistiques Principales -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="text-right">
                <div class="text-xs text-slate-400">Total</div>
                <div class="stat-value">{{ $stats['appointments_count'] }}</div>
            </div>
        </div>
        <div class="stat-label">Rendez-vous</div>
        <div class="mt-2 flex gap-2 text-xs">
            <span class="text-emerald-600"><i class="fas fa-check-circle"></i> Confirmés: {{ $stats['confirmed_appointments'] }}</span>
            <span class="text-red-600"><i class="fas fa-times-circle"></i> Annulés: {{ $stats['cancelled_appointments'] }}</span>
        </div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="stat-value">{{ $stats['new_patients'] }}</div>
        </div>
        <div class="stat-label">Nouveaux patients</div>
        <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
            <div class="bg-primary-lighter h-1.5 rounded-full" style="width: {{ min(100, ($stats['new_patients'] / 50) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0) }} DT</div>
        </div>
        <div class="stat-label">Chiffre d'affaires</div>
        <div class="mt-2 flex justify-between text-xs">
            <span class="text-emerald-600">Payé: {{ number_format($stats['total_paid'], 0) }} DT</span>
            <span class="text-amber-600">En attente: {{ number_format($stats['pending_payment'], 0) }} DT</span>
        </div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.25s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="stat-value">{{ $stats['completed_appointments'] }}</div>
        </div>
        <div class="stat-label">Consultations réalisées</div>
        <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
            @php $completionRate = $stats['appointments_count'] > 0 ? round(($stats['completed_appointments'] / $stats['appointments_count']) * 100) : 0; @endphp
            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $completionRate }}%"></div>
        </div>
        <div class="text-right text-xs text-slate-400 mt-1">Taux de réalisation: {{ $completionRate }}%</div>
    </div>
</div>

<!-- Graphique et Détails -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Évolution mensuelle -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 animate-fade-up" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Évolution des rendez-vous</h3>
                <p class="text-sm text-slate-500">Comparaison avec le mois précédent</p>
            </div>
            @php
                $prevMonthAppointments = $stats['appointments_count'] - ($stats['appointments_growth'] ?? 0);
                $growthIcon = ($stats['appointments_growth'] ?? 0) >= 0 ? 'up' : 'down';
                $growthColor = ($stats['appointments_growth'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600';
            @endphp
            <div class="text-right">
                <div class="text-xs text-slate-400">Mois dernier</div>
                <div class="font-semibold">{{ $prevMonthAppointments }}</div>
                <div class="text-xs {{ $growthColor }}"> <i class="fas fa-arrow-{{ $growthIcon }}"></i> {{ abs($stats['appointments_growth'] ?? 0) }}%</div>
            </div>
        </div>
        <div style="height: 250px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
    
    <!-- Répartition par type -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 animate-fade-up" style="animation-delay: 0.35s">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-slate-800">Répartition par type</h3>
            <p class="text-sm text-slate-500">Types de consultations</p>
        </div>
        
        <div class="space-y-4">
            @foreach($stats['appointments_by_type'] as $type => $count)
                @php
                    $percentage = $stats['appointments_count'] > 0 ? round(($count / $stats['appointments_count']) * 100) : 0;
                    $typeClass = match($type) {
                        'general' => 'type-general',
                        'emergency' => 'type-emergency',
                        'follow_up' => 'type-follow-up',
                        'specialist' => 'type-specialist',
                        default => 'type-general'
                    };
                    $typeLabel = match($type) {
                        'general' => 'Consultation générale',
                        'emergency' => 'Urgence',
                        'follow_up' => 'Suivi',
                        'specialist' => 'Spécialiste',
                        default => $type
                    };
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div>
                            <span class="type-badge {{ $typeClass }}">{{ $typeLabel }}</span>
                        </div>
                        <div class="text-sm font-semibold text-slate-600">{{ $count }} ({{ $percentage }}%)</div>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $percentage }}%; background: {{ match($type) { 'general' => '#0284c7', 'emergency' => '#dc2626', 'follow_up' => '#d97706', 'specialist' => '#4f46e5', default => '#023E8A' } }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Légende -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap gap-4 justify-center">
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#0284c7]"></div><span class="text-xs text-slate-500">Générale</span></div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#dc2626]"></div><span class="text-xs text-slate-500">Urgence</span></div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#d97706]"></div><span class="text-xs text-slate-500">Suivi</span></div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-[#4f46e5]"></div><span class="text-xs text-slate-500">Spécialiste</span></div>
        </div>
    </div>
</div>

<!-- Tableau détaillé -->
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.4s">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h3 class="text-lg font-bold text-slate-800">Détail des rendez-vous</h3>
        <p class="text-sm text-slate-500">Liste complète des rendez-vous du mois</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Médecin</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($appointments ?? [] as $appointment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="font-medium text-slate-700">{{ $appointment->patient->user->name ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-400">{{ $appointment->patient->user->phone ?? '' }}</div>
                    </td>
                    <td class="px-6 py-3">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3">
                        <div>{{ \Carbon\Carbon::parse($appointment->date_time)->format('d/m/Y') }}</div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-3">
                        <span class="type-badge {{ match($appointment->type) { 'general' => 'type-general', 'emergency' => 'type-emergency', 'follow_up' => 'type-follow-up', 'specialist' => 'type-specialist', default => 'type-general' } }}">
                            {{ match($appointment->type) { 'general' => 'Générale', 'emergency' => 'Urgence', 'follow_up' => 'Suivi', 'specialist' => 'Spécialiste', default => $appointment->type } }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $statusClass = match($appointment->status) {
                                'confirmed' => 'text-emerald-600 bg-emerald-50',
                                'pending' => 'text-amber-600 bg-amber-50',
                                'cancelled' => 'text-red-600 bg-red-50',
                                'completed' => 'text-blue-600 bg-blue-50',
                                default => 'text-slate-600 bg-slate-50'
                            };
                            $statusLabel = match($appointment->status) {
                                'confirmed' => 'Confirmé',
                                'pending' => 'En attente',
                                'cancelled' => 'Annulé',
                                'completed' => 'Terminé',
                                default => $appointment->status
                            };
                        @endphp
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-6 py-3 text-right font-semibold text-primary-blue">
                        {{ number_format($appointment->doctor->consultation_fee ?? 0, 0) }} DT
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-calendar-alt text-3xl mb-2 opacity-50"></i>
                        <p>Aucun rendez-vous enregistré pour ce mois</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour le graphique (comparaison avec mois précédent)
    const currentMonthData = [{{ $stats['appointments_count'] ?? 0 }}];
    const previousMonthData = [{{ $stats['appointments_count'] - ($stats['appointments_growth'] ?? 0) }}];
    
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mois actuel', 'Mois précédent'],
            datasets: [{
                label: 'Nombre de rendez-vous',
                data: [{{ $stats['appointments_count'] ?? 0 }}, {{ $stats['appointments_count'] - ($stats['appointments_growth'] ?? 0) }}],
                backgroundColor: ['#023E8A', '#90E0EF'],
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
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' rendez-vous';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

@endsection