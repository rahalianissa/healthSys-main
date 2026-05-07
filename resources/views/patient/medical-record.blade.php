@extends('layouts.app')

@section('page_title', 'Mon dossier médical')
@section('page_subtitle', 'Historique et informations de santé')

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
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .info-header {
        background: linear-gradient(135deg, var(--primary-bg), white);
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
    }
    
    .info-header i {
        color: var(--primary-light);
        margin-right: 8px;
    }
    
    .patient-avatar {
        width: 100px;
        height: 100px;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 700;
        color: white;
        margin: 0 auto 16px;
        box-shadow: 0 10px 20px rgba(0, 119, 182, 0.2);
    }
    
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-label {
        width: 120px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }
    
    .info-value {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
    }
    
    .vital-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .vital-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
    }
    
    .vital-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--primary-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    
    .vital-icon i {
        font-size: 22px;
        color: var(--primary-blue);
    }
    
    .vital-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .vital-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .consultation-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .consultation-item:hover {
        transform: translateX(6px);
        border-color: var(--primary-lighter);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .prescription-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .prescription-item:hover {
        transform: translateX(4px);
        border-color: var(--primary-lighter);
    }
    
    .medication-tag {
        display: inline-block;
        padding: 4px 10px;
        background: #f1f5f9;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        color: #475569;
        margin: 0 4px 4px 0;
    }
    
    .empty-state {
        text-align: center;
        padding: 50px 20px;
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
    
    .btn-outline-blue {
        background: transparent;
        border: 1px solid var(--primary-light);
        color: var(--primary-light);
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
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
    
    .badge-cnam {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-mutuelle {
        background: #dcfce7;
        color: #166534;
    }
</style>

@php
    $user = auth()->user();
    $patient = $user->patient;
    $consultations = $patient ? $patient->consultations()->orderBy('consultation_date', 'desc')->get() : collect();
    $prescriptions = $patient ? $patient->prescriptions()->orderBy('created_at', 'desc')->get() : collect();
    $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : null;
    $lastConsultation = $consultations->first();
    
    // Calculer l'IMC si poids et taille sont disponibles
    $imc = null;
    $weight = null;
    $height = null;
    
    if ($lastConsultation) {
        $weight = $lastConsultation->weight ?? ($patient->weight ?? null);
        $height = $lastConsultation->height ?? ($patient->height ?? null);
    } else {
        $weight = $patient->weight ?? null;
        $height = $patient->height ?? null;
    }
    
    if ($weight && $height) {
        $heightM = $height / 100;
        $imc = round($weight / ($heightM * $heightM), 1);
    }
@endphp

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-folder-medical text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">MON ESPACE SANTÉ</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Dossier médical</h1>
        <p class="text-white/60 text-sm">Historique complet de votre santé</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- ===== COLONNE GAUCHE : INFORMATIONS PATIENT ===== -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Carte Profil -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.05s">
            <div class="info-header">
                <h3><i class="fas fa-user-circle"></i> Mon profil</h3>
            </div>
            <div class="p-6 text-center">
                <div class="patient-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 class="text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                <p class="text-slate-500 text-sm mt-1">Patient ID: #{{ $patient->id ?? 'N/A' }}</p>
                
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-envelope mr-1"></i> Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-phone mr-1"></i> Téléphone</div>
                        <div class="info-value">{{ $user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-calendar-alt mr-1"></i> Date naissance</div>
                        <div class="info-value">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-chart-line mr-1"></i> Âge</div>
                        <div class="info-value">{{ $age ? $age . ' ans' : 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-tint mr-1"></i> Groupe sanguin</div>
                        <div class="info-value"><span class="font-bold text-red-600">{{ $patient->blood_type ?? 'Non renseigné' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte Assurance -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.1s">
            <div class="info-header">
                <h3><i class="fas fa-shield-alt"></i> Couverture santé</h3>
            </div>
            <div class="p-5">
                @if($patient && $patient->has_cnam)
                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 mb-3">
                    <div>
                        <div class="font-semibold text-blue-800">CNAM</div>
                        <div class="text-xs text-blue-600">N°: {{ $patient->cnam_number ?? 'N/A' }}</div>
                    </div>
                    <span class="badge-cnam text-xs font-semibold px-2 py-1 rounded-full">Actif</span>
                </div>
                @else
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 mb-3">
                    <i class="fas fa-building text-slate-400"></i>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-slate-600">CNAM</div>
                        <div class="text-xs text-slate-400">Non inscrit</div>
                    </div>
                </div>
                @endif
                
                @if($patient && $patient->has_mutuelle)
                <div class="flex items-center justify-between p-3 rounded-xl bg-green-50">
                    <div>
                        <div class="font-semibold text-green-800">Mutuelle</div>
                        <div class="text-xs text-green-600">{{ $patient->mutuelle_company ?? 'N/A' }}</div>
                        <div class="text-xs text-green-600">Taux: {{ $patient->mutuelle_rate ?? 0 }}%</div>
                    </div>
                    <span class="badge-mutuelle text-xs font-semibold px-2 py-1 rounded-full">Actif</span>
                </div>
                @else
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                    <i class="fas fa-handshake text-slate-400"></i>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-slate-600">Mutuelle</div>
                        <div class="text-xs text-slate-400">Non inscrit</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Carte Signes vitaux -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.15s">
            <div class="info-header">
                <h3><i class="fas fa-heartbeat"></i> Signes vitaux</h3>
            </div>
            <div class="p-5">
                @if($lastConsultation || ($weight && $height))
                <div class="grid grid-cols-2 gap-4">
                    <div class="vital-card p-3">
                        <div class="vital-icon w-10 h-10">
                            <i class="fas fa-weight-scale"></i>
                        </div>
                        <div class="vital-value text-lg">{{ $weight ?? '—' }}</div>
                        <div class="vital-label">Poids (kg)</div>
                    </div>
                    <div class="vital-card p-3">
                        <div class="vital-icon w-10 h-10">
                            <i class="fas fa-ruler"></i>
                        </div>
                        <div class="vital-value text-lg">{{ $height ?? '—' }}</div>
                        <div class="vital-label">Taille (cm)</div>
                    </div>
                    <div class="vital-card p-3">
                        <div class="vital-icon w-10 h-10">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="vital-value text-lg">{{ $lastConsultation->blood_pressure ?? '—' }}</div>
                        <div class="vital-label">Tension</div>
                    </div>
                    <div class="vital-card p-3">
                        <div class="vital-icon w-10 h-10">
                            <i class="fas fa-thermometer-half"></i>
                        </div>
                        <div class="vital-value text-lg">{{ $lastConsultation ? ($lastConsultation->temperature ? $lastConsultation->temperature . '°C' : '—') : '—' }}</div>
                        <div class="vital-label">Température</div>
                    </div>
                </div>
                
                @if($imc)
                <div class="mt-4 p-3 rounded-xl text-center" style="background: {{ $imc < 18.5 ? '#fef3c7' : ($imc < 25 ? '#dcfce7' : ($imc < 30 ? '#fed7aa' : '#fee2e2')) }}">
                    <div class="text-sm font-semibold">IMC: {{ $imc }}</div>
                    <div class="text-xs">
                        @if($imc < 18.5) ➖ Insuffisance pondérale
                        @elseif($imc < 25) ✅ Poids normal
                        @elseif($imc < 30) ⚠️ Surpoids
                        @else 🔴 Obésité
                        @endif
                    </div>
                </div>
                @endif
                
                @else
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-heartbeat text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 text-sm">Aucune donnée de consultation disponible</p>
                    <a href="{{ route('patient.appointments') }}" class="inline-flex items-center gap-1 text-primary-blue text-sm font-semibold mt-2 hover:underline">
                        Prendre rendez-vous <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Carte Allergies / Antécédents -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.2s">
            <div class="info-header">
                <h3><i class="fas fa-notes-medical"></i> Antécédents & Allergies</h3>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <div class="text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-allergies text-warning"></i> Allergies
                    </div>
                    <div class="text-sm text-slate-600">
                        {{ $patient->allergies ?? 'Aucune allergie signalée' }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-history text-info"></i> Antécédents médicaux
                    </div>
                    <div class="text-sm text-slate-600">
                        {{ $patient->medical_history ?? 'Aucun antécédent signalé' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== COLONNE DROITE : HISTORIQUE ===== -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Consultations -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.1s">
            <div class="info-header">
                <h3><i class="fas fa-stethoscope"></i> Historique des consultations</h3>
            </div>
            <div class="p-5">
                @if($consultations->count() > 0)
                    @foreach($consultations->take(10) as $consultation)
                    <div class="consultation-item" onclick="showConsultationDetails({{ $consultation->id }})">
                        <div class="flex items-start justify-between flex-wrap gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center">
                                        <i class="fas fa-user-md text-primary-blue"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Dr. {{ $consultation->doctor->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-400">{{ $consultation->doctor->specialty ?? 'Généraliste' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 flex-wrap text-sm mt-2">
                                    <span class="text-slate-500">
                                        <i class="far fa-calendar mr-1"></i> {{ $consultation->consultation_date->format('d/m/Y') }}
                                    </span>
                                    @if($consultation->diagnosis)
                                    <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                                        <i class="fas fa-clipboard-list"></i> Diagnostic
                                    </span>
                                    @endif
                                    @if($consultation->treatment)
                                    <span class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 px-2 py-1 rounded-full">
                                        <i class="fas fa-pills"></i> Traitement
                                    </span>
                                    @endif
                                </div>
                                @if($consultation->diagnosis)
                                <div class="mt-2 text-sm text-slate-600">
                                    <strong>Diagnostic:</strong> {{ Str::limit($consultation->diagnosis, 60) }}
                                </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <i class="fas fa-chevron-right text-slate-300"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($consultations->count() > 10)
                    <div class="text-center mt-4">
                        <button class="btn-outline-blue text-sm" onclick="alert('Voir toutes les consultations')">
                            Voir toutes les consultations ({{ $consultations->count() }})
                        </button>
                    </div>
                    @endif
                @else
                    <div class="empty-state py-8">
                        <div class="empty-state-icon w-16 h-16">
                            <i class="fas fa-stethoscope text-2xl"></i>
                        </div>
                        <p class="text-slate-500">Aucune consultation pour le moment</p>
                        <a href="{{ route('patient.appointments') }}" class="inline-flex items-center gap-1 text-primary-blue text-sm font-semibold mt-2 hover:underline">
                            Prendre rendez-vous <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ordonnances -->
        <div class="info-card animate-fade-up" style="animation-delay: 0.15s">
            <div class="info-header">
                <h3><i class="fas fa-prescription"></i> Mes ordonnances</h3>
            </div>
            <div class="p-5">
                @if($prescriptions->count() > 0)
                    @foreach($prescriptions->take(5) as $prescription)
                    <div class="prescription-item">
                        <div class="flex items-start justify-between flex-wrap gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                                        <i class="fas fa-file-prescription text-red-500"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Ordonnance du {{ $prescription->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-400">Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                @php
                                    $meds = is_array($prescription->medications) ? $prescription->medications : json_decode($prescription->medications, true);
                                @endphp
                                @if(is_array($meds))
                                <div class="mt-2">
                                    @foreach(array_slice($meds, 0, 3) as $med)
                                        <span class="medication-tag">{{ $med['name'] ?? '' }}</span>
                                    @endforeach
                                    @if(count($meds) > 3)
                                        <span class="medication-tag">+{{ count($meds) - 3 }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ url('/prescriptions/'.$prescription->id.'/pdf') }}" target="_blank" class="btn-action bg-red-50 text-red-600 p-2 rounded-xl hover:bg-red-100 transition-all" title="Télécharger PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($prescriptions->count() > 5)
                    <div class="text-center mt-4">
                        <a href="{{ route('patient.prescriptions') }}" class="text-primary-blue text-sm font-semibold hover:underline">
                            Voir toutes mes ordonnances ({{ $prescriptions->count() }})
                        </a>
                    </div>
                    @endif
                @else
                    <div class="empty-state py-8">
                        <div class="empty-state-icon w-16 h-16">
                            <i class="fas fa-prescription text-2xl"></i>
                        </div>
                        <p class="text-slate-500">Aucune ordonnance pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-3 gap-4 animate-fade-up" style="animation-delay: 0.2s">
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
                <div class="text-2xl font-bold text-primary-blue">{{ $consultations->count() }}</div>
                <div class="text-xs text-slate-500 uppercase font-semibold">Consultations</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
                <div class="text-2xl font-bold text-primary-blue">{{ $prescriptions->count() }}</div>
                <div class="text-xs text-slate-500 uppercase font-semibold">Ordonnances</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
                <div class="text-2xl font-bold text-primary-blue">{{ $patient ? $patient->appointments->where('status', 'completed')->count() : 0 }}</div>
                <div class="text-xs text-slate-500 uppercase font-semibold">Visites</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Consultation -->
<div class="modal fade fixed inset-0 bg-black/50 hidden items-center justify-center z-50" id="consultationModal">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-slate-100 p-5 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">
                <i class="fas fa-stethoscope text-primary-blue mr-2"></i>
                Détails de la consultation
            </h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="modalContent">
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-blue"></div>
                <p class="mt-3 text-slate-500">Chargement...</p>
            </div>
        </div>
        <div class="sticky bottom-0 bg-white border-t border-slate-100 p-4 flex justify-end">
            <button onclick="closeModal()" class="px-5 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all">
                Fermer
            </button>
            <button onclick="window.print()" class="ml-3 px-5 py-2 bg-primary-blue text-white rounded-xl hover:bg-primary-dark transition-all">
                <i class="fas fa-print mr-2"></i> Imprimer
            </button>
        </div>
    </div>
</div>

<script>
    let modal = document.getElementById('consultationModal');
    
    function showConsultationDetails(id) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-blue"></div>
                <p class="mt-3 text-slate-500">Chargement des détails...</p>
            </div>
        `;
        
        fetch(`/consultations/${id}/details`)
            .then(response => response.json())
            .then(data => {
                let date = data.consultation_date ? new Date(data.consultation_date) : null;
                let formattedDate = date ? date.toLocaleDateString('fr-FR') : 'N/A';
                
                let html = `
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-semibold text-primary-blue mb-3"><i class="fas fa-info-circle mr-2"></i>Informations générales</h4>
                                <p class="text-sm mb-2"><strong>📅 Date:</strong> ${formattedDate}</p>
                                <p class="text-sm mb-2"><strong>👨‍⚕️ Médecin:</strong> Dr. ${data.doctor?.user?.name || 'N/A'}</p>
                                <p class="text-sm"><strong>🏥 Spécialité:</strong> ${data.doctor?.specialty || 'N/A'}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4">
                                <h4 class="font-semibold text-primary-blue mb-3"><i class="fas fa-heartbeat mr-2"></i>Signes vitaux</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                   <p><strong>⚖️ Poids:</strong> ${data.weight ? data.weight + ' kg' : '—'}</p>
                                    <p><strong>📏 Taille:</strong> ${data.height ? data.height + ' cm' : '—'}</p>
                                    <p><strong>❤️ Tension:</strong> ${data.blood_pressure || '—'}</p>
                                    <p><strong>🌡️ Température:</strong> ${data.temperature ? data.temperature + ' °C' : '—'}</p>
                                    <p><strong>💓 Fréquence:</strong> ${data.heart_rate ? data.heart_rate + ' bpm' : '—'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h4 class="font-semibold text-primary-blue mb-2"><i class="fas fa-notes-medical mr-2"></i>Symptômes</h4>
                            <p class="text-sm">${data.symptoms || 'Non renseignés'}</p>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4">
                            <h4 class="font-semibold text-green-700 mb-2"><i class="fas fa-clipboard-list mr-2"></i>Diagnostic</h4>
                            <p class="text-sm">${data.diagnosis || 'Non renseigné'}</p>
                        </div>
                        
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="font-semibold text-amber-700 mb-2"><i class="fas fa-pills mr-2"></i>Traitement</h4>
                            <p class="text-sm">${data.treatment || 'Non renseigné'}</p>
                        </div>
                        
                        ${data.notes ? `
                        <div class="bg-purple-50 rounded-xl p-4">
                            <h4 class="font-semibold text-purple-700 mb-2"><i class="fas fa-comment-dots mr-2"></i>Notes</h4>
                            <p class="text-sm">${data.notes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                document.getElementById('modalContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-3"></i>
                        <p class="text-slate-600">Erreur lors du chargement des détails</p>
                        <button onclick="showConsultationDetails(${id})" class="mt-4 px-4 py-2 bg-primary-blue text-white rounded-lg">Réessayer</button>
                    </div>
                `;
            });
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Fermer en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>

<style>
    .modal {
        transition: all 0.3s ease;
    }
    
    .modal .bg-white {
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: scale(1.05);
    }
</style>

@endsection