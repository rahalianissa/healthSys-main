@extends('layouts.app')

@section('page_title', 'Mes consultations')
@section('page_subtitle', 'Historique des consultations médicales')

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
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .consultation-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        cursor: pointer;
    }
    
    .consultation-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .patient-avatar {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 119, 182, 0.2);
    }
    
    .diagnosis-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        background: var(--primary-bg);
        color: var(--primary-blue);
    }
    
    .treatment-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        background: #f0fdf4;
        color: var(--success);
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
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
    
    .search-input {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 16px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
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
    
    /* Modal styles */
    .modal-consultation {
        background: white;
        border-radius: 24px;
        max-width: 700px;
        margin: 50px auto;
    }
    
    .modal-header-custom {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
        padding: 20px 24px;
        border-radius: 24px 24px 0 0;
    }
    
    .vital-sign {
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px;
        text-align: center;
        transition: all 0.2s;
    }
    
    .vital-sign:hover {
        background: var(--primary-bg);
        transform: translateY(-2px);
    }
    
    .vital-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
    }
    
    .vital-value {
        font-size: 16px;
        font-weight: bold;
        color: var(--primary-dark);
    }
    
    .vital-label {
        font-size: 10px;
        color: #64748b;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-stethoscope text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">HISTORIQUE MÉDICAL</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Mes consultations</h1>
            <p class="text-white/60 text-sm">Historique complet de vos consultations médicales</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center gap-2 bg-white/10 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-white/20 transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Retour au tableau de bord</span>
        </a>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total consultations</div>
                <div class="text-2xl font-bold text-slate-800">{{ $consultations->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-stethoscope"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Patients uniques</div>
                <div class="text-2xl font-bold text-slate-800">{{ $consultations->pluck('patient_id')->unique()->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Avec diagnostic</div>
                <div class="text-2xl font-bold text-slate-800">{{ $consultations->whereNotNull('diagnosis')->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Ce mois</div>
                <div class="text-2xl font-bold text-slate-800">{{ $consultations->where('consultation_date', '>=', now()->startOfMonth())->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche -->
<div class="max-w-2xl mx-auto mb-8 animate-fade-up" style="animation-delay: 0.25s">
    <div class="bg-white rounded-2xl p-3 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un patient ou un diagnostic..." class="search-input pl-10 border-0 bg-slate-50 focus:bg-white">
            </div>
            <button onclick="resetFilters()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 transition-all text-sm font-semibold whitespace-nowrap">
                <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #consultationModal, #consultationModal * {
            visibility: visible;
        }
        #consultationModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }
        .modal-footer, .btn-close, [data-bs-dismiss="modal"] {
            display: none !important;
        }
        .modal-header-custom {
            background: white !important;
            color: black !important;
            border-bottom: 2px solid #eee;
        }
        .modal-header-custom h5, .modal-header-custom p {
            color: black !important;
        }
        .bg-slate-50, .bg-emerald-50, .bg-amber-50 {
            background-color: transparent !important;
            border: 1px solid #eee !important;
        }
    }
</style>

<!-- Liste des consultations -->
@if($consultations->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="consultationsGrid">
        @foreach($consultations as $consultation)
        <div class="consultation-card animate-fade-up consultation-item" 
             style="animation-delay: {{ 0.3 + ($loop->iteration * 0.03) }}s"
             data-patient="{{ strtolower($consultation->patient->user->name ?? '') }}"
             data-diagnosis="{{ strtolower($consultation->diagnosis ?? '') }}"
             onclick="showConsultationDetails({{ $consultation->id }})">
            
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <!-- Avatar Patient -->
                    <div class="patient-avatar">
                        {{ strtoupper(substr($consultation->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    
                    <!-- Infos principales -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ $consultation->patient->user->name ?? 'Patient' }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-slate-400">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $consultation->consultation_date->format('d/m/Y') }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $consultation->consultation_date->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-600">
                                    <i class="fas fa-check-circle mr-1"></i> Terminée
                                </span>
                            </div>
                        </div>
                        
                        <!-- Diagnostic & Traitement -->
                        <div class="mt-3 space-y-2">
                            @if($consultation->diagnosis)
                            <div class="flex items-start gap-2">
                                <i class="fas fa-clipboard-list text-primary-blue text-xs mt-0.5"></i>
                                <div>
                                    <span class="diagnosis-badge">
                                        Diagnostic: {{ Str::limit($consultation->diagnosis, 50) }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            
                            @if($consultation->treatment)
                            <div class="flex items-start gap-2">
                                <i class="fas fa-pills text-emerald-500 text-xs mt-0.5"></i>
                                <div>
                                    <span class="treatment-badge">
                                        Traitement: {{ Str::limit($consultation->treatment, 50) }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Signes vitaux (aperçu) -->
                        @if($consultation->weight || $consultation->blood_pressure || $consultation->temperature)
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-3">
                            @if($consultation->weight)
                            <div class="flex items-center gap-1 text-xs text-slate-500">
                                <i class="fas fa-weight-scale text-primary-blue"></i>
                                <span>{{ $consultation->weight }} kg</span>
                            </div>
                            @endif
                            @if($consultation->blood_pressure)
                            <div class="flex items-center gap-1 text-xs text-slate-500">
                                <i class="fas fa-tachometer-alt text-primary-blue"></i>
                                <span>{{ $consultation->blood_pressure }}</span>
                            </div>
                            @endif
                            @if($consultation->temperature)
                            <div class="flex items-center gap-1 text-xs text-slate-500">
                                <i class="fas fa-thermometer-half text-primary-blue"></i>
                                <span>{{ $consultation->temperature }}°C</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-3 flex justify-end">
                            <button onclick="event.stopPropagation(); showConsultationDetails({{ $consultation->id }})" class="text-primary-blue hover:text-primary-dark text-sm font-medium flex items-center gap-1">
                                Voir détails <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($consultations, 'links'))
    <div class="mt-8">
        {{ $consultations->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100">
        <div class="empty-state-icon">
            <i class="fas fa-stethoscope"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune consultation</h3>
        <p class="text-slate-500 mb-6">Vous n'avez pas encore effectué de consultations</p>
        <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Retour au tableau de bord</span>
        </a>
    </div>
@endif

<!-- Modal Détails Consultation -->
<div class="modal fade" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-consultation">
            <div class="modal-header-custom">
                <div class="flex justify-between items-center">
                    <div>
                        <h5 class="text-white text-xl font-bold mb-0">
                            <i class="fas fa-stethoscope mr-2"></i> Détails de la consultation
                        </h5>
                        <p class="text-white/70 text-sm mt-1 mb-0" id="modalConsultationDate">-</p>
                    </div>
                    <button type="button" class="text-white/70 hover:text-white text-2xl leading-none" data-bs-dismiss="modal">×</button>
                </div>
            </div>
            <div class="modal-body p-6" id="consultationDetails">
                <div class="text-center py-8">
                    <div class="spinner-border text-primary-blue" role="status"></div>
                    <p class="mt-3 text-slate-500">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer border-t border-slate-100 p-4">
                <button type="button" class="px-5 py-2 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-2"></i> Fermer
                </button>
                <button type="button" class="px-5 py-2 bg-primary-blue text-white rounded-xl hover:bg-primary-dark transition-all" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Filtrage des consultations
    const searchInput = document.getElementById('searchInput');
    const consultationsGrid = document.getElementById('consultationsGrid');
    
    function filterConsultations() {
        if (!consultationsGrid) return;
        
        const searchTerm = searchInput?.value.toLowerCase() || '';
        
        const items = document.querySelectorAll('.consultation-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const patient = item.dataset.patient || '';
            const diagnosis = item.dataset.diagnosis || '';
            
            const matchesSearch = searchTerm === '' || patient.includes(searchTerm) || diagnosis.includes(searchTerm);
            
            if (matchesSearch) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Afficher un message si aucun résultat
        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.className = 'col-span-full text-center py-12';
                noResultsMsg.innerHTML = `
                    <div class="empty-state-icon mx-auto">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-600 mb-1">Aucun résultat</h4>
                    <p class="text-slate-400 text-sm">Aucune consultation ne correspond à votre recherche</p>
                `;
                consultationsGrid.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        filterConsultations();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterConsultations);
    
    // Afficher les détails de la consultation
    function showConsultationDetails(id) {
        const modal = new bootstrap.Modal(document.getElementById('consultationModal'));
        const detailsDiv = document.getElementById('consultationDetails');
        const modalDate = document.getElementById('modalConsultationDate');
        
        modal.show();
        
        detailsDiv.innerHTML = `
            <div class="text-center py-8">
                <div class="spinner-border text-primary-blue" role="status"></div>
                <p class="mt-3 text-slate-500">Chargement des détails...</p>
            </div>
        `;
        
        fetch(`/consultations/${id}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Erreur HTTP ' + response.status);
                return response.json();
            })
            .then(data => {
                const date = new Date(data.consultation_date);
                const formattedDate = date.toLocaleDateString('fr-FR');
                const formattedTime = date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                
                modalDate.innerHTML = `<i class="far fa-calendar-alt mr-1"></i> ${formattedDate} à ${formattedTime}`;
                
                let html = `
                    <!-- Patient Info -->
                    <div class="bg-slate-50 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-blue to-primary-lighter flex items-center justify-center text-white font-bold text-lg">
                                ${data.patient?.user?.name ? data.patient.user.name.charAt(0).toUpperCase() : 'P'}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">${data.patient?.user?.name || 'Patient'}</h4>
                                <p class="text-sm text-slate-500">
                                    <i class="fas fa-phone-alt mr-1"></i> ${data.patient?.user?.phone || 'Non renseigné'}
                                </p>
                                <p class="text-sm text-slate-500">
                                    <i class="fas fa-envelope mr-1"></i> ${data.patient?.user?.email || 'Non renseigné'}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Signes vitaux -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                        <div class="vital-sign">
                            <div class="vital-icon">
                                <i class="fas fa-weight-scale text-white text-sm"></i>
                            </div>
                            <div class="vital-value">${data.weight ? data.weight + ' kg' : '—'}</div>
                            <div class="vital-label">Poids</div>
                        </div>
                        <div class="vital-sign">
                            <div class="vital-icon">
                                <i class="fas fa-ruler text-white text-sm"></i>
                            </div>
                            <div class="vital-value">${data.height ? data.height + ' cm' : '—'}</div>
                            <div class="vital-label">Taille</div>
                        </div>
                        <div class="vital-sign">
                            <div class="vital-icon">
                                <i class="fas fa-tachometer-alt text-white text-sm"></i>
                            </div>
                            <div class="vital-value">${data.blood_pressure || '—'}</div>
                            <div class="vital-label">Tension</div>
                        </div>
                        <div class="vital-sign">
                            <div class="vital-icon">
                                <i class="fas fa-thermometer-half text-white text-sm"></i>
                            </div>
                            <div class="vital-value">${data.temperature ? data.temperature + '°C' : '—'}</div>
                            <div class="vital-label">Température</div>
                        </div>
                        <div class="vital-sign">
                            <div class="vital-icon">
                                <i class="fas fa-heart text-white text-sm"></i>
                            </div>
                            <div class="vital-value">${data.heart_rate || '—'}</div>
                            <div class="vital-label">FC</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Symptômes -->
                        <div class="bg-slate-50 rounded-xl p-4">
                            <h6 class="font-semibold text-primary-blue mb-2">
                                <i class="fas fa-notes-medical mr-2"></i>Symptômes
                            </h6>
                            <p class="text-slate-600 text-sm">${data.symptoms || 'Non renseignés'}</p>
                        </div>
                        
                        <!-- Diagnostic -->
                        <div class="bg-emerald-50 rounded-xl p-4">
                            <h6 class="font-semibold text-emerald-600 mb-2">
                                <i class="fas fa-clipboard-list mr-2"></i>Diagnostic
                            </h6>
                            <p class="text-slate-600 text-sm">${data.diagnosis || 'Non renseigné'}</p>
                        </div>
                        
                        <!-- Traitement -->
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h6 class="font-semibold text-amber-600 mb-2">
                                <i class="fas fa-pills mr-2"></i>Traitement
                            </h6>
                            <p class="text-slate-600 text-sm">${data.treatment || 'Non renseigné'}</p>
                        </div>
                        
                        <!-- Notes -->
                        ${data.notes ? `
                        <div class="bg-slate-50 rounded-xl p-4">
                            <h6 class="font-semibold text-primary-blue mb-2">
                                <i class="fas fa-comment-dots mr-2"></i>Notes
                            </h6>
                            <p class="text-slate-600 text-sm">${data.notes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                detailsDiv.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                detailsDiv.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <p>Erreur lors du chargement des détails</p>
                        <button class="btn btn-primary mt-3" onclick="location.reload()">Réessayer</button>
                    </div>
                `;
            });
    }
</script>

<style>
    /* Pagination styles */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .pagination .page-link:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
        color: var(--primary-blue);
    }
    
    .pagination .active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
    }
    
    /* Cursor pointer for consultation cards */
    .consultation-card {
        cursor: pointer;
    }
</style>

@endsection