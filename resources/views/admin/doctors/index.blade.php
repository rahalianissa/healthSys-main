@extends('layouts.app')

@section('page_title', 'Gestion des médecins')
@section('page_subtitle', 'Liste et gestion des médecins du cabinet')

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
    
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
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
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .doctor-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .doctor-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .doctor-avatar {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 119, 182, 0.2);
    }
    
    .specialty-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        background: var(--primary-bg);
        color: var(--primary-blue);
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
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-user-md text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">CORPS MÉDICAL</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Médecins</h1>
            <p class="text-white/60 text-sm">Gestion des médecins et spécialistes du cabinet</p>
        </div>
        <a href="{{ route('admin.doctors.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter un médecin</span>
        </a>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total médecins</div>
                <div class="stats-value">{{ $doctors->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-user-md"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Spécialités</div>
                <div class="stats-value">{{ $doctors->pluck('specialty')->unique()->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-stethoscope"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Consultations</div>
                <div class="stats-value">{{ $doctors->sum(function($d) { return $d->appointments->count(); }) }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Honoraire moyen</div>
                <div class="stats-value">{{ number_format($doctors->avg('consultation_fee') ?? 0, 0) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-tag"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche -->
<div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-slate-100 animate-fade-up" style="animation-delay: 0.25s">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher par nom, email, spécialité..." class="search-input pl-10">
        </div>
        <div class="md:w-64">
            <select id="specialtyFilter" class="search-input">
                <option value="">Toutes les spécialités</option>
                @php
                    $specialties = $doctors->pluck('specialty')->unique()->sort();
                @endphp
                @foreach($specialties as $spec)
                    <option value="{{ $spec }}">{{ $spec }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
                <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Liste des médecins -->
@if($doctors->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="doctorsGrid">
        @foreach($doctors as $doctor)
        <div class="doctor-card animate-fade-up doctor-item" style="animation-delay: {{ 0.3 + ($loop->iteration * 0.03) }}s"
             data-name="{{ strtolower($doctor->user->name) }}"
             data-email="{{ strtolower($doctor->user->email) }}"
             data-specialty="{{ strtolower($doctor->specialty) }}">
            
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    <div class="doctor-avatar">
                        {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                    </div>
                    
                    <!-- Infos principales -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ $doctor->user->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="specialty-badge">
                                        <i class="fas fa-stethoscope mr-1 text-xs"></i>
                                        {{ $doctor->specialty }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-400">Honoraire</div>
                                <div class="font-bold text-primary-blue">{{ number_format($doctor->consultation_fee, 0) }} DT</div>
                            </div>
                        </div>
                        
                        <!-- Contact -->
                        <div class="mt-4 space-y-1.5">
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <i class="fas fa-envelope w-4 text-slate-400"></i>
                                <span class="truncate">{{ $doctor->user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <i class="fas fa-phone w-4 text-slate-400"></i>
                                <span>{{ $doctor->user->phone ?? 'Non renseigné' }}</span>
                            </div>
                            @if($doctor->registration_number)
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <i class="fas fa-id-card w-4 text-slate-400"></i>
                                <span class="text-xs">Matricule: {{ $doctor->registration_number }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Statistiques rapides -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="text-center">
                                    <div class="text-xs text-slate-400">RDV</div>
                                    <div class="font-semibold text-slate-700">{{ $doctor->appointments->count() }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-slate-400">Consult.</div>
                                    <div class="font-semibold text-slate-700">{{ $doctor->consultations->count() }}</div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-100" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement ce médecin ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action bg-red-50 text-red-600 hover:bg-red-100" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($doctors, 'links'))
    <div class="mt-8">
        {{ $doctors->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100">
        <div class="empty-state-icon">
            <i class="fas fa-user-md"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucun médecin</h3>
        <p class="text-slate-500 mb-6">Commencez par ajouter votre premier médecin</p>
        <a href="{{ route('admin.doctors.create') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter un médecin</span>
        </a>
    </div>
@endif

<script>
    // Filtrage des médecins
    const searchInput = document.getElementById('searchInput');
    const specialtyFilter = document.getElementById('specialtyFilter');
    const doctorsGrid = document.getElementById('doctorsGrid');
    
    function filterDoctors() {
        if (!doctorsGrid) return;
        
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const specialtyTerm = specialtyFilter?.value.toLowerCase() || '';
        
        const doctorItems = document.querySelectorAll('.doctor-item');
        let visibleCount = 0;
        
        doctorItems.forEach(item => {
            const name = item.dataset.name || '';
            const email = item.dataset.email || '';
            const specialty = item.dataset.specialty || '';
            
            const matchesSearch = searchTerm === '' || name.includes(searchTerm) || email.includes(searchTerm);
            const matchesSpecialty = specialtyTerm === '' || specialty === specialtyTerm;
            
            if (matchesSearch && matchesSpecialty) {
                item.style.display = '';
                visibleCount++;
                // Animation d'apparition
                item.style.animation = 'fadeInUp 0.4s ease forwards';
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
                    <p class="text-slate-400 text-sm">Aucun médecin ne correspond à votre recherche</p>
                `;
                doctorsGrid.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (specialtyFilter) specialtyFilter.value = '';
        filterDoctors();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterDoctors);
    if (specialtyFilter) specialtyFilter.addEventListener('change', filterDoctors);
</script>

<style>
    /* Styles pour la pagination */
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
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@endsection