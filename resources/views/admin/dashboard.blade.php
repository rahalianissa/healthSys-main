@extends('layouts.app')

@section('page_title', 'Tableau de bord - Administration')
@section('page_subtitle', 'Gestion centralisée de votre établissement de santé')

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

    .dashboard-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    
    .stat-card {
        background: white;
        border-radius: 24px;
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
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .trend-up {
        background: #ecfdf5;
        color: #059669;
    }
    
    .trend-down {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
    }
    
    .btn-outline-blue {
        background: transparent;
        border: 1px solid var(--primary-light);
        color: var(--primary-light);
        transition: all 0.2s;
    }
    
    .btn-outline-blue:hover {
        background: var(--primary-light);
        color: white;
        border-color: var(--primary-light);
    }
    
    .recent-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        background: #f8fafc;
        padding: 16px 20px;
    }
    
    .recent-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-confirmed {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-pending {
        background: #fffbeb;
        color: #d97706;
    }
    
    .status-cancelled {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .status-completed {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--primary-light) 100%);
        border-radius: 28px;
        padding: 40px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
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
        animation: fadeInUp 0.6s ease forwards;
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner animate-fade-up">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-4 py-1.5 mb-4">
                <i class="fas fa-crown text-yellow-400 text-xs"></i>
                <span class="text-white text-xs font-semibold tracking-wider">ESPACE ADMINISTRATEUR</span>
            </div>
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-2">
                Bienvenue, <span class="text-cyan-300">{{ explode(' ', auth()->user()->name)[0] }}</span>
            </h1>
            <p class="text-white/70 text-sm">
                {{ now()->translatedFormat('l d F Y') }} — Vue d'ensemble de votre établissement
            </p>
        </div>
        <div class="bg-white/10 rounded-2xl px-6 py-3 text-center backdrop-blur-sm">
            <div class="text-white text-3xl font-bold">{{ $stats['today_appointments'] ?? 0 }}</div>
            <div class="text-white/60 text-xs font-semibold uppercase tracking-wider">Rendez-vous aujourd'hui</div>
        </div>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> Actifs
            </div>
        </div>
        <div class="stat-value">{{ $stats['doctors_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Médecins</div>
        <div class="text-xs text-slate-400 mt-2">Tous départements confondus</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> {{ $stats['secretaries_count'] ?? 0 }}
            </div>
        </div>
        <div class="stat-value">{{ $stats['secretaries_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Secrétaires</div>
        <div class="text-xs text-slate-400 mt-2">Équipe administrative</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-hospital-user"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full {{ ($stats['patients_growth'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="fas fa-arrow-{{ ($stats['patients_growth'] ?? 0) >= 0 ? 'up' : 'down' }} mr-1"></i> {{ abs($stats['patients_growth'] ?? 0) }}%
            </div>
        </div>
        <div class="stat-value">{{ $stats['patients_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Patients</div>
        <div class="text-xs text-slate-400 mt-2">+{{ $stats['new_patients_month'] ?? 0 }} ce mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full {{ ($stats['revenue_growth'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="fas fa-arrow-{{ ($stats['revenue_growth'] ?? 0) >= 0 ? 'up' : 'down' }} mr-1"></i> {{ abs($stats['revenue_growth'] ?? 0) }}%
            </div>
        </div>
        <div class="stat-value">{{ number_format(($stats['total_revenue'] ?? 0) / 1000, 1) }}k</div>
        <div class="stat-label mt-1">CA (DT)</div>
        <div class="text-xs text-slate-400 mt-2">Payé: {{ number_format($stats['paid_revenue'] ?? 0, 0) }} DT</div>
    </div>
</div>

<!-- Statistiques supplémentaires -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="dashboard-card p-5 flex items-center justify-between animate-fade-up" style="animation-delay: 0.25s">
        <div>
            <div class="text-slate-500 text-sm font-semibold mb-1">Rendez-vous en attente</div>
            <div class="text-2xl font-bold text-amber-600">{{ $stats['pending_appointments'] ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
            <i class="fas fa-clock text-amber-600 text-xl"></i>
        </div>
    </div>
    
    <div class="dashboard-card p-5 flex items-center justify-between animate-fade-up" style="animation-delay: 0.3s">
        <div>
            <div class="text-slate-500 text-sm font-semibold mb-1">Consultations terminées</div>
            <div class="text-2xl font-bold text-emerald-600">{{ $stats['completed_consultations'] ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
        </div>
    </div>
    
    <div class="dashboard-card p-5 flex items-center justify-between animate-fade-up" style="animation-delay: 0.35s">
        <div>
            <div class="text-slate-500 text-sm font-semibold mb-1">Revenu total</div>
            <div class="text-2xl font-bold text-primary-blue">{{ number_format($stats['total_revenue'] ?? 0, 0) }} DT</div>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-dollar-sign text-primary-blue text-xl"></i>
        </div>
    </div>
</div>

<!-- Graphique et Actions Rapides -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <div class="lg:col-span-2 chart-card animate-fade-up" style="animation-delay: 0.4s">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Évolution mensuelle</h3>
                <p class="text-sm text-slate-500">Rendez-vous et revenus sur 12 mois</p>
            </div>
            <div class="flex gap-2">
                <button onclick="updateChart('rdv')" id="btn-rdv" class="px-4 py-2 text-sm font-semibold rounded-xl bg-primary-blue text-white transition-all">
                    RDV
                </button>
                <button onclick="updateChart('rev')" id="btn-rev" class="px-4 py-2 text-sm font-semibold rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    Revenus
                </button>
            </div>
        </div>
        <div style="height: 320px;">
            <canvas id="mainChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card animate-fade-up" style="animation-delay: 0.45s">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Actions rapides</h3>
        
        <a href="{{ route('admin.doctors.create') }}" class="quick-action">
            <div class="quick-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Ajouter un médecin</div>
                <div class="text-xs text-slate-500">Nouveau praticien</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('secretaire.patients.create') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Ajouter un patient</div>
                <div class="text-xs text-slate-500">Nouveau dossier</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('admin.secretaries.create') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Ajouter une secrétaire</div>
                <div class="text-xs text-slate-500">Personnel administratif</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('admin.reports') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Générer un rapport</div>
                <div class="text-xs text-slate-500">Statistiques détaillées</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
    </div>
</div>

<!-- Derniers Rendez-vous -->
<div class="dashboard-card animate-fade-up" style="animation-delay: 0.5s">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Derniers rendez-vous</h3>
            <p class="text-sm text-slate-500">Activité récente du cabinet</p>
        </div>
        <a href="{{ route('secretaire.appointments.index') }}" class="text-sm font-semibold text-primary-blue hover:underline">
            Voir tout <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full recent-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Date & Heure</th>
                    <th>Statut</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAppointments ?? [] as $apt)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-bg flex items-center justify-center text-primary-blue font-bold">
                                {{ substr($apt->patient->user->name ?? 'P', 0, 1) }}
                            </div>
                            <span class="font-semibold text-slate-700">{{ $apt->patient->user->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="text-slate-600">Dr. {{ $apt->doctor->user->name ?? 'N/A' }}</td>
                    <td>
                        <div class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($apt->date_time)->format('d/m/Y') }}</div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}</div>
                    </td>
                    <td>
                        @php
                            $statusClass = match($apt->status) {
                                'confirmed' => 'status-confirmed',
                                'pending' => 'status-pending',
                                'cancelled' => 'status-cancelled',
                                'completed' => 'status-completed',
                                default => ''
                            };
                            $statusLabel = match($apt->status) {
                                'confirmed' => 'Confirmé',
                                'pending' => 'En attente',
                                'cancelled' => 'Annulé',
                                'completed' => 'Terminé',
                                default => $apt->status
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('secretaire.appointments.show', $apt->id) }}" class="text-primary-blue hover:text-primary-dark transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">
                        <i class="fas fa-calendar-alt text-3xl mb-2 opacity-50"></i>
                        <p>Aucun rendez-vous récent</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Récupérer les données depuis PHP
    let appointmentsRaw = @json($monthlyAppointments ?? []);
    let revenueRaw = @json($monthlyRevenue ?? []);
    
    // S'assurer que les tableaux ont 12 éléments
    const appointmentsData = Array.isArray(appointmentsRaw) && appointmentsRaw.length === 12 ? appointmentsRaw : Array(12).fill(0);
    const revenueData = Array.isArray(revenueRaw) && revenueRaw.length === 12 ? revenueRaw : Array(12).fill(0);
    
    const ctx = document.getElementById('mainChart').getContext('2d');
    let mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rendez-vous',
                data: appointmentsData,
                borderColor: '#023E8A',
                backgroundColor: 'rgba(2, 62, 138, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0077B6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', drawBorder: false },
                    ticks: { color: '#64748b', font: { size: 11, weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11, weight: '600' } }
                }
            }
        }
    });
    
    function updateChart(type) {
        const isRevenue = (type === 'rev');
        mainChart.data.datasets[0].label = isRevenue ? 'Revenus (DT)' : 'Rendez-vous';
        mainChart.data.datasets[0].data = isRevenue ? revenueData : appointmentsData;
        mainChart.data.datasets[0].borderColor = isRevenue ? '#10B981' : '#023E8A';
        mainChart.data.datasets[0].backgroundColor = isRevenue ? 'rgba(16, 185, 129, 0.05)' : 'rgba(2, 62, 138, 0.05)';
        mainChart.data.datasets[0].pointBackgroundColor = isRevenue ? '#10B981' : '#0077B6';
        mainChart.update();
        
        const rdvBtn = document.getElementById('btn-rdv');
        const revBtn = document.getElementById('btn-rev');
        
        if (isRevenue) {
            rdvBtn.classList.remove('bg-primary-blue', 'text-white');
            rdvBtn.classList.add('bg-slate-100', 'text-slate-600');
            revBtn.classList.remove('bg-slate-100', 'text-slate-600');
            revBtn.classList.add('bg-primary-blue', 'text-white');
        } else {
            rdvBtn.classList.remove('bg-slate-100', 'text-slate-600');
            rdvBtn.classList.add('bg-primary-blue', 'text-white');
            revBtn.classList.remove('bg-primary-blue', 'text-white');
            revBtn.classList.add('bg-slate-100', 'text-slate-600');
        }
    }
</script>

@endsection