@extends('layouts.app')

@section('page_title', 'Salle d\'attente')
@section('page_subtitle', 'Gestion des patients en attente de consultation')

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
        --purple: #8B5CF6;
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
    
    .waiting-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .waiting-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .priority-urgent {
        background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
        border-left: 4px solid var(--danger);
    }
    
    .priority-high {
        background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
        border-left: 4px solid var(--warning);
    }
    
    .priority-normal {
        background: linear-gradient(135deg, #F8FAFC, #F1F5F9);
        border-left: 4px solid var(--primary-light);
    }
    
    .patient-avatar {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 119, 182, 0.2);
    }
    
    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .priority-urgent-badge {
        background: #FEF2F2;
        color: var(--danger);
    }
    
    .priority-high-badge {
        background: #FFFBEB;
        color: var(--warning);
    }
    
    .priority-normal-badge {
        background: #F1F5F9;
        color: var(--primary-blue);
    }
    
    .btn-start {
        background: linear-gradient(135deg, var(--success), #059669);
        border: none;
        transition: all 0.2s;
    }
    
    .btn-start:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .current-patient {
        background: linear-gradient(135deg, var(--primary-bg), #E0F2FE);
        border: 2px solid var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.2);
    }
    
    .current-patient .patient-avatar {
        background: linear-gradient(135deg, var(--success), #059669);
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
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
    
    .timer {
        font-family: monospace;
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-blue);
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }
    
    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
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
    
    .btn-refresh {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    
    .btn-refresh:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-clock text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">CONSULTATIONS EN DIRECT</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Salle d'attente</h1>
            <p class="text-white/60 text-sm">Gérez les patients en attente de consultation</p>
        </div>
        <button onclick="location.reload()" class="btn-refresh inline-flex items-center gap-2 text-slate-600">
            <i class="fas fa-sync-alt text-sm"></i>
            <span>Rafraîchir</span>
        </button>
    </div>
</div>

<!-- Patient en consultation actuelle -->
@if($inConsultation ?? false)
<div class="current-patient rounded-2xl p-5 mb-8 animate-fade-up">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="patient-avatar">
                {{ substr($inConsultation->patient->user->name ?? 'P', 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-slate-800 text-xl">{{ $inConsultation->patient->user->name ?? 'Patient' }}</h3>
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                        <i class="fas fa-circle fa-xs mr-1 pulse-animation"></i> En consultation
                    </span>
                </div>
                <div class="flex items-center gap-4 mt-2 text-sm text-slate-500">
                    <span><i class="fas fa-phone-alt mr-1"></i> {{ $inConsultation->patient->user->phone ?? 'Non renseigné' }}</span>
                    <span><i class="fas fa-clock mr-1"></i> Début: {{ \Carbon\Carbon::parse($inConsultation->start_time)->format('H:i') }}</span>
                    <span class="timer" data-start="{{ $inConsultation->start_time }}"></span>
                </div>
            </div>
        </div>
        <form action="{{ route('doctor.consultation.complete', $inConsultation) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-success text-white rounded-xl font-semibold hover:bg-emerald-700 transition-all">
                <i class="fas fa-check-circle"></i>
                <span>Terminer consultation</span>
            </button>
        </form>
    </div>
</div>
@else
<div class="bg-slate-50 rounded-2xl p-5 mb-8 text-center border border-slate-100 animate-fade-up">
    <i class="fas fa-user-check text-4xl text-slate-300 mb-2"></i>
    <p class="text-slate-500">Aucun patient en consultation pour le moment</p>
</div>
@endif

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">En attente</div>
                <div class="text-3xl font-bold text-amber-600">{{ $waiting->count() }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-amber-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Temps d'attente moyen</div>
                <div class="text-3xl font-bold text-primary-blue">15 min</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-stopwatch text-primary-blue text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Consultations aujourd'hui</div>
                <div class="text-3xl font-bold text-emerald-600">{{ $waiting->count() + ($inConsultation ? 1 : 0) }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-calendar-check text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Liste des patients en attente -->
<h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
    <i class="fas fa-users text-primary-blue"></i>
    Patients en attente
    <span class="bg-primary-bg text-primary-blue text-xs px-2 py-1 rounded-full">{{ $waiting->count() }}</span>
</h3>

@if($waiting->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @foreach($waiting as $item)
        @php
            $priorityClass = '';
            $priorityBadge = '';
            $priorityIcon = '';
            $bgClass = '';
            
            if($item->priority == 2) {
                $priorityClass = 'priority-urgent';
                $priorityBadge = 'priority-urgent-badge';
                $priorityIcon = 'fa-triangle-exclamation';
            } elseif($item->priority == 1) {
                $priorityClass = 'priority-high';
                $priorityBadge = 'priority-high-badge';
                $priorityIcon = 'fa-circle-exclamation';
            } else {
                $priorityClass = 'priority-normal';
                $priorityBadge = 'priority-normal-badge';
                $priorityIcon = 'fa-clock';
            }
        @endphp
        
        <div class="waiting-card {{ $priorityClass }}">
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="patient-avatar">
                        {{ substr($item->patient->user->name ?? 'P', 0, 1) }}
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-start justify-between flex-wrap gap-2">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ $item->patient->user->name ?? 'Patient' }}</h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-sm text-slate-500">
                                        <i class="fas fa-phone-alt mr-1 text-xs"></i>
                                        {{ $item->patient->user->phone ?? 'Non renseigné' }}
                                    </span>
                                    <span class="priority-badge {{ $priorityBadge }}">
                                        <i class="fas {{ $priorityIcon }} text-xs"></i>
                                        @if($item->priority == 2) Urgent
                                        @elseif($item->priority == 1) Prioritaire
                                        @else Normal @endif
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-400">Arrivée</div>
                                <div class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($item->arrival_time)->format('H:i') }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($item->arrival_time)->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        <!-- Motif si disponible -->
                        @if($item->appointment && $item->appointment->reason)
                        <div class="mt-3 p-2 bg-slate-50 rounded-lg text-sm text-slate-600">
                            <i class="fas fa-sticky-note mr-1 text-slate-400"></i>
                            {{ $item->appointment->reason }}
                        </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-4 flex justify-end">
                            <form action="{{ route('doctor.consultation.start', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-start inline-flex items-center gap-2 px-5 py-2.5 text-white rounded-xl font-semibold text-sm">
                                    <i class="fas fa-stethoscope"></i>
                                    <span>Commencer consultation</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-clock"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Salle d'attente vide</h3>
        <p class="text-slate-500 mb-6">Aucun patient en attente de consultation</p>
        <div class="inline-flex items-center gap-2 text-slate-400 text-sm">
            <i class="fas fa-check-circle text-success"></i>
            <span>Vous êtes à jour !</span>
        </div>
    </div>
@endif

<script>
    // Timer pour le patient en consultation
    function updateTimers() {
        const timerElements = document.querySelectorAll('.timer');
        timerElements.forEach(el => {
            const startTime = el.dataset.start;
            if (startTime) {
                const start = new Date(startTime);
                const now = new Date();
                const diff = Math.floor((now - start) / 1000 / 60);
                if (diff >= 0) {
                    el.innerHTML = `<i class="fas fa-hourglass-half mr-1"></i> Durée: ${diff} min`;
                }
            }
        });
    }
    
    // Mettre à jour les timers toutes les minutes
    updateTimers();
    setInterval(updateTimers, 60000);
    
    // Auto-refresh toutes les 30 secondes (optionnel)
    let autoRefresh = true;
    if (autoRefresh && {{ $waiting->count() > 0 ? 'true' : 'false' }}) {
        setTimeout(() => {
            location.reload();
        }, 30000);
    }
</script>

<!-- Style additionnel pour les animations -->
<style>
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
    
    .waiting-card {
        animation: fadeInUp 0.4s ease forwards;
        animation-delay: calc(0.05s * var(--index, 0));
    }
</style>

@endsection