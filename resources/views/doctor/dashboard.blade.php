@extends('layouts.app')

@section('page_title', 'Tableau de bord - Médecin')
@section('page_subtitle', 'Vue d\'ensemble de votre activité médicale')

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
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
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
    
    .action-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
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
    
    .appointment-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .appointment-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-lighter);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .patient-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: white;
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
</style>

<!-- Welcome Banner -->
<div class="welcome-banner animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1.5 mb-4">
                <i class="fas fa-stethoscope text-cyan-300 text-xs"></i>
                <span class="text-white text-xs font-semibold tracking-wider">ESPACE PRATICIEN</span>
            </div>
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-2">
                Bonjour, <span class="text-cyan-300">Dr. {{ explode(' ', auth()->user()->name)[0] }}</span>
            </h1>
            <p class="text-white/70 text-sm">
                {{ now()->translatedFormat('l d F Y') }} — Bienvenue sur votre espace de travail
            </p>
        </div>
        <div class="bg-white/10 rounded-2xl px-6 py-3 text-center backdrop-blur-sm">
            <div class="text-white text-3xl font-bold">{{ $stats['today_appointments'] ?? 0 }}</div>
            <div class="text-white/60 text-xs font-semibold uppercase tracking-wider">Consultations aujourd'hui</div>
        </div>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-calendar-check mr-1"></i> Aujourd'hui
            </div>
        </div>
        <div class="stat-value">{{ $stats['today_appointments'] ?? 0 }}</div>
        <div class="stat-label mt-1">Rendez-vous</div>
        <div class="text-xs text-slate-400 mt-2">Programmés pour aujourd'hui</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full" style="background: #fffbeb; color: #d97706;">
                <i class="fas fa-hourglass-half mr-1"></i> En attente
            </div>
        </div>
        <div class="stat-value">{{ $stats['pending_appointments'] ?? 0 }}</div>
        <div class="stat-label mt-1">En attente</div>
        <div class="text-xs text-slate-400 mt-2">À confirmer</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-users"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-chart-line mr-1"></i> Total
            </div>
        </div>
        <div class="stat-value">{{ $stats['patients_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Patients</div>
        <div class="text-xs text-slate-400 mt-2">Suivis au total</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full trend-up">
                <i class="fas fa-chart-simple mr-1"></i> Activité
            </div>
        </div>
        <div class="stat-value">{{ $stats['consultations_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Consultations</div>
        <div class="text-xs text-slate-400 mt-2">Au total</div>
    </div>
</div>

<!-- Graphique et Actions Rapides -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- Graphique simple -->
    <div class="lg:col-span-2 chart-card animate-fade-up" style="animation-delay: 0.25s">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Aperçu de l'activité</h3>
                <p class="text-sm text-slate-500">Rendez-vous et consultations</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="text-center p-5 bg-slate-50 rounded-2xl">
                <div class="text-3xl font-bold text-primary-blue">{{ $stats['appointments_count'] ?? 0 }}</div>
                <div class="text-sm text-slate-500 mt-1">Rendez-vous totaux</div>
            </div>
            <div class="text-center p-5 bg-slate-50 rounded-2xl">
                <div class="text-3xl font-bold text-success">{{ $stats['consultations_count'] ?? 0 }}</div>
                <div class="text-sm text-slate-500 mt-1">Consultations réalisées</div>
            </div>
        </div>
        
        <!-- Monthly Charts Section -->
        @if(isset($monthlyAppointments) && $monthlyAppointments->count() > 0)
        <div class="mt-6">
            <h4 class="text-sm font-semibold text-slate-700 mb-4">Évolution mensuelle</h4>
            <div class="space-y-4">
                @foreach($monthlyAppointments as $index => $count)
                <div>
                    <div class="flex justify-between text-xs text-slate-600 mb-1">
                        <span>{{ $chartLabels[$index] ?? 'Mois ' . ($index+1) }}</span>
                        <span>{{ $count }} RDV</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-primary-blue h-2 rounded-full" style="width: {{ $stats['appointments_count'] > 0 ? ($count / $stats['appointments_count']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="mt-4 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-slate-600">Croissance des rendez-vous</span>
                <span class="text-sm font-bold {{ $stats['appointments_growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $stats['appointments_growth'] >= 0 ? '+' : '' }}{{ $stats['appointments_growth'] }}%
                </span>
            </div>
            <div class="text-xs text-slate-500">Comparé au mois dernier</div>
        </div>
    </div>
    
    <!-- Actions Rapides -->
    <div class="action-card animate-fade-up" style="animation-delay: 0.3s">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Actions rapides</h3>
        
        <a href="{{ route('doctor.waiting-room') }}" class="quick-action">
            <div class="quick-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Salle d'attente</div>
                <div class="text-xs text-slate-500">Gérer la file des patients</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('doctor.consultations.create') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Nouvelle consultation</div>
                <div class="text-xs text-slate-500">Démarrer un examen</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('doctor.calendar') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Mon calendrier</div>
                <div class="text-xs text-slate-500">Voir les disponibilités</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
        
        <a href="{{ route('doctor.establish-document') }}" class="quick-action">
            <div class="quick-icon" style="background: linear-gradient(135deg, #8B5CF6, #6D28D9);">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800">Documents médicaux</div>
                <div class="text-xs text-slate-500">Ordonnances, certificats</div>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
        </a>
    </div>
</div>

<!-- Prochains Rendez-vous -->
<div class="chart-card animate-fade-up" style="animation-delay: 0.35s">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Prochains rendez-vous</h3>
            <p class="text-sm text-slate-500">Patients attendus aujourd'hui et prochainement</p>
        </div>
        <a href="{{ route('doctor.calendar') }}" class="text-sm font-semibold text-primary-blue hover:underline">
            Voir tout <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    @if(isset($recentAppointments) && $recentAppointments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($recentAppointments as $apt)
            <div class="appointment-card">
                <div class="flex items-start gap-4">
                    <div class="patient-avatar">
                        {{ substr($apt->patient->user->name ?? 'P', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $apt->patient->user->name ?? 'Patient' }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-slate-400">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($apt->date_time)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
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
                        </div>
                        
                        @if($apt->reason)
                        <p class="text-xs text-slate-500 mt-2 line-clamp-1">
                            <i class="fas fa-sticky-note mr-1"></i> {{ Str::limit($apt->reason, 50) }}
                        </p>
                        @endif
                        
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('doctor.appointments.show', $apt->id) }}" class="text-xs text-primary-blue hover:underline">
                                <i class="fas fa-eye mr-1"></i> Voir détails
                            </a>
                            @if($apt->status == 'confirmed' || $apt->status == 'pending')
                            <a href="{{ route('doctor.consultations.create') }}?appointment={{ $apt->id }}&patient={{ $apt->patient_id }}" class="text-xs text-success hover:underline">
                                <i class="fas fa-stethoscope mr-1"></i> Démarrer
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-alt text-2xl text-slate-400"></i>
            </div>
            <h4 class="text-lg font-semibold text-slate-600 mb-1">Aucun rendez-vous</h4>
            <p class="text-slate-400 text-sm">Aucun rendez-vous programmé pour le moment</p>
        </div>
    @endif
</div>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@endsection