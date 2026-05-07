@extends('layouts.app')

@section('page_title', 'Mon espace santé')
@section('page_subtitle', 'Bienvenue dans votre espace patient')

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

    .welcome-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--primary-light) 100%);
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-card::after {
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
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .appointment-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .appointment-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .doctor-avatar {
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
        font-size: 20px;
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
    
    .prescription-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .prescription-item:hover {
        background: white;
        border-color: var(--primary-lighter);
        transform: translateX(4px);
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
    
    .btn-primary-custom {
        background: var(--primary-blue);
        color: white;
        transition: all 0.3s;
    }
    
    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 62, 138, 0.2);
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-card animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-4">
            <i class="fas fa-heartbeat text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">ESPACE PATIENT</span>
        </div>
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-white text-2xl lg:text-3xl font-bold mb-2">
                    Bonjour, <span class="text-cyan-300">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </h1>
                <p class="text-white/60 text-sm">
                    {{ now()->translatedFormat('l d F Y') }} — Suivi de votre santé
                </p>
            </div>
            @if($stats['next_appointment'] ?? false)
            <div class="bg-white/10 rounded-2xl px-6 py-3 text-center backdrop-blur-sm">
                <div class="text-white/70 text-xs font-semibold uppercase">Prochain rendez-vous</div>
                <div class="text-white text-lg font-bold">{{ \Carbon\Carbon::parse($stats['next_appointment']->date_time)->format('d/m/Y') }}</div>
                <div class="text-cyan-300 text-sm font-semibold">{{ \Carbon\Carbon::parse($stats['next_appointment']->date_time)->format('H:i') }}</div>
            </div>
            @endif
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
        </div>
        <div class="stat-value">{{ $stats['appointments_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Rendez-vous</div>
        <div class="text-xs text-slate-400 mt-2">Total des consultations</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-prescription"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['prescriptions_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Ordonnances</div>
        <div class="text-xs text-slate-400 mt-2">Traitements prescrits</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['invoices_count'] ?? 0 }}</div>
        <div class="stat-label mt-1">Factures</div>
        <div class="text-xs text-slate-400 mt-2">Historique des paiements</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-3">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['unpaid_invoices'] ?? 0 }}</div>
        <div class="stat-label mt-1">À régler</div>
        <div class="text-xs text-slate-400 mt-2">Factures impayées</div>
    </div>
</div>

<!-- Actions Rapides et Prochain Rendez-vous -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Actions Rapides -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.25s">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-primary-blue"></i>
                Actions rapides
            </h3>
            
            <a href="{{ route('patient.appointments') }}" class="quick-action">
                <div class="quick-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-800">Prendre rendez-vous</div>
                    <div class="text-xs text-slate-500">Consulter un médecin</div>
                </div>
                <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
            </a>
            
            <a href="{{ route('patient.medical-record') }}" class="quick-action">
                <div class="quick-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-folder-medical"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-800">Mon dossier médical</div>
                    <div class="text-xs text-slate-500">Historique et examens</div>
                </div>
                <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
            </a>
            
            <a href="{{ route('patient.prescriptions') }}" class="quick-action">
                <div class="quick-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-file-prescription"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-800">Mes ordonnances</div>
                    <div class="text-xs text-slate-500">Télécharger PDF</div>
                </div>
                <i class="fas fa-chevron-right ml-auto text-slate-400"></i>
            </a>
        </div>
    </div>
    
    <!-- Prochain Rendez-vous -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.3s">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-primary-blue"></i>
                    Prochains rendez-vous
                </h3>
                @if(($stats['appointments_count'] ?? 0) > 0)
                <a href="{{ route('patient.appointments') }}" class="text-sm text-primary-blue hover:underline">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
                @endif
            </div>
            
            @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                @foreach($recentAppointments->take(3) as $apt)
                <div class="appointment-card mb-3 p-4">
                    <div class="flex items-center gap-4">
                        <div class="doctor-avatar">
                            {{ strtoupper(substr($apt->doctor->user->name ?? 'D', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-slate-800">Dr. {{ $apt->doctor->user->name ?? 'N/A' }}</h4>
                                    <p class="text-xs text-slate-500">{{ $apt->doctor->specialty ?? 'Généraliste' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-primary-blue">{{ \Carbon\Carbon::parse($apt->date_time)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-3">
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
                                @if($apt->status == 'pending' || $apt->status == 'confirmed')
                                <a href="{{ route('patient.appointments.show', $apt->id) }}" class="text-xs text-primary-blue hover:underline">
                                    Voir détails <i class="fas fa-eye ml-1"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-times text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 mb-3">Aucun rendez-vous programmé</p>
                    <a href="{{ route('patient.appointments') }}" class="btn-primary-custom inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold">
                        <i class="fas fa-plus-circle"></i>
                        Prendre rendez-vous
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Dernières ordonnances et Conseils santé -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Dernières ordonnances -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.35s">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-prescription text-primary-blue"></i>
                Dernières ordonnances
            </h3>
            @if(($stats['prescriptions_count'] ?? 0) > 0)
            <a href="{{ route('patient.prescriptions') }}" class="text-sm text-primary-blue hover:underline">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
            @endif
        </div>
        
        @if(isset($recentPrescriptions) && $recentPrescriptions->count() > 0)
            @foreach($recentPrescriptions->take(3) as $prescription)
            <div class="prescription-item mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-prescription text-primary-blue"></i>
                            <span class="font-semibold text-slate-700">Ordonnance</span>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Dr. {{ $prescription->doctor->user->name ?? 'N/A' }} • {{ $prescription->created_at->format('d/m/Y') }}
                        </div>
                        @php
                            $meds = is_array($prescription->medications) ? $prescription->medications : json_decode($prescription->medications ?? '[]', true);
                        @endphp
                        @if(is_array($meds) && count($meds) > 0)
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach(array_slice($meds, 0, 2) as $med)
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $med['name'] ?? 'Médicament' }}</span>
                            @endforeach
                            @if(count($meds) > 2)
                                <span class="text-xs text-slate-400">+{{ count($meds) - 2 }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('prescriptions.pdf', $prescription) }}" class="text-xs text-red-500 hover:text-red-600" target="_blank">
                        <i class="fas fa-file-pdf text-lg"></i>
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-prescription text-2xl text-slate-400"></i>
                </div>
                <p class="text-slate-500">Aucune ordonnance disponible</p>
            </div>
        @endif
    </div>
    
    <!-- Conseils santé -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 animate-fade-up" style="animation-delay: 0.4s">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-lightbulb text-warning"></i>
            Conseils santé
        </h3>
        
        <div class="space-y-4">
            <div class="flex gap-3 p-3 bg-primary-bg/30 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-apple-alt text-primary-blue"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-700">Alimentation équilibrée</h4>
                    <p class="text-xs text-slate-500">Une alimentation saine est essentielle pour maintenir une bonne santé.</p>
                </div>
            </div>
            
            <div class="flex gap-3 p-3 bg-primary-bg/30 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-walking text-primary-blue"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-700">Activité physique</h4>
                    <p class="text-xs text-slate-500">30 minutes d'exercice par jour améliorent votre santé cardiovasculaire.</p>
                </div>
            </div>
            
            <div class="flex gap-3 p-3 bg-primary-bg/30 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bed text-primary-blue"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-700">Sommeil réparateur</h4>
                    <p class="text-xs text-slate-500">Dormez 7 à 8 heures par nuit pour une meilleure récupération.</p>
                </div>
            </div>
            
            <div class="flex gap-3 p-3 bg-primary-bg/30 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-tint text-primary-blue"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-700">Hydratation</h4>
                    <p class="text-xs text-slate-500">Buvez au moins 1.5 L d'eau par jour pour rester hydraté.</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone-alt text-primary-blue text-sm"></i>
                    <span class="text-xs text-slate-500">Urgence médicale: <strong class="text-primary-blue">190</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-ambulance text-primary-blue text-sm"></i>
                    <span class="text-xs text-slate-500">SAMU: <strong class="text-primary-blue">15</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection