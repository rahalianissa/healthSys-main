@extends('layouts.app')

@section('page_title', 'Détails de la consultation')
@section('page_subtitle', 'Fiche de consultation médicale')

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-bg: #CAF0F8;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
    }

    .consultation-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .consultation-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .detail-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.05);
    }

    .section-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--primary-blue);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vital-sign {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    .vital-value {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin: 4px 0;
    }

    .vital-label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
    }

    .vital-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        color: white;
    }

    .content-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border-left: 4px solid var(--primary-blue);
        color: #475569;
        line-height: 1.6;
    }

    .patient-info-box {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .patient-avatar {
        width: 60px;
        height: 60px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 800;
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

<div class="max-w-5xl mx-auto">
    
    <!-- Header -->
    <div class="consultation-header animate-fade-up">
        <div class="flex justify-between items-center flex-wrap gap-6">
            <div class="patient-info-box">
                <div class="patient-avatar">
                    {{ substr($consultation->patient->user->name ?? 'P', 0, 1) }}
                </div>
                <div>
                    <div class="text-white/70 text-xs font-bold uppercase tracking-widest mb-1">Consultation Patient</div>
                    <h1 class="text-2xl font-bold">{{ $consultation->patient->user->name ?? 'N/A' }}</h1>
                    <div class="flex gap-4 mt-2 text-sm text-white/80">
                        <span><i class="fas fa-calendar-alt mr-1"></i> {{ $consultation->consultation_date ? \Carbon\Carbon::parse($consultation->consultation_date)->format('d/m/Y') : 'N/A' }}</span>
                        <span><i class="fas fa-clock mr-1"></i> {{ $consultation->created_at ? $consultation->created_at->format('H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                @if(!$consultation->invoice && (auth()->user()->role == 'doctor' || auth()->user()->role == 'secretaire'))
                <a href="{{ route('invoices.create', ['consultation_id' => $consultation->id, 'patient_id' => $consultation->patient_id, 'amount' => $consultation->doctor->consultation_fee ?? 50]) }}" 
                   class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition-all shadow-lg border-none">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Facturer
                </a>
                @elseif($consultation->invoice)
                <a href="{{ route('invoices.show', $consultation->invoice) }}" 
                   class="px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-bold text-sm transition-all shadow-lg border-none">
                    <i class="fas fa-eye mr-2"></i>Voir Facture
                </a>
                @endif
                <button onclick="window.print()" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-sm transition-all border border-white/20">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
                <a href="{{ url()->previous() }}" class="px-5 py-2.5 bg-white text-primary-blue rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Colonne Gauche : Signes Vitaux + Infos Patient -->
        <div class="space-y-8">
            
            <!-- Signes Vitaux -->
            <div class="detail-card animate-fade-up" style="animation-delay: 0.05s">
                <h3 class="section-title">
                    <i class="fas fa-heartbeat"></i> Constantes Vitales
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="vital-sign">
                        <div class="vital-icon"><i class="fas fa-weight-scale"></i></div>
                        <div class="vital-value">{{ $consultation->weight ?? '—' }} <small class="text-xs">kg</small></div>
                        <div class="vital-label">Poids</div>
                    </div>
                    <div class="vital-sign">
                        <div class="vital-icon" style="background: var(--primary-lighter);"><i class="fas fa-ruler"></i></div>
                        <div class="vital-value">{{ $consultation->height ?? '—' }} <small class="text-xs">cm</small></div>
                        <div class="vital-label">Taille</div>
                    </div>
                    <div class="vital-sign">
                        <div class="vital-icon" style="background: var(--danger);"><i class="fas fa-tachometer-alt"></i></div>
                        <div class="vital-value text-sm">{{ $consultation->blood_pressure ?? '—' }}</div>
                        <div class="vital-label">Tension</div>
                    </div>
                    <div class="vital-sign">
                        <div class="vital-icon" style="background: var(--warning);"><i class="fas fa-thermometer-half"></i></div>
                        <div class="vital-value">{{ $consultation->temperature ?? '—' }} <small class="text-xs">°C</small></div>
                        <div class="vital-label">Température</div>
                    </div>
                    <div class="vital-sign col-span-2">
                        <div class="vital-icon" style="background: #6366F1;"><i class="fas fa-heart"></i></div>
                        <div class="vital-value">{{ $consultation->heart_rate ?? '—' }} <small class="text-xs">bpm</small></div>
                        <div class="vital-label">Fréquence Cardiaque</div>
                    </div>
                </div>
            </div>

            <!-- Informations Patient -->
            <div class="detail-card animate-fade-up" style="animation-delay: 0.1s">
                <h3 class="section-title">
                    <i class="fas fa-user-circle"></i> Informations Patient
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">Téléphone</div>
                        <div class="font-bold text-slate-700">{{ $consultation->patient->user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">Email</div>
                        <div class="font-bold text-slate-700">{{ $consultation->patient->user->email ?? 'Non renseigné' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">Âge</div>
                        <div class="font-bold text-slate-700">
                            @if($consultation->patient && $consultation->patient->user && $consultation->patient->user->birth_date)
                                {{ \Carbon\Carbon::parse($consultation->patient->user->birth_date)->age }} ans
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">Médecin traitant</div>
                        <div class="font-bold text-slate-700">Dr. {{ $consultation->doctor->user->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase mb-1">Spécialité</div>
                        <div class="font-bold text-slate-700">{{ $consultation->doctor->specialty ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Contenu Médical -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Motifs et Symptômes -->
            <div class="detail-card animate-fade-up" style="animation-delay: 0.15s">
                <h3 class="section-title">
                    <i class="fas fa-notes-medical"></i> Motifs et Symptômes
                </h3>
                <div class="content-box">
                    {{ $consultation->symptoms ?: 'Aucun symptôme renseigné.' }}
                </div>
            </div>

            <!-- Diagnostic -->
            <div class="detail-card animate-fade-up" style="animation-delay: 0.2s">
                <h3 class="section-title" style="color: var(--success);">
                    <i class="fas fa-clipboard-check"></i> Diagnostic Final
                </h3>
                <div class="content-box" style="border-left-color: var(--success);">
                    <span class="font-bold text-slate-800">{{ $consultation->diagnosis ?: 'Non renseigné.' }}</span>
                </div>
            </div>

            <!-- Traitement -->
            <div class="detail-card animate-fade-up" style="animation-delay: 0.25s">
                <h3 class="section-title" style="color: var(--warning);">
                    <i class="fas fa-pills"></i> Traitement et Prescriptions
                </h3>
                <div class="content-box" style="border-left-color: var(--warning);">
                    {{ $consultation->treatment ?: 'Aucun traitement renseigné.' }}
                </div>
            </div>

            <!-- Notes additionnelles -->
            @if($consultation->notes)
            <div class="detail-card animate-fade-up" style="animation-delay: 0.3s">
                <h3 class="section-title">
                    <i class="fas fa-comment-dots"></i> Observations
                </h3>
                <div class="content-box" style="border-left-color: #94a3b8; background: #f1f5f9;">
                    {{ $consultation->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Styles d'impression */
    @media print {
        .consultation-header {
            background: #023E8A;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .btn-print, button, a[href*="retour"] {
            display: none !important;
        }
        .detail-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

@endsection