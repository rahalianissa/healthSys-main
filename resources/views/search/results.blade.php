@extends('layouts.app')

@section('page_title', 'Résultats de recherche')
@section('page_subtitle', 'Résultats pour : "' . $query . '"')

@section('content')

<style>
    .result-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #4f46e5;
    }
    .result-item-link {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.2s;
    }
    .result-item-link:last-child { border-bottom: none; }
    .result-item-link:hover { background: #f8fafc; }
    
    .category-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .category-badge {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 12px;
        border-radius: 10px;
    }
</style>

<div class="max-w-5xl mx-auto">
    
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-search"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Résultats de recherche</h1>
                <p class="text-slate-500 text-sm">Nous avons trouvé <span class="font-bold text-indigo-600">{{ $total }}</span> résultat(s)</p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($total == 0)
        <div class="result-card p-20 text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search-minus text-4xl text-slate-200"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Aucun résultat trouvé</h2>
            <p class="text-slate-400 max-w-sm mx-auto font-medium">Nous n'avons trouvé aucun élément correspondant à "<span class="text-slate-600 font-bold">{{ $query }}</span>".</p>
        </div>
    @else

        <div class="space-y-10">
            @foreach($results as $category => $items)
                @if(count($items) > 0)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                @switch($category)
                                    @case('patients') <i class="fas fa-user-injured mr-2 text-indigo-500"></i> Patients @break
                                    @case('doctors') <i class="fas fa-user-md mr-2 text-emerald-500"></i> Médecins @break
                                    @case('appointments') <i class="fas fa-calendar-check mr-2 text-amber-500"></i> Rendez-vous @break
                                    @case('invoices') <i class="fas fa-file-invoice-dollar mr-2 text-blue-500"></i> Factures @break
                                    @case('consultations') <i class="fas fa-notes-medical mr-2 text-purple-500"></i> Consultations @break
                                    @case('prescriptions') <i class="fas fa-prescription mr-2 text-rose-500"></i> Ordonnances @break
                                    @case('specialites') <i class="fas fa-stethoscope mr-2 text-cyan-500"></i> Spécialités @break
                                    @default {{ ucfirst($category) }}
                                @endswitch
                                ({{ count($items) }})
                            </h3>
                        </div>

                        <div class="result-card">
                            @foreach($items as $item)
                                <a href="{{ $item['url'] ?? '#' }}" class="result-item-link">
                                    <div class="category-icon 
                                        @switch($category)
                                            @case('patients') bg-indigo-50 text-indigo-600 @break
                                            @case('doctors') bg-emerald-50 text-emerald-600 @break
                                            @case('appointments') bg-amber-50 text-amber-600 @break
                                            @case('invoices') bg-blue-50 text-blue-600 @break
                                            @case('consultations') bg-purple-50 text-purple-600 @break
                                            @case('prescriptions') bg-rose-50 text-rose-600 @break
                                            @default bg-slate-50 text-slate-600
                                        @endswitch">
                                        <i class="fas 
                                            @switch($category)
                                                @case('patients') fa-user @break
                                                @case('doctors') fa-user-md @break
                                                @case('appointments') fa-calendar-alt @break
                                                @case('invoices') fa-file-invoice-dollar @break
                                                @case('consultations') fa-notes-medical @break
                                                @case('prescriptions') fa-prescription @break
                                                @case('specialites') fa-stethoscope @break
                                                @default fa-dot-circle
                                            @endswitch"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-800">{{ $item['name'] ?? $item['patient'] ?? $item['doctor'] ?? 'Résultat' }}</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            @if(isset($item['email'])) <i class="fas fa-envelope mr-1 opacity-50"></i> {{ $item['email'] }} @endif
                                            @if(isset($item['phone'])) <span class="mx-2 opacity-20">|</span> <i class="fas fa-phone mr-1 opacity-50"></i> {{ $item['phone'] }} @endif
                                            @if(isset($item['specialty'])) <span class="text-emerald-600 font-bold">{{ $item['specialty'] }}</span> @endif
                                            @if(isset($item['date'])) <i class="far fa-clock mr-1 opacity-50"></i> {{ $item['date'] }} @endif
                                            @if(isset($item['amount'])) <span class="text-blue-600 font-black">{{ $item['amount'] }}</span> @endif
                                            @if(isset($item['status'])) 
                                                <span class="ml-2 uppercase text-[10px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $item['status'] }}</span> 
                                            @endif
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    @endif

</div>

@endsection