@extends('layouts.app')

@section('page_title', 'Gestion des rendez-vous')
@section('page_subtitle', 'Planification et suivi des consultations')

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
    
    .appointment-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .appointment-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .patient-avatar {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
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
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }
    
    .type-general { background: #e0f2fe; color: #0284c7; }
    .type-emergency { background: #fef2f2; color: #dc2626; }
    .type-follow_up { background: #fef3c7; color: #d97706; }
    .type-specialist { background: #f3e8ff; color: #9333ea; }
    
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
    
    /* Date picker styles */
    .date-range {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .date-input {
        flex: 1;
        min-width: 150px;
    }
    
    /* Pagination */
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
</style>

<!-- Page Header -->
<div class="page-header animate-fade">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-calendar-alt text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">PLANIFICATION</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Rendez-vous</h1>
            <p class="text-white/60 text-sm">Gestion et suivi des consultations</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('secretaire.appointments.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
                <i class="fas fa-plus-circle"></i>
                <span>Nouveau rendez-vous</span>
            </a>
            <a href="{{ auth()->user()->role == 'doctor' ? route('doctor.calendar') : route('secretaire.calendar') }}" class="inline-flex items-center gap-2 bg-white/10 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-white/20 transition-all">
                <i class="fas fa-calendar-week"></i>
                <span>Voir calendrier</span>
            </a>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stats-card animate-fade" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ $appointments->count() }}</div>
                <div class="text-xs text-slate-400 font-semibold uppercase mt-1">Total RDV</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold text-emerald-600">{{ $appointments->where('status', 'confirmed')->count() }}</div>
                <div class="text-xs text-slate-400 font-semibold uppercase mt-1">Confirmés</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold text-amber-600">{{ $appointments->where('status', 'pending')->count() }}</div>
                <div class="text-xs text-slate-400 font-semibold uppercase mt-1">En attente</div>
            </div>
            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ $appointments->where('date_time', '>=', now())->count() }}</div>
                <div class="text-xs text-slate-400 font-semibold uppercase mt-1">À venir</div>
            </div>
            <div class="stats-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-slate-100 animate-fade" style="animation-delay: 0.25s">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher par patient, médecin..." class="search-input pl-10">
        </div>
        <div class="md:w-48">
            <select id="statusFilter" class="search-input">
                <option value="">Tous les statuts</option>
                <option value="confirmed">✅ Confirmé</option>
                <option value="pending">⏳ En attente</option>
                <option value="cancelled">❌ Annulé</option>
                <option value="completed">✓ Terminé</option>
            </select>
        </div>
        <div class="md:w-48">
            <select id="typeFilter" class="search-input">
                <option value="">Tous les types</option>
                <option value="general">🩺 Générale</option>
                <option value="emergency">🚨 Urgence</option>
                <option value="follow_up">📋 Suivi</option>
                <option value="specialist">👨‍⚕️ Spécialiste</option>
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
                <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Liste des rendez-vous -->
@if($appointments->count() > 0)
    <div class="space-y-4" id="appointmentsList">
        @foreach($appointments as $appointment)
        <div class="appointment-card appointment-item" 
             data-patient="{{ strtolower($appointment->patient->user->name ?? '') }}"
             data-doctor="{{ strtolower($appointment->doctor->user->name ?? '') }}"
             data-status="{{ $appointment->status }}"
             data-type="{{ $appointment->type }}"
             style="animation-delay: {{ 0.3 + ($loop->iteration * 0.02) }}s">
            
            <div class="p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <!-- Patient Info -->
                    <div class="flex items-center gap-4">
                        <div class="patient-avatar">
                            {{ substr($appointment->patient->user->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-800 text-lg">{{ $appointment->patient->user->name ?? 'N/A' }}</h3>
                                <span class="text-xs text-slate-400">ID: #{{ $appointment->patient_id }}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-sm text-slate-500">
                                <span><i class="fas fa-phone-alt mr-1 text-slate-400"></i> {{ $appointment->patient->user->phone ?? 'Non renseigné' }}</span>
                                <span><i class="fas fa-envelope mr-1 text-slate-400"></i> {{ $appointment->patient->user->email ?? 'Non renseigné' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div>
                        @php
                            $statusClass = match($appointment->status) {
                                'confirmed' => 'status-confirmed',
                                'pending' => 'status-pending',
                                'cancelled' => 'status-cancelled',
                                'completed' => 'status-completed',
                                default => ''
                            };
                            $statusLabel = match($appointment->status) {
                                'confirmed' => 'Confirmé',
                                'pending' => 'En attente',
                                'cancelled' => 'Annulé',
                                'completed' => 'Terminé',
                                default => $appointment->status
                            };
                            $typeClass = match($appointment->type) {
                                'general' => 'type-general',
                                'emergency' => 'type-emergency',
                                'follow_up' => 'type-follow_up',
                                'specialist' => 'type-specialist',
                                default => ''
                            };
                            $typeLabel = match($appointment->type) {
                                'general' => 'Générale',
                                'emergency' => 'Urgence',
                                'follow_up' => 'Suivi',
                                'specialist' => 'Spécialiste',
                                default => $appointment->type
                            };
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            <span class="type-badge {{ $typeClass }}">
                                @if($appointment->type == 'general') 🩺
                                @elseif($appointment->type == 'emergency') 🚨
                                @elseif($appointment->type == 'follow_up') 📋
                                @elseif($appointment->type == 'specialist') 👨‍⚕️
                                @endif
                                {{ $typeLabel }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Médecin et Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-primary-blue">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">Médecin</div>
                            <div class="font-semibold text-slate-700">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-500">{{ $appointment->doctor->specialty ?? 'Généraliste' }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-primary-blue">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">Date et heure</div>
                            <div class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($appointment->date_time)->format('d/m/Y') }}</div>
                            <div class="text-xs text-primary-blue">{{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }} ({{ $appointment->duration }} min)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Motif -->
                @if($appointment->reason)
                <div class="mt-3 p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-sticky-note text-slate-400 text-sm mt-0.5"></i>
                        <div>
                            <div class="text-xs text-slate-400">Motif</div>
                            <div class="text-sm text-slate-600">{{ $appointment->reason }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-100">
                    @if($appointment->status == 'pending')
                    <a href="{{ route('secretaire.appointments.confirm', $appointment->id) }}" 
                       class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-sm font-semibold hover:bg-emerald-100 transition-all flex items-center gap-2"
                       onclick="return confirm('Confirmer ce rendez-vous ?')">
                        <i class="fas fa-check-circle"></i> Confirmer
                    </a>
                    @endif
                    
                    @if($appointment->status != 'cancelled' && $appointment->status != 'completed')
                    <a href="{{ route('secretaire.appointments.cancel', $appointment->id) }}" 
                       class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-semibold hover:bg-red-100 transition-all flex items-center gap-2"
                       onclick="return confirm('Annuler ce rendez-vous ?')">
                        <i class="fas fa-times-circle"></i> Annuler
                    </a>
                    @endif
                    
                    <a href="{{ route('secretaire.appointments.show', $appointment->id) }}" 
                       class="btn-action bg-slate-100 text-slate-600 hover:bg-slate-200" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <a href="{{ route('secretaire.appointments.edit', $appointment->id) }}" 
                       class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-100" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <form action="{{ route('secretaire.appointments.destroy', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement ce rendez-vous ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action bg-red-50 text-red-600 hover:bg-red-100" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($appointments, 'links'))
    <div class="mt-8">
        {{ $appointments->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade">
        <div class="empty-state-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucun rendez-vous</h3>
        <p class="text-slate-500 mb-6">Commencez par créer un nouveau rendez-vous</p>
        <a href="{{ route('secretaire.appointments.create') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-plus-circle"></i>
            <span>Prendre un rendez-vous</span>
        </a>
    </div>
@endif

<script>
    // Filtrage des rendez-vous
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    function filterAppointments() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusTerm = statusFilter?.value || '';
        const typeTerm = typeFilter?.value || '';
        
        const items = document.querySelectorAll('.appointment-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const patientName = item.dataset.patient || '';
            const doctorName = item.dataset.doctor || '';
            const status = item.dataset.status || '';
            const type = item.dataset.type || '';
            
            const matchesSearch = searchTerm === '' || patientName.includes(searchTerm) || doctorName.includes(searchTerm);
            const matchesStatus = statusTerm === '' || status === statusTerm;
            const matchesType = typeTerm === '' || type === typeTerm;
            
            if (matchesSearch && matchesStatus && matchesType) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Afficher message si aucun résultat
        let noResultsMsg = document.getElementById('noResultsMsg');
        const container = document.getElementById('appointmentsList');
        
        if (visibleCount === 0 && container) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.className = 'text-center py-12 bg-white rounded-2xl border border-slate-100';
                noResultsMsg.innerHTML = `
                    <div class="empty-state-icon mx-auto">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-600 mb-1">Aucun résultat</h4>
                    <p class="text-slate-400 text-sm">Aucun rendez-vous ne correspond à votre recherche</p>
                `;
                container.parentNode?.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
            if (container) container.style.display = 'none';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
            if (container) container.style.display = 'block';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (typeFilter) typeFilter.value = '';
        filterAppointments();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterAppointments);
    if (statusFilter) statusFilter.addEventListener('change', filterAppointments);
    if (typeFilter) typeFilter.addEventListener('change', filterAppointments);
</script>

@endsection