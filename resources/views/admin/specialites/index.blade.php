@extends('layouts.app')

@section('page_title', 'Gestion des spécialités')
@section('page_subtitle', 'Liste des spécialités médicales')

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
    
    .specialty-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .specialty-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .specialty-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }
    
    .doctor-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-bg);
        color: var(--primary-blue);
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
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
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-stethoscope text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">SPÉCIALITÉS MÉDICALES</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Spécialités</h1>
            <p class="text-white/60 text-sm">Gestion des spécialités médicales du cabinet</p>
        </div>
        <a href="{{ route('admin.specialites.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter une spécialité</span>
        </a>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total spécialités</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $specialites->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-tags"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Médecins total</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $specialites->sum(function($s) { return $s->doctors->count(); }) }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-user-md"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Moyenne / spécialité</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $specialites->count() > 0 ? round($specialites->sum(function($s) { return $s->doctors->count(); }) / $specialites->count(), 1) : 0 }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Actives</div>
                <div class="text-2xl font-bold text-primary-dark">{{ $specialites->where('doctors_count', '>', 0)->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche -->
<div class="bg-white rounded-2xl p-4 mb-6 shadow-sm border border-slate-100 animate-fade-up" style="animation-delay: 0.25s">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher par nom ou description..." class="search-input pl-10">
        </div>
        <div>
            <button onclick="resetFilters()" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
                <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Liste des spécialités -->
@if($specialites->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="specialitesGrid">
        @foreach($specialites as $specialite)
        @php
            $doctorsCount = $specialite->doctors->count();
            $icons = [
                'Cardiologue' => 'fa-heart',
                'Dermatologue' => 'fa-allergies',
                'Pédiatre' => 'fa-baby',
                'Gynécologue' => 'fa-female',
                'Ophtalmologue' => 'fa-eye',
                'Dentiste' => 'fa-tooth',
                'Orthopédiste' => 'fa-bone',
                'Neurologue' => 'fa-brain',
                'Psychiatre' => 'fa-head-side-vr',
                'Généraliste' => 'fa-user-md',
            ];
            $icon = $icons[$specialite->nom] ?? 'fa-stethoscope';
        @endphp
        <div class="specialty-card animate-fade-up specialty-item" style="animation-delay: {{ 0.3 + ($loop->iteration * 0.03) }}s"
             data-name="{{ strtolower($specialite->nom) }}"
             data-description="{{ strtolower($specialite->description ?? '') }}">
            
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="specialty-icon">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ $specialite->nom }}</h3>
                                <div class="mt-1">
                                    <span class="doctor-count">
                                        <i class="fas fa-user-md text-xs"></i>
                                        {{ $doctorsCount }} médecin{{ $doctorsCount > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.specialites.edit', $specialite) }}" class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-100" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.specialites.destroy', $specialite) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement cette spécialité ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action bg-red-50 text-red-600 hover:bg-red-100" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if($specialite->description)
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-slate-500 text-sm line-clamp-2">{{ $specialite->description }}</p>
                        </div>
                        @endif
                        
                        <!-- Liste des médecins (si moins de 3) -->
                        @if($doctorsCount > 0 && $doctorsCount <= 3)
                        <div class="mt-3 flex flex-wrap gap-1">
                            @foreach($specialite->doctors->take(3) as $doctor)
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">
                                <i class="fas fa-user-md mr-1 text-[9px]"></i>
                                Dr. {{ $doctor->user->name }}
                            </span>
                            @endforeach
                        </div>
                        @elseif($doctorsCount > 3)
                        <div class="mt-3">
                            <span class="text-xs text-primary-blue">
                                <i class="fas fa-chevron-right mr-1"></i>
                                +{{ $doctorsCount - 3 }} autres médecins
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($specialites, 'links'))
    <div class="mt-8">
        {{ $specialites->links() }}
    </div>
    @endif
    
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade-up">
        <div class="empty-state-icon">
            <i class="fas fa-stethoscope"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune spécialité</h3>
        <p class="text-slate-500 mb-6">Commencez par ajouter votre première spécialité médicale</p>
        <a href="{{ route('admin.specialites.create') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter une spécialité</span>
        </a>
    </div>
@endif

<script>
    const searchInput = document.getElementById('searchInput');
    const specialitesGrid = document.getElementById('specialitesGrid');
    
    function filterSpecialites() {
        if (!specialitesGrid) return;
        
        const searchTerm = searchInput?.value.toLowerCase() || '';
        
        const items = document.querySelectorAll('.specialty-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const name = item.dataset.name || '';
            const description = item.dataset.description || '';
            
            const matches = searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm);
            
            if (matches) {
                item.style.display = '';
                visibleCount++;
                item.style.animation = 'fadeInUp 0.4s ease forwards';
            } else {
                item.style.display = 'none';
            }
        });
        
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
                    <p class="text-slate-400 text-sm">Aucune spécialité ne correspond à votre recherche</p>
                `;
                specialitesGrid.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        filterSpecialites();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterSpecialites);
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
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

@endsection