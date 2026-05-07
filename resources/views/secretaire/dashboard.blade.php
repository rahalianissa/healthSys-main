@extends('layouts.app')

@section('page_title', 'Tableau de bord - Secrétariat')
@section('page_subtitle', 'Gestion quotidienne du cabinet médical')

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

    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--primary-light) 100%);
        border-radius: 28px;
        padding: 35px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
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
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .trend-up {
        background: #ecfdf5;
        color: #059669;
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
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }
    
    .appointment-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .appointment-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-lighter);
        box-shadow: 0 8px 20px -5px rgba(0, 119, 182, 0.08);
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
    
    .btn-outline-blue {
        background: transparent;
        border: 1px solid var(--primary-light);
        color: var(--primary-light);
        transition: all 0.2s;
    }
    
    .btn-outline-blue:hover {
        background: var(--primary-light);
        color: white;
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
    
    .waiting-counter {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }
    
    .revenue-counter {
        background: linear-gradient(135deg, #10B981, #059669);
    }
    
    .patients-counter {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-calendar-alt text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">TABLEAU DE BORD</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">
                Bonjour, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p class="text-white/60 text-sm">
                {{ now()->translatedFormat('l d F Y') }} — Gestion quotidienne du cabinet
            </p>
        </div>
        <div class="bg-white/10 rounded-2xl px-6 py-3 text-center backdrop-blur-sm">
            <div class="text-white text-2xl font-bold">{{ $stats['today_appointments'] ?? 0 }}</div>
            <div class="text-white/60 text-xs font-semibold uppercase tracking-wider">Rendez-vous aujourd'hui</div>
        </div>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> {{ $stats['appointments_growth'] ?? 0 }}%
            </div>
        </div>
        <div class="stat-value">{{ $stats['today_appointments'] ?? 0 }}</div>
        <div class="stat-label mt-1">Rendez-vous aujourd'hui</div>
        <div class="text-xs text-slate-400 mt-2">{{ $stats['pending_appointments'] ?? 0 }} en attente</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-users mr-1"></i> Actifs
            </div>
        </div>
        <div class="stat-value">{{ $stats['waiting_room_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Salle d'attente</div>
        <div class="text-xs text-slate-400 mt-2">Patients en attente</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full {{ ($stats['patients_growth'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="fas fa-arrow-{{ ($stats['patients_growth'] ?? 0) >= 0 ? 'up' : 'down' }} mr-1"></i> {{ abs($stats['patients_growth'] ?? 0) }}%
            </div>
        </div>
        <div class="stat-value">{{ $stats['new_patients_today'] ?? 0 }}</div>
        <div class="stat-label mt-1">Nouveaux patients</div>
        <div class="text-xs text-slate-400 mt-2">Aujourd'hui</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-chart-line mr-1"></i> Aujourd'hui
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['today_revenue'] ?? 0, 0) }} DT</div>
        <div class="stat-label mt-1">Chiffre d'affaires</div>
        <div class="text-xs text-slate-400 mt-2">Revenus du jour</div>
    </div>
</div>

<!-- Graphique et Actions Rapides -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Graphique -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.25s">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Activité du cabinet</h3>
                <p class="text-sm text-slate-500">Rendez-vous et factures - 12 derniers mois</p>
            </div>
            <div class="flex gap-2">
                <button onclick="updateChart('rdv')" id="btn-rdv" class="px-4 py-2 text-sm font-semibold rounded-xl bg-primary-blue text-white transition-all">
                    RDV
                </button>
                <button onclick="updateChart('inv')" id="btn-inv" class="px-4 py-2 text-sm font-semibold rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    Factures
                </button>
            </div>
        </div>
        <div style="height: 280px;">
            <canvas id="mainChart"></canvas>
        </div>
    </div>
    
    <!-- Actions Rapides -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.3s">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Actions rapides</h3>
        
        <a href="{{ route('secretaire.appointments.create') }}" class="quick-action">
            <div class="quick-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Prendre rendez-vous</div>
                <div class="text-xs text-slate-500">Nouvelle consultation</div>
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
        
        <a href="{{ route('secretaire.waiting-room') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Salle d'attente</div>
                <div class="text-xs text-slate-500">Gérer les arrivées</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('secretaire.comptabilite') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Facturation</div>
                <div class="text-xs text-slate-500">Gérer les paiements</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
    </div>
</div>

<!-- Rendez-vous du Jour et Salle d'attente -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Rendez-vous du jour -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.35s">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-calendar-day text-primary-blue mr-2"></i>
                    Rendez-vous du jour
                </h3>
                <p class="text-xs text-slate-400 mt-1">Liste des consultations programmées</p>
            </div>
            <a href="{{ route('secretaire.appointments.index') }}" class="text-xs font-semibold text-primary-blue hover:underline">
                Voir tous <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($todayAppointments ?? [] as $apt)
            <div class="p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center text-primary-blue font-bold">
                            {{ substr($apt->patient->user->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $apt->patient->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-500">Dr. {{ $apt->doctor->user->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium text-primary-blue">{{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}</div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($apt->date_time)->format('d/m') }}</div>
                    </div>
                </div>
                @if($apt->reason)
                <div class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded-lg">
                    <i class="fas fa-sticky-note mr-1"></i> {{ Str::limit($apt->reason, 60) }}
                </div>
                @endif
            </div>
            @empty
            <div class="p-8 text-center">
                <i class="fas fa-calendar-alt text-3xl text-slate-300 mb-2"></i>
                <p class="text-slate-500">Aucun rendez-vous pour aujourd'hui</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Salle d'attente -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.4s">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    Salle d'attente
                </h3>
                <p class="text-xs text-slate-400 mt-1">Patients en attente de consultation</p>
            </div>
            <a href="{{ route('secretaire.waiting-room') }}" class="text-xs font-semibold text-primary-blue hover:underline">
                Gérer <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($waitingPatients ?? [] as $waiting)
            <div class="p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold">
                            {{ substr($waiting->patient->user->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $waiting->patient->user->name ?? 'N/A' }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                @if($waiting->priority == 2)
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">URGENT</span>
                                @elseif($waiting->priority == 1)
                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Prioritaire</span>
                                @endif
                                <span class="text-xs text-slate-400">Arrivé à {{ \Carbon\Carbon::parse($waiting->arrival_time)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-slate-400">{{ $waiting->arrival_time->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <i class="fas fa-clock text-3xl text-slate-300 mb-2"></i>
                <p class="text-slate-500">Salle d'attente vide</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données du graphique - Version corrigée
    const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Récupération des données PHP
    let appointmentsRaw = {!! json_encode($monthlyAppointments ?? []) !!};
    let invoicesRaw = {!! json_encode($monthlyInvoices ?? []) !!};
    
    // Vérification et correction des tableaux
    let appointmentsData = [];
    let invoicesData = [];
    
    if (Array.isArray(appointmentsRaw) && appointmentsRaw.length === 12) {
        appointmentsData = appointmentsRaw;
    } else {
        appointmentsData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }
    
    if (Array.isArray(invoicesRaw) && invoicesRaw.length === 12) {
        invoicesData = invoicesRaw;
    } else {
        invoicesData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }
    
    // Création du graphique
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
        const isInvoice = (type === 'inv');
        mainChart.data.datasets[0].label = isInvoice ? 'Factures' : 'Rendez-vous';
        mainChart.data.datasets[0].data = isInvoice ? invoicesData : appointmentsData;
        mainChart.data.datasets[0].borderColor = isInvoice ? '#10B981' : '#023E8A';
        mainChart.data.datasets[0].backgroundColor = isInvoice ? 'rgba(16, 185, 129, 0.05)' : 'rgba(2, 62, 138, 0.05)';
        mainChart.data.datasets[0].pointBackgroundColor = isInvoice ? '#10B981' : '#0077B6';
        mainChart.update();
        
        const rdvBtn = document.getElementById('btn-rdv');
        const invBtn = document.getElementById('btn-inv');
        
        if (rdvBtn && invBtn) {
            if (isInvoice) {
                rdvBtn.classList.remove('bg-primary-blue', 'text-white');
                rdvBtn.classList.add('bg-slate-100', 'text-slate-600');
                invBtn.classList.remove('bg-slate-100', 'text-slate-600');
                invBtn.classList.add('bg-primary-blue', 'text-white');
            } else {
                rdvBtn.classList.remove('bg-slate-100', 'text-slate-600');
                rdvBtn.classList.add('bg-primary-blue', 'text-white');
                invBtn.classList.remove('bg-primary-blue', 'text-white');
                invBtn.classList.add('bg-slate-100', 'text-slate-600');
            }
        }
    }
</script>

@endsection