@extends('layouts.app')

@section('page_title', 'Salle d\'attente')
@section('page_subtitle', 'Gestion des patients en attente')

{{-- ✅ الحل المؤقت: تعريف المتغير إذا كان غير موجود --}}
@php
    if (!isset($inConsultation)) {
        $inConsultation = null;
    }
    if (!isset($appointments)) {
        $appointments = collect();
    }
@endphp

@section('content')
{{-- باقي الكود --}}

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
        --pending: #F59E0B;
        --waiting: #3B82F6;
        --consulting: #8B5CF6;
        --completed: #10B981;
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
    
    .waiting-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .waiting-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .priority-urgent {
        background: #FEF2F2;
        border-left: 4px solid var(--danger);
    }
    
    .priority-high {
        background: #FFFBEB;
        border-left: 4px solid var(--warning);
    }
    
    .priority-normal {
        border-left: 4px solid var(--primary-light);
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
    
    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
    }
    
    .priority-urgent-badge {
        background: #FEF2F2;
        color: var(--danger);
    }
    
    .priority-high-badge {
        background: #FFFBEB;
        color: var(--warning);
    }
    
    .priority-normal-badge {
        background: var(--primary-bg);
        color: var(--primary-blue);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
    }
    
    .status-waiting {
        background: #EFF6FF;
        color: var(--info);
    }
    
    .status-consulting {
        background: #F3E8FF;
        color: var(--consulting);
    }
    
    .status-completed {
        background: #ECFDF5;
        color: var(--success);
    }
    
    .btn-add {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        transition: all 0.3s ease;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(2, 62, 138, 0.3);
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    
    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
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
    
    .waiting-time {
        font-size: 11px;
        color: #94a3b8;
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
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .blink-animation {
        animation: blink 1s ease-in-out infinite;
    }
    
    .modal-custom {
        border-radius: 24px;
        overflow: hidden;
    }
    
    .modal-custom .modal-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
        color: white;
        border: none;
        padding: 20px 24px;
    }
    
    .modal-custom .modal-body {
        padding: 24px;
    }
    
    .modal-custom .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-clock text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">GESTION DES PATIENTS</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Salle d'attente</h1>
            <p class="text-white/60 text-sm">Gérez les patients en attente et leur priorité</p>
        </div>
        <button type="button" class="btn-add inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg" data-bs-toggle="modal" data-bs-target="#addPatientModal">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter un patient</span>
        </button>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">En attente</div>
                <div class="text-3xl font-bold text-primary-blue">{{ $waiting->count() }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-primary-blue text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">En consultation</div>
                <div class="text-3xl font-bold text-purple-600">{{ $inConsultation ? 1 : 0 }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="fas fa-stethoscope text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Urgents</div>
                <div class="text-3xl font-bold text-danger">{{ $waiting->where('priority', 2)->count() }}</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-danger text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Temps moyen d'attente</div>
                <div class="text-3xl font-bold text-warning">~{{ $waiting->avg(function($w) { return now()->diffInMinutes($w->arrival_time); }) ?? 0 }} min</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="fas fa-clock text-warning text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- File d'attente -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.25s">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-list-ul text-primary-blue mr-2"></i>
                    File d'attente
                    <span class="ml-2 text-sm font-normal text-slate-500">({{ $waiting->count() }} patients)</span>
                </h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($waiting as $item)
                @php
                    $priorityClass = '';
                    $priorityBadgeClass = '';
                    $priorityLabel = '';
                    $priorityIcon = '';
                    
                    if($item->priority == 2) {
                        $priorityClass = 'priority-urgent';
                        $priorityBadgeClass = 'priority-urgent-badge';
                        $priorityLabel = 'Urgent';
                        $priorityIcon = 'fa-bell';
                    } elseif($item->priority == 1) {
                        $priorityClass = 'priority-high';
                        $priorityBadgeClass = 'priority-high-badge';
                        $priorityLabel = 'Prioritaire';
                        $priorityIcon = 'fa-flag';
                    } else {
                        $priorityClass = 'priority-normal';
                        $priorityBadgeClass = 'priority-normal-badge';
                        $priorityLabel = 'Normal';
                        $priorityIcon = 'fa-circle';
                    }
                    
                    $waitingTime = now()->diffInMinutes($item->arrival_time);
                    $waitingText = $waitingTime < 60 ? $waitingTime . ' min' : floor($waitingTime / 60) . 'h ' . ($waitingTime % 60) . 'min';
                @endphp
                
                <div class="waiting-card {{ $priorityClass }} p-4 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="patient-avatar">
                            {{ strtoupper(substr($item->patient->user->name ?? 'P', 0, 1)) }}
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $item->patient->user->name ?? 'N/A' }}</h4>
                                    <div class="flex items-center gap-3 mt-1 flex-wrap">
                                        <span class="priority-badge {{ $priorityBadgeClass }}">
                                            <i class="fas {{ $priorityIcon }} text-xs"></i>
                                            {{ $priorityLabel }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            <i class="far fa-clock mr-1"></i>
                                            Arrivé il y a {{ $waitingText }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('doctor.consultation.start', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-primary-blue text-white rounded-xl text-sm font-semibold hover:bg-primary-dark transition-all">
                                            <i class="fas fa-stethoscope mr-1"></i> Démarrer
                                        </button>
                                    </form>
                                    <form action="{{ route('secretaire.waiting-room.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 border border-red-200 text-red-600 rounded-xl text-sm font-semibold hover:bg-red-50 transition-all" onclick="return confirm('Retirer ce patient de la salle d\'attente ?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($item->doctor)
                            <div class="mt-2 text-xs text-slate-400">
                                <i class="fas fa-user-md mr-1"></i>
                                Médecin: Dr. {{ $item->doctor->user->name ?? 'N/A' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Salle d'attente vide</h3>
                    <p class="text-slate-500">Aucun patient en attente pour le moment</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Consultation en cours -->
    <div>
        @if($inConsultation)
        <div class="bg-white rounded-2xl border border-purple-200 overflow-hidden animate-fade-up" style="animation-delay: 0.3s">
            <div class="px-6 py-4 bg-purple-50 border-b border-purple-100">
                <h3 class="font-bold text-purple-800">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Consultation en cours
                    <span class="ml-2 text-xs font-normal text-purple-600 blink-animation">
                        <i class="fas fa-circle text-[8px]"></i> LIVE
                    </span>
                </h3>
            </div>
            
            <div class="p-5">
                <div class="flex items-center gap-4">
                    <div class="patient-avatar" style="background: linear-gradient(135deg, #8B5CF6, #6D28D9);">
                        {{ strtoupper(substr($inConsultation->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800 text-lg">{{ $inConsultation->patient->user->name ?? 'N/A' }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="status-badge status-consulting">
                                <i class="fas fa-circle text-[8px]"></i>
                                En consultation
                            </span>
                        </div>
                        <div class="mt-3 text-sm text-slate-500">
                            <i class="fas fa-clock mr-1"></i>
                            Début: {{ $inConsultation->start_time ? \Carbon\Carbon::parse($inConsultation->start_time)->format('H:i') : 'En cours' }}
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <form action="{{ route('waiting-room.complete', $inConsultation) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-success text-white rounded-xl font-semibold hover:bg-emerald-700 transition-all">
                            <i class="fas fa-check-circle mr-2"></i>
                            Terminer la consultation
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.3s">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Consultation
                </h3>
            </div>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-md text-slate-400 text-2xl"></i>
                </div>
                <p class="text-slate-500 text-sm">Aucune consultation en cours</p>
                <p class="text-slate-400 text-xs mt-1">Démarrez une consultation depuis la file d'attente</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Ajouter Patient -->
<div class="modal fade" id="addPatientModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-none shadow-2xl rounded-[32px] overflow-hidden">
            <!-- Header Dégradé Médical -->
            <div class="bg-gradient-to-r from-[#03045E] to-[#023E8A] px-8 py-6 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-stethoscope text-cyan-300"></i>
                    </div>
                    <div>
                        <h5 class="text-xl font-bold leading-none">Salle d'attente</h5>
                        <p class="text-white/60 text-xs mt-1 font-medium uppercase tracking-widest">Enregistrement patient</p>
                    </div>
                </div>
                <button type="button" class="text-white/50 hover:text-white transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Formulaire -->
            <form id="waitingRoomForm" action="{{ route('secretaire.waiting-room.add') }}" method="POST" novalidate>
                @csrf
                <div class="p-8">
                    <!-- Titre Interne -->
                    <div class="flex items-center gap-2 mb-8">
                        <i class="fas fa-user-plus text-indigo-600"></i>
                        <h6 class="font-bold text-slate-800 uppercase text-xs tracking-widest">Ajouter un patient à la file</h6>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Patient <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <select name="patient_id" id="patient_id" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all text-slate-700 font-medium appearance-none" required>
                                    <option value="">Sélectionner un patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                            <p class="error-text text-rose-500 text-[11px] font-bold ml-1 hidden"></p>
                        </div>

                        <!-- Médecin -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Médecin <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <i class="fas fa-user-md"></i>
                                </span>
                                <select name="doctor_id" id="doctor_id" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all text-slate-700 font-medium appearance-none" required>
                                    <option value="">Sélectionner un médecin</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                            <p class="error-text text-rose-500 text-[11px] font-bold ml-1 hidden"></p>
                        </div>

                        <!-- Priorité -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Priorité</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <select name="priority" id="priority_select" class="w-full pl-11 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all text-slate-700 font-bold appearance-none">
                                    <option value="0" data-color="#10B981">🟢 Normale</option>
                                    <option value="1" data-color="#F59E0B">🟡 Prioritaire</option>
                                    <option value="2" data-color="#EF4444">🔴 Urgente</option>
                                </select>
                                <div id="priority_dot" class="absolute right-10 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Rendez-vous -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Rendez-vous (optionnel)</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <select name="appointment_id" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all text-slate-700 font-medium appearance-none">
                                    <option value="">Aucun rendez-vous</option>
                                    @if($appointments)
                                        @foreach($appointments as $appointment)
                                            <option value="{{ $appointment->id }}">
                                                {{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }} - {{ $appointment->patient->user->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#03045E] to-[#023E8A] text-white rounded-xl font-bold text-sm shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter à la file</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div id="successToast" class="fixed bottom-6 right-6 z-[9999] animate-fade-up">
    <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <p class="font-bold text-sm">Opération réussie</p>
            <p class="text-xs text-white/80">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

<script>
    // Add patient to waiting room component
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('waitingRoomForm');
        const prioritySelect = document.getElementById('priority_select');
        const priorityDot = document.getElementById('priority_dot');

        // Animation Pastille Priorité
        prioritySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            priorityDot.style.backgroundColor = color;
            priorityDot.style.boxShadow = `0 0 8px ${color}80`;
        });

        // Validation Front-end
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const patientId = document.getElementById('patient_id');
            const doctorId = document.getElementById('doctor_id');

            // Reset errors
            document.querySelectorAll('.error-text').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('select').forEach(el => el.classList.remove('border-rose-500', 'bg-rose-50'));

            if (!patientId.value) {
                showError(patientId, 'Veuillez sélectionner un patient');
                isValid = false;
            }

            if (!doctorId.value) {
                showError(doctorId, 'Veuillez sélectionner un médecin');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function showError(element, message) {
            element.classList.add('border-rose-500', 'bg-rose-50');
            const errorText = element.closest('.space-y-2').querySelector('.error-text');
            errorText.innerText = message;
            errorText.classList.remove('hidden');
        }

        // Auto-hide toast
        const successToast = document.getElementById('successToast');
        if (successToast) {
            setTimeout(() => {
                successToast.classList.add('opacity-0', 'translate-y-4');
                successToast.style.transition = 'all 0.5s ease';
                setTimeout(() => successToast.remove(), 500);
            }, 4000);
        }
    });
</script>

@endsection