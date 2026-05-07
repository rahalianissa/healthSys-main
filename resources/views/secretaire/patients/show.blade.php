@extends('layouts.app')

@section('page_title', 'Dossier patient')
@section('page_subtitle', 'Consultation du dossier médical complet')

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
    
    .card-header-custom {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-custom h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
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
        box-shadow: 0 8px 20px rgba(0, 119, 182, 0.2);
    }
    
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-label {
        width: 140px;
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
    
    .badge-insurance {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-cnam {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .badge-mutuelle {
        background: #ecfdf5;
        color: #059669;
    }
    
    .stat-box {
        text-align: center;
        padding: 16px;
        background: #f8fafc;
        border-radius: 16px;
        transition: all 0.3s;
    }
    
    .stat-box:hover {
        background: var(--primary-bg);
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-blue);
    }
    
    .consultation-item {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .consultation-item:hover {
        background: #f8fafc;
        transform: translateX(4px);
    }
    
    .prescription-item {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    
    .prescription-item:hover {
        background: #f8fafc;
    }
    
    .invoice-item {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    
    .invoice-item:hover {
        background: #f8fafc;
    }
    
    .status-paid {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-unpaid {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: scale(1.05);
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
    
    .tab-button {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        background: transparent;
        border: none;
        color: #64748b;
    }
    
    .tab-button.active {
        background: var(--primary-blue);
        color: white;
    }
    
    .tab-button:hover:not(.active) {
        background: #f1f5f9;
        color: var(--primary-blue);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
        animation: fadeInUp 0.3s ease forwards;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-folder-medical text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">DOSSIER MÉDICAL</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">{{ $patient->user->name }}</h1>
            <p class="text-white/60 text-sm">Patient ID: #{{ $patient->id }} | {{ $patient->user->email }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('secretaire.patients.edit', $patient) }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('secretaire.patients.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Colonne gauche - Infos patient -->
    <div class="space-y-6">
        
        <!-- Carte patient -->
        <div class="info-card animate-fade" style="animation-delay: 0.05s">
            <div class="p-6 text-center">
                <div class="patient-avatar mx-auto mb-4">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $patient->user->name }}</h2>
                <p class="text-slate-500 text-sm mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i> 
                    Inscrit le {{ $patient->created_at->format('d/m/Y') }}
                </p>
                <div class="flex justify-center gap-2 mt-3">
                    @if($patient->blood_type)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-tint"></i> {{ $patient->blood_type }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Coordonnées -->
        <div class="info-card animate-fade" style="animation-delay: 0.1s">
            <div class="card-header-custom">
                <i class="fas fa-address-card text-primary-blue text-lg"></i>
                <h3>Coordonnées</h3>
            </div>
            <div class="p-5">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-envelope mr-2 text-slate-400"></i> Email
                    </div>
                    <div class="info-value">{{ $patient->user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-phone mr-2 text-slate-400"></i> Téléphone
                    </div>
                    <div class="info-value">{{ $patient->user->phone ?? 'Non renseigné' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-cake-candles mr-2 text-slate-400"></i> Date naissance
                    </div>
                    <div class="info-value">
                        @if($patient->user->birth_date)
                            {{ \Carbon\Carbon::parse($patient->user->birth_date)->format('d/m/Y') }}
                            ({{ \Carbon\Carbon::parse($patient->user->birth_date)->age }} ans)
                        @else
                            Non renseignée
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-location-dot mr-2 text-slate-400"></i> Adresse
                    </div>
                    <div class="info-value">{{ $patient->user->address ?? 'Non renseignée' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Assurance médicale -->
        <div class="info-card animate-fade" style="animation-delay: 0.15s">
            <div class="card-header-custom">
                <i class="fas fa-shield-alt text-primary-blue text-lg"></i>
                <h3>Couverture médicale</h3>
            </div>
            <div class="p-5">
                <!-- CNAM -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-700">CNAM</span>
                        @if($patient->has_cnam)
                            <span class="badge-insurance badge-cnam">
                                <i class="fas fa-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="badge-insurance bg-slate-100 text-slate-500">
                                <i class="fas fa-times-circle"></i> Inactif
                            </span>
                        @endif
                    </div>
                    @if($patient->has_cnam)
                        <div class="text-sm text-slate-600">
                            <div>Numéro: <span class="font-mono">{{ $patient->cnam_number ?? 'Non renseigné' }}</span></div>
                            @if($patient->cnam_expiry_date)
                            <div>Expire le: {{ \Carbon\Carbon::parse($patient->cnam_expiry_date)->format('d/m/Y') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Mutuelle -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-700">Mutuelle</span>
                        @if($patient->has_mutuelle)
                            <span class="badge-insurance badge-mutuelle">
                                <i class="fas fa-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="badge-insurance bg-slate-100 text-slate-500">
                                <i class="fas fa-times-circle"></i> Inactif
                            </span>
                        @endif
                    </div>
                    @if($patient->has_mutuelle)
                        <div class="text-sm text-slate-600">
                            <div>Compagnie: <span class="font-semibold">{{ $patient->mutuelle_company ?? 'Non renseignée' }}</span></div>
                            <div>Numéro: <span class="font-mono">{{ $patient->mutuelle_number ?? 'Non renseigné' }}</span></div>
                            <div>Taux: <span class="font-semibold">{{ $patient->mutuelle_rate ?? 0 }}%</span></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Contact urgence -->
        @if($patient->emergency_contact || $patient->emergency_phone)
        <div class="info-card animate-fade" style="animation-delay: 0.2s">
            <div class="card-header-custom">
                <i class="fas fa-phone-alt text-primary-blue text-lg"></i>
                <h3>Contact d'urgence</h3>
            </div>
            <div class="p-5">
                <div class="info-row">
                    <div class="info-label">Nom</div>
                    <div class="info-value">{{ $patient->emergency_contact ?? 'Non renseigné' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $patient->emergency_phone ?? 'Non renseigné' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Colonne droite - Historique médical -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade" style="animation-delay: 0.05s">
            <div class="stat-box">
                <div class="stat-number">{{ $patient->appointments->count() }}</div>
                <div class="text-xs text-slate-500 font-medium mt-1">Rendez-vous</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ $patient->consultations->count() }}</div>
                <div class="text-xs text-slate-500 font-medium mt-1">Consultations</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ $patient->prescriptions->count() }}</div>
                <div class="text-xs text-slate-500 font-medium mt-1">Ordonnances</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ number_format($patient->invoices->sum('amount'), 0) }} DT</div>
                <div class="text-xs text-slate-500 font-medium mt-1">Total facturé</div>
            </div>
        </div>
        
        <!-- Tabs navigation -->
        <div class="bg-white rounded-xl border border-slate-100 p-1 animate-fade" style="animation-delay: 0.1s">
            <div class="flex gap-1">
                <button onclick="showTab('consultations')" id="tab-consultations" class="tab-button active">
                    <i class="fas fa-stethoscope mr-2"></i> Consultations
                </button>
                <button onclick="showTab('prescriptions')" id="tab-prescriptions" class="tab-button">
                    <i class="fas fa-prescription mr-2"></i> Ordonnances
                </button>
                <button onclick="showTab('invoices')" id="tab-invoices" class="tab-button">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Factures
                </button>
                <button onclick="showTab('appointments')" id="tab-appointments" class="tab-button">
                    <i class="fas fa-calendar-alt mr-2"></i> Rendez-vous
                </button>
            </div>
        </div>
        
        <!-- Tab Consultations -->
        <div id="consultations-tab" class="tab-content active animate-fade" style="animation-delay: 0.15s">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-stethoscope text-primary-blue text-lg"></i>
                    <h3>Historique des consultations</h3>
                </div>
                <div>
                    @if($patient->consultations->count() > 0)
                        @foreach($patient->consultations->sortByDesc('consultation_date') as $consultation)
                        <div class="consultation-item" onclick="showConsultationDetails({{ $consultation->id }})">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-semibold text-primary-blue">
                                            {{ $consultation->consultation_date->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $consultation->consultation_date->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-700">
                                        <i class="fas fa-user-md mr-1 text-slate-400"></i>
                                        Dr. {{ $consultation->doctor->user->name ?? 'N/A' }}
                                        <span class="text-xs text-slate-400 ml-2">({{ $consultation->doctor->specialty ?? 'Généraliste' }})</span>
                                    </div>
                                    @if($consultation->diagnosis)
                                    <div class="text-sm text-slate-600 mt-2">
                                        <i class="fas fa-clipboard-list mr-1 text-slate-400"></i>
                                        {{ Str::limit($consultation->diagnosis, 60) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn-action bg-primary-bg text-primary-blue" onclick="event.stopPropagation(); showConsultationDetails({{ $consultation->id }})" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <i class="fas fa-stethoscope text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucune consultation enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tab Ordonnances -->
        <div id="prescriptions-tab" class="tab-content animate-fade" style="animation-delay: 0.15s">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-prescription text-primary-blue text-lg"></i>
                    <h3>Ordonnances médicales</h3>
                </div>
                <div>
                    @if($patient->prescriptions->count() > 0)
                        @foreach($patient->prescriptions->sortByDesc('created_at') as $prescription)
                        <div class="prescription-item">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fas fa-file-prescription text-danger text-sm"></i>
                                        <span class="text-sm font-semibold text-slate-700">
                                            Ordonnance du {{ $prescription->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}
                                    </div>
                                    @php
                                        $meds = is_array($prescription->medications) ? $prescription->medications : json_decode($prescription->medications, true);
                                    @endphp
                                    @if($meds && count($meds) > 0)
                                    <div class="mt-2">
                                        @foreach(array_slice($meds, 0, 2) as $med)
                                            <span class="inline-block px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-lg mr-1">
                                                {{ $med['name'] ?? '' }}
                                            </span>
                                        @endforeach
                                        @if(count($meds) > 2)
                                            <span class="text-xs text-slate-400">+{{ count($meds) - 2 }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('prescriptions.pdf', $prescription) }}" class="btn-action bg-red-50 text-red-600" target="_blank" title="Télécharger PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <i class="fas fa-prescription text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucune ordonnance</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tab Factures -->
        <div id="invoices-tab" class="tab-content animate-fade" style="animation-delay: 0.15s">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-file-invoice-dollar text-primary-blue text-lg"></i>
                    <h3>Factures</h3>
                </div>
                <div>
                    @if($patient->invoices->count() > 0)
                        @foreach($patient->invoices->sortByDesc('created_at') as $invoice)
                        <div class="invoice-item">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-semibold text-slate-700">{{ $invoice->invoice_number }}</span>
                                        <span class="text-xs text-slate-400">{{ $invoice->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="font-semibold text-primary-blue">{{ number_format($invoice->amount, 2) }} DT</span>
                                        @php $remaining = $invoice->amount - $invoice->paid_amount; @endphp
                                        @if($remaining > 0)
                                            <span class="text-xs text-danger ml-2">Reste: {{ number_format($remaining, 2) }} DT</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $invoice->status == 'paid' ? 'status-paid' : 'status-unpaid' }}">
                                        {{ $invoice->status == 'paid' ? 'Payée' : ($invoice->status == 'partially_paid' ? 'Partielle' : 'En attente') }}
                                    </span>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn-action bg-primary-bg text-primary-blue" title="Voir facture">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <i class="fas fa-file-invoice-dollar text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucune facture</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tab Rendez-vous -->
        <div id="appointments-tab" class="tab-content animate-fade" style="animation-delay: 0.15s">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-calendar-alt text-primary-blue text-lg"></i>
                    <h3>Historique des rendez-vous</h3>
                </div>
                <div>
                    @if($patient->appointments->count() > 0)
                        @foreach($patient->appointments->sortByDesc('date_time') as $appointment)
                        <div class="consultation-item">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-semibold text-primary-blue">
                                            {{ $appointment->date_time->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $appointment->date_time->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-700">
                                        <i class="fas fa-user-md mr-1 text-slate-400"></i>
                                        Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}
                                    </div>
                                    @if($appointment->reason)
                                    <div class="text-sm text-slate-600 mt-1">
                                        <i class="fas fa-sticky-note mr-1 text-slate-400"></i>
                                        {{ Str::limit($appointment->reason, 50) }}
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    @php
                                        $statusClass = match($appointment->status) {
                                            'confirmed' => 'bg-green-100 text-green-600',
                                            'pending' => 'bg-yellow-100 text-yellow-600',
                                            'cancelled' => 'bg-red-100 text-red-600',
                                            'completed' => 'bg-blue-100 text-blue-600',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                        $statusLabel = match($appointment->status) {
                                            'confirmed' => 'Confirmé',
                                            'pending' => 'En attente',
                                            'cancelled' => 'Annulé',
                                            'completed' => 'Terminé',
                                            default => $appointment->status
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <i class="fas fa-calendar-alt text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucun rendez-vous</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Cacher tous les tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Désactiver tous les boutons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Afficher le tab sélectionné
        document.getElementById(tabName + '-tab').classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
    }
    
    function showConsultationDetails(id) {
        window.location.href = '/consultations/' + id;
    }
</script>

@endsection