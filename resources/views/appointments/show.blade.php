@extends('layouts.app')

@section('page_title', 'Détails du rendez-vous')
@section('page_subtitle', 'Informations complètes du rendez-vous')

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
    
    .info-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .card-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafcff;
    }
    
    .info-row {
        padding: 16px 24px;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        flex-wrap: wrap;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        width: 140px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-value {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
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
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .type-general {
        background: #e0f2fe;
        color: #0284c7;
    }
    
    .type-emergency {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .type-follow_up {
        background: #fef3c7;
        color: #d97706;
    }
    
    .type-specialist {
        background: #e0e7ff;
        color: #4f46e5;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .btn-confirm {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(16, 185, 129, 0.4);
    }
    
    .btn-cancel {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(239, 68, 68, 0.4);
    }
    
    .btn-edit {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(245, 158, 11, 0.4);
    }
    
    .btn-back {
        background: #f1f5f9;
        color: #475569;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-back:hover {
        background: #e2e8f0;
    }
    
    .avatar-large {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 119, 182, 0.2);
    }
    
    .doctor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: white;
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
    
    .animate-fade {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary-light);
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--primary-light);
    }
    
    .timeline-date {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    
    .timeline-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-calendar-alt text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">DÉTAILS DU RENDEZ-VOUS</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Rendez-vous #{{ $appointment->id }}</h1>
            <p class="text-white/60 text-sm">Informations complètes du rendez-vous médical</p>
        </div>
        <div>
            @if($appointment->status == 'pending')
                <span class="status-badge status-pending">
                    <i class="fas fa-clock mr-1"></i> En attente
                </span>
            @elseif($appointment->status == 'confirmed')
                <span class="status-badge status-confirmed">
                    <i class="fas fa-check-circle mr-1"></i> Confirmé
                </span>
            @elseif($appointment->status == 'cancelled')
                <span class="status-badge status-cancelled">
                    <i class="fas fa-times-circle mr-1"></i> Annulé
                </span>
            @elseif($appointment->status == 'completed')
                <span class="status-badge status-completed">
                    <i class="fas fa-check-double mr-1"></i> Terminé
                </span>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Colonne de gauche - Informations Patient -->
    <div class="animate-fade" style="animation-delay: 0.05s">
        <div class="info-card">
            <div class="card-header-custom">
                <div class="flex items-center gap-3">
                    <div class="avatar-large">
                        {{ strtoupper(substr($appointment->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">{{ $appointment->patient->user->name ?? 'N/A' }}</h3>
                        <p class="text-slate-500 text-xs">ID Patient: #{{ $appointment->patient_id }}</p>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-envelope text-slate-400 w-4"></i>
                    Email
                </div>
                <div class="info-value">{{ $appointment->patient->user->email ?? 'Non renseigné' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-phone text-slate-400 w-4"></i>
                    Téléphone
                </div>
                <div class="info-value">{{ $appointment->patient->user->phone ?? 'Non renseigné' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-calendar text-slate-400 w-4"></i>
                    Date naissance
                </div>
                <div class="info-value">{{ $appointment->patient->user->birth_date ? \Carbon\Carbon::parse($appointment->patient->user->birth_date)->format('d/m/Y') : 'Non renseignée' }}</div>
            </div>
            
            @if($appointment->patient->insurance_company)
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-shield-alt text-slate-400 w-4"></i>
                    Mutuelle
                </div>
                <div class="info-value">{{ $appointment->patient->insurance_company }} - {{ $appointment->patient->insurance_number ?? '' }}</div>
            </div>
            @endif
            
            @if($appointment->patient->allergies)
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-allergies text-slate-400 w-4"></i>
                    Allergies
                </div>
                <div class="info-value text-amber-600">{{ $appointment->patient->allergies }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Colonne centrale - Informations Rendez-vous -->
    <div class="animate-fade" style="animation-delay: 0.1s">
        <div class="info-card">
            <div class="card-header-custom">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-calendar-check text-primary-blue mr-2"></i>
                    Informations du rendez-vous
                </h3>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-user-md text-slate-400 w-4"></i>
                    Médecin
                </div>
                <div class="info-value">
                    <div class="flex items-center gap-2">
                        <div class="doctor-avatar">
                            {{ strtoupper(substr($appointment->doctor->user->name ?? 'D', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-500">{{ $appointment->doctor->specialty ?? 'Généraliste' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-calendar-day text-slate-400 w-4"></i>
                    Date
                </div>
                <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('l d F Y') }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-clock text-slate-400 w-4"></i>
                    Heure
                </div>
                <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-hourglass-half text-slate-400 w-4"></i>
                    Durée
                </div>
                <div class="info-value">{{ $appointment->duration }} minutes</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-tag text-slate-400 w-4"></i>
                    Type
                </div>
                <div class="info-value">
                    @if($appointment->type == 'general')
                        <span class="type-badge type-general">📋 Générale</span>
                    @elseif($appointment->type == 'emergency')
                        <span class="type-badge type-emergency">🚨 Urgence</span>
                    @elseif($appointment->type == 'follow_up')
                        <span class="type-badge type-follow_up">📊 Suivi</span>
                    @elseif($appointment->type == 'specialist')
                        <span class="type-badge type-specialist">👨‍⚕️ Spécialiste</span>
                    @else
                        <span class="type-badge">{{ $appointment->type }}</span>
                    @endif
                </div>
            </div>
            
            @if($appointment->reason)
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-sticky-note text-slate-400 w-4"></i>
                    Motif
                </div>
                <div class="info-value">{{ $appointment->reason }}</div>
            </div>
            @endif
            
            @if($appointment->notes)
            <div class="info-row">
                <div class="info-label">
                    <i class="fas fa-comment text-slate-400 w-4"></i>
                    Notes
                </div>
                <div class="info-value">{{ $appointment->notes }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Colonne droite - Actions & Timeline -->
    <div class="animate-fade" style="animation-delay: 0.15s">
        <!-- Actions -->
        <div class="info-card mb-6">
            <div class="card-header-custom">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-bolt text-primary-blue mr-2"></i>
                    Actions
                </h3>
            </div>
            <div class="p-5">
                <div class="action-buttons">
                    @if($appointment->status == 'pending')
                        <a href="{{ route('secretaire.appointments.confirm', $appointment->id) }}" 
                           class="btn-confirm"
                           onclick="return confirm('Confirmer ce rendez-vous ? Le patient recevra une notification.')">
                            <i class="fas fa-check-circle"></i>
                            Confirmer
                        </a>
                        <a href="{{ route('secretaire.appointments.cancel', $appointment->id) }}" 
                           class="btn-cancel"
                           onclick="return confirm('Annuler ce rendez-vous ?')">
                            <i class="fas fa-times-circle"></i>
                            Annuler
                        </a>
                    @elseif($appointment->status == 'confirmed')
                        <a href="{{ route('secretaire.appointments.cancel', $appointment->id) }}" 
                           class="btn-cancel"
                           onclick="return confirm('Annuler ce rendez-vous confirmé ?')">
                            <i class="fas fa-times-circle"></i>
                            Annuler
                        </a>
                    @endif
                    
                    <a href="{{ route('secretaire.appointments.edit', $appointment->id) }}" class="btn-edit">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    
                    <a href="{{ route('secretaire.appointments.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="info-card">
            <div class="card-header-custom">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-history text-primary-blue mr-2"></i>
                    Chronologie
                </h3>
            </div>
            <div class="p-5">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-title">Rendez-vous créé</div>
                        <div class="text-xs text-slate-500">Par {{ $appointment->creator->name ?? 'Système' }}</div>
                    </div>
                    
                    @if($appointment->confirmed_at)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($appointment->confirmed_at)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-title">Rendez-vous confirmé</div>
                        <div class="text-xs text-slate-500">Confirmé par {{ $appointment->confirmedBy->name ?? 'Secrétaire' }}</div>
                    </div>
                    @endif
                    
                    @if($appointment->cancelled_at)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($appointment->cancelled_at)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-title">Rendez-vous annulé</div>
                        <div class="text-xs text-slate-500">Motif: {{ $appointment->cancellation_reason ?? 'Non spécifié' }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Consultation associée (si existante) -->
@if($appointment->consultation)
<div class="info-card mt-6 animate-fade" style="animation-delay: 0.2s">
    <div class="card-header-custom">
        <h3 class="font-bold text-slate-800">
            <i class="fas fa-stethoscope text-primary-blue mr-2"></i>
            Consultation associée
        </h3>
    </div>
    <div class="p-5">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <p class="text-slate-500 text-sm">Date de consultation</p>
                <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($appointment->consultation->consultation_date)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-sm">Diagnostic</p>
                <p class="text-slate-800">{{ Str::limit($appointment->consultation->diagnosis ?? 'Non renseigné', 50) }}</p>
            </div>
            <div>
                <a href="{{ route('consultations.show', $appointment->consultation->id) }}" class="text-primary-blue hover:underline text-sm font-semibold">
                    Voir la consultation <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal d'annulation -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Annuler le rendez-vous</h3>
            <p class="text-slate-500 text-sm mb-4">Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action est irréversible.</p>
            <form id="cancelForm" method="POST">
                @csrf
                <input type="hidden" name="reason" id="cancelReason" value="Annulé par le secrétariat">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Motif d'annulation</label>
                    <textarea name="reason_detail" id="reasonDetail" rows="2" class="w-full border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500" placeholder="Facultatif"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 px-4 py-2 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">Annuler</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCancelModal(appointmentId) {
        const modal = document.getElementById('cancelModal');
        const form = document.getElementById('cancelForm');
        form.action = '/secretaire/appointments/' + appointmentId + '/cancel';
        modal.classList.remove('hidden');
    }
    
    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        modal.classList.add('hidden');
    }
    
    // Récupérer le motif d'annulation avant soumission
    document.getElementById('cancelForm')?.addEventListener('submit', function(e) {
        const reasonDetail = document.getElementById('reasonDetail').value;
        const hiddenReason = document.getElementById('cancelReason');
        if (reasonDetail) {
            hiddenReason.value = reasonDetail;
        }
    });
    
    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('cancelModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });
</script>

@endsection