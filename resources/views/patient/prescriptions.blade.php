@extends('layouts.app')

@section('page_title', 'Mes ordonnances')
@section('page_subtitle', 'Historique de vos prescriptions médicales')

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
    
    .prescription-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .prescription-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .doctor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: white;
    }
    
    .medication-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s;
    }
    
    .medication-item:hover {
        background: var(--primary-bg);
    }
    
    .medication-name {
        font-weight: 700;
        color: var(--primary-dark);
    }
    
    .medication-dosage {
        font-size: 12px;
        color: var(--primary-light);
        font-weight: 600;
    }
    
    .medication-duration {
        font-size: 11px;
        color: #94a3b8;
    }
    
    .status-active {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-expired {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-pdf {
        background: #dc2626;
        color: white;
        transition: all 0.2s;
    }
    
    .btn-pdf:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
    }
    
    .btn-view {
        background: #f1f5f9;
        color: var(--primary-blue);
        transition: all 0.2s;
    }
    
    .btn-view:hover {
        background: var(--primary-bg);
        transform: translateY(-2px);
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
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-prescription text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">MES ORDONNANCES</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Prescriptions médicales</h1>
        <p class="text-white/60 text-sm">Retrouvez toutes vos ordonnances et traitements prescrits</p>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total ordonnances</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $prescriptions->count() }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-bg flex items-center justify-center">
                <i class="fas fa-prescription text-primary-blue text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Médecins consultés</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $prescriptions->pluck('doctor_id')->unique()->count() }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-bg flex items-center justify-center">
                <i class="fas fa-user-md text-primary-blue text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Dernière prescription</div>
                <div class="text-lg font-bold text-primary-dark">
                    @if($prescriptions->count() > 0)
                        {{ $prescriptions->first()->created_at->format('d/m/Y') }}
                    @else
                        --
                    @endif
                </div>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-bg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-primary-blue text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche -->
<div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-slate-100 animate-fade-up" style="animation-delay: 0.2s">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher par médecin, médicament..." class="search-input pl-10">
        </div>
        <div class="md:w-48">
            <select id="statusFilter" class="search-input">
                <option value="">Tous les statuts</option>
                <option value="active">Actives</option>
                <option value="expired">Expirées</option>
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
                <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Liste des ordonnances -->
@if($prescriptions->count() > 0)
    <div class="space-y-5" id="prescriptionsGrid">
        @foreach($prescriptions as $prescription)
        @php
            $isExpired = $prescription->valid_until && \Carbon\Carbon::parse($prescription->valid_until)->isPast();
            $medications = is_array($prescription->medications) ? $prescription->medications : json_decode($prescription->medications, true);
            $medicationCount = is_array($medications) ? count($medications) : 0;
        @endphp
        <div class="prescription-card animate-fade-up prescription-item" style="animation-delay: {{ 0.25 + ($loop->iteration * 0.03) }}s"
             data-doctor="{{ strtolower($prescription->doctor->user->name ?? '') }}"
             data-medications="{{ strtolower(json_encode($medications)) }}"
             data-status="{{ $isExpired ? 'expired' : 'active' }}">
            
            <div class="p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Left Section - Doctor Info -->
                    <div class="flex items-start gap-4">
                        <div class="doctor-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-800 text-lg">Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}</h3>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-primary-bg text-primary-blue">
                                    {{ $prescription->doctor->specialty ?? 'Médecin' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $prescription->created_at->format('d/m/Y') }}</span>
                                @if($prescription->valid_until)
                                <span><i class="far fa-hourglass-half mr-1"></i> Valable jusqu'au {{ \Carbon\Carbon::parse($prescription->valid_until)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Section - Status & Actions -->
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $isExpired ? 'status-expired' : 'status-active' }}">
                            @if($isExpired)
                                <i class="fas fa-clock mr-1"></i> Expirée
                            @else
                                <i class="fas fa-check-circle mr-1"></i> Active
                            @endif
                        </span>
                        <a href="{{ route('prescriptions.pdf', $prescription) }}" target="_blank" class="btn-pdf w-10 h-10 rounded-xl flex items-center justify-center" title="Télécharger PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('prescriptions.show', $prescription) }}" class="btn-view w-10 h-10 rounded-xl flex items-center justify-center" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Medications List -->
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-pills text-primary-light text-sm"></i>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Médicaments prescrits</span>
                        <span class="text-xs bg-slate-100 px-2 py-0.5 rounded-full text-slate-500">{{ $medicationCount }} produit(s)</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach(array_slice($medications, 0, 4) as $med)
                        <div class="medication-item flex items-center justify-between">
                            <div>
                                <div class="medication-name">{{ $med['name'] ?? 'Médicament' }}</div>
                                <div class="medication-dosage">{{ $med['dosage'] ?? '' }}</div>
                            </div>
                            <div class="medication-duration">
                                <i class="fas fa-hourglass-start mr-1"></i> {{ $med['duration'] ?? '' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($medicationCount > 4)
                    <div class="text-center mt-2">
                        <span class="text-xs text-slate-400">+ {{ $medicationCount - 4 }} autre(s) médicament(s)</span>
                    </div>
                    @endif
                </div>
                
                <!-- Instructions -->
                @if($prescription->instructions)
                <div class="mt-4 p-3 rounded-xl bg-amber-50 border-l-4 border-amber-400">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-amber-500 text-sm mt-0.5"></i>
                        <div>
                            <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Instructions</span>
                            <p class="text-sm text-slate-600 mt-1">{{ $prescription->instructions }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($prescriptions, 'links'))
    <div class="mt-8">
        {{ $prescriptions->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade-up">
        <div class="empty-state-icon">
            <i class="fas fa-prescription"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune ordonnance</h3>
        <p class="text-slate-500 mb-6">Vous n'avez pas encore d'ordonnances enregistrées</p>
        <a href="{{ route('patient.appointments') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-calendar-plus"></i>
            <span>Prendre un rendez-vous</span>
        </a>
    </div>
@endif

<script>
    // Filtrage des ordonnances
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterPrescriptions() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusTerm = statusFilter?.value || '';
        
        const prescriptionItems = document.querySelectorAll('.prescription-item');
        let visibleCount = 0;
        
        prescriptionItems.forEach(item => {
            const doctorName = item.dataset.doctor || '';
            const medications = item.dataset.medications || '';
            const itemStatus = item.dataset.status || '';
            
            const matchesSearch = searchTerm === '' || doctorName.includes(searchTerm) || medications.includes(searchTerm);
            const matchesStatus = statusTerm === '' || itemStatus === statusTerm;
            
            if (matchesSearch && matchesStatus) {
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
                const grid = document.getElementById('prescriptionsGrid');
                if (grid) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMsg';
                    noResultsMsg.className = 'text-center py-12';
                    noResultsMsg.innerHTML = `
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-slate-500">Aucune ordonnance trouvée</p>
                    `;
                    grid.parentNode.insertBefore(noResultsMsg, grid.nextSibling);
                }
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        filterPrescriptions();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterPrescriptions);
    if (statusFilter) statusFilter.addEventListener('change', filterPrescriptions);
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
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
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
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@endsection