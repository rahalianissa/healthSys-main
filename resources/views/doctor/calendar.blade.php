@extends('layouts.app')

@section('page_title', 'Mon calendrier')
@section('page_subtitle', 'Gestion des rendez-vous et planning médical')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
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
        --purple: #8B5CF6;
    }

    /* Page Header */
    .calendar-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    
    .calendar-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .calendar-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Doctor Selection Cards */
    .doctor-selection-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .doctor-selection-card:hover {
        transform: translateY(-6px);
        border-color: var(--primary-lighter);
        box-shadow: 0 20px 25px -12px rgba(0, 119, 182, 0.15);
    }
    
    .doctor-selection-card:hover .doctor-avatar {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        transform: scale(1.05);
    }
    
    .doctor-avatar {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-bg), var(--primary-soft));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-blue);
        transition: all 0.3s;
    }
    
    .doctor-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        background: #ecfdf5;
        color: #059669;
    }

    /* Calendar Container */
    .calendar-container {
        background: white;
        border-radius: 28px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .calendar-container:hover {
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.08);
    }

    /* Sidebar Filters */
    .filters-sidebar {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        height: 100%;
    }
    
    .filter-btn {
        width: 100%;
        text-align: left;
        padding: 12px 16px;
        border-radius: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
        cursor: pointer;
        background: transparent;
        border: none;
        font-weight: 500;
        color: #475569;
    }
    
    .filter-btn:hover {
        background: #e2e8f0;
        transform: translateX(4px);
    }
    
    .filter-btn.active {
        background: var(--primary-bg);
        color: var(--primary-blue);
        border-left: 3px solid var(--primary-light);
    }
    
    .filter-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .filter-dot.all { background: #64748b; }
    .filter-dot.pending { background: #f59e0b; }
    .filter-dot.confirmed { background: #10b981; }
    .filter-dot.completed { background: #8b5cf6; }
    .filter-dot.cancelled { background: #ef4444; }
    
    .filter-count {
        margin-left: auto;
        background: #e2e8f0;
        padding: 2px 8px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: 'Inter', sans-serif;
    }
    
    .fc-toolbar-title {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: var(--primary-dark) !important;
    }
    
    .fc-button {
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
        border-radius: 12px !important;
        transition: all 0.2s !important;
        text-transform: capitalize !important;
    }
    
    .fc-button:hover {
        background: var(--primary-bg) !important;
        border-color: var(--primary-lighter) !important;
        color: var(--primary-blue) !important;
    }
    
    .fc-button-primary {
        background: var(--primary-blue) !important;
        border-color: var(--primary-blue) !important;
        color: white !important;
    }
    
    .fc-button-primary:hover {
        background: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }
    
    .fc-day-today {
        background: rgba(202, 240, 248, 0.3) !important;
    }
    
    .fc-event {
        border-radius: 10px !important;
        padding: 4px 8px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        cursor: pointer;
        transition: all 0.2s;
        border: none !important;
    }
    
    .fc-event:hover {
        transform: scale(1.02);
        filter: brightness(0.95);
    }
    
    .fc-event-pending { 
        background: #fef3c7 !important;
        border-left: 4px solid #f59e0b !important;
        color: #92400e !important;
    }
    
    .fc-event-confirmed { 
        background: #d1fae5 !important;
        border-left: 4px solid #10b981 !important;
        color: #065f46 !important;
    }
    
    .fc-event-cancelled { 
        background: #fee2e2 !important;
        border-left: 4px solid #ef4444 !important;
        color: #991b1b !important;
        text-decoration: line-through;
    }
    
    .fc-event-completed { 
        background: #e0e7ff !important;
        border-left: 4px solid #8b5cf6 !important;
        color: #3730a3 !important;
    }
    
    /* Modal Styles */
    .appointment-modal .modal-content {
        border-radius: 28px;
        overflow: hidden;
        border: none;
    }
    
    .appointment-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
        padding: 20px 24px;
        border: none;
    }
    
    .appointment-modal .modal-body {
        padding: 24px;
    }
    
    .appointment-detail-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
    }
    
    .detail-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    /* Animations */
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
    
    /* Empty State */
    .empty-doctors {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: var(--primary-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-icon i {
        font-size: 48px;
        color: var(--primary-blue);
    }
    
    /* Loading Spinner */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 28px;
        z-index: 10;
    }
    
    .spinner-custom {
        width: 50px;
        height: 50px;
        border: 3px solid var(--primary-bg);
        border-top-color: var(--primary-blue);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .calendar-header {
            padding: 20px;
        }
        .fc-toolbar {
            flex-direction: column;
            gap: 12px;
        }
        .doctor-avatar {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }
    }
</style>
@endsection

@section('content')

@php
    $userRole = auth()->user()->role;
    $isSecretary = in_array($userRole, ['secretaire', 'chef_medecine', 'admin']);
    $isDoctor = in_array($userRole, ['doctor', 'medecin', 'docteur']);
    $isPatient = in_array($userRole, ['patient']);
@endphp

<!-- ==================== DOCTOR SELECTION SCREEN (Pour Secrétaires) ==================== -->
@if($isSecretary)
<div id="doctorSelectionScreen" class="animate-fade-up">
    <!-- Header -->
    <div class="calendar-header">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1.5 mb-4">
                <i class="fas fa-user-md text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider uppercase">GESTION DES PLANNINGS</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-2">Sélectionner un Médecin</h1>
            <p class="text-white/70 text-sm">Choisissez un médecin pour gérer son emploi du temps et ses rendez-vous</p>
        </div>
    </div>

    @if($doctors && $doctors->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctors as $doctor)
            <div class="doctor-selection-card" onclick="selectDoctor('{{ $doctor->id }}', '{{ addslashes($doctor->user->name) }}', '{{ $doctor->specialty ?? 'Généraliste' }}')">
                <div class="flex items-start gap-4">
                    <div class="doctor-avatar">
                        {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Dr. {{ $doctor->user->name }}</h3>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $doctor->specialty ?? 'Médecin généraliste' }}</p>
                            </div>
                            <span class="doctor-status">
                                <i class="fas fa-circle text-[8px]"></i>
                                Disponible
                            </span>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3 text-xs text-slate-400">
                                <span><i class="fas fa-calendar-check mr-1"></i> {{ $doctor->appointments->count() }} RDV</span>
                                <span><i class="fas fa-users mr-1"></i> {{ $doctor->consultations->count() }} Consult.</span>
                            </div>
                            <span class="text-primary-blue font-medium text-sm flex items-center gap-1">
                                Voir l'agenda <i class="fas fa-arrow-right text-xs"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-doctors bg-white rounded-2xl border border-slate-100">
            <div class="empty-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">Aucun médecin trouvé</h3>
            <p class="text-slate-500 mb-6">Veuillez contacter l'administrateur pour ajouter des médecins.</p>
            <a href="{{ route('admin.doctors.create') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
                <i class="fas fa-plus-circle"></i>
                <span>Ajouter un médecin</span>
            </a>
        </div>
    @endif
</div>
@endif

<!-- ==================== MAIN CALENDAR CONTAINER ==================== -->
<div id="calendarMainContainer" @if($isSecretary) style="display: none;" @endif>
    
    <!-- Page Header -->
    <div class="calendar-header animate-fade-up">
        <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1.5 mb-3">
                    <i class="fas fa-calendar-alt text-cyan-300 text-xs"></i>
                    <span class="text-white/80 text-xs font-semibold tracking-wider uppercase" id="calendarScope">
                        @if($isDoctor) MON PLANNING @elseif($isPatient) MES RENDEZ-VOUS @else PLANNING MÉDICAL @endif
                    </span>
                </div>
                <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1" id="calendarTitle">
                    @if($isDoctor) Mon Calendrier 
                    @elseif($isPatient) Mes Rendez-vous 
                    @else Calendrier @endif
                </h1>
                <p class="text-white/70 text-sm" id="calendarSubtitle">
                    @if($isDoctor) Gérez vos disponibilités et vos rendez-vous
                    @elseif($isPatient) Consultez et gérez vos rendez-vous médicaux
                    @else Gérez les rendez-vous du cabinet @endif
                </p>
            </div>
            <div class="flex gap-3">
                @if($isSecretary)
                <button onclick="showDoctorSelection()" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Changer de médecin</span>
                </button>
                @endif
                <button onclick="refreshCalendar()" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all" title="Rafraîchir">
                    <i class="fas fa-sync-alt"></i>
                </button>
                @if($isSecretary)
                <a href="{{ route('secretaire.appointments.create') }}" id="newAppointmentBtn" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-lg">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nouveau RDV</span>
                </a>
                @endif
                @if($isDoctor)
                <a href="{{ route('doctor.appointments.create') }}" id="newAppointmentBtnDoctor" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-lg">
                    <i class="fas fa-plus-circle"></i>
                    <span>Prendre RDV</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="filters-sidebar animate-fade-up" style="animation-delay: 0.1s">
                
                @if($isSecretary)
                <div id="selectedDoctorInfo" class="mb-6 p-4 bg-gradient-to-r from-primary-bg to-white rounded-xl border border-primary-soft" style="display: none;">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary-blue flex items-center justify-center text-white text-xl">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <p class="text-xs text-primary-blue font-bold uppercase tracking-wider mb-0.5">Médecin sélectionné</p>
                            <h6 class="font-bold text-slate-800 mb-0" id="currentDoctorName"></h6>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mb-6">
                    <h6 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-filter text-primary-blue"></i>
                        <span>Filtres par statut</span>
                    </h6>
                    
                    <div id="statusFilters">
                        <button class="filter-btn active" data-status="all">
                            <div class="filter-dot all"></div>
                            <span>Tous les rendez-vous</span>
                            <span class="filter-count" id="count-all">0</span>
                        </button>
                        <button class="filter-btn" data-status="pending">
                            <div class="filter-dot pending"></div>
                            <span>En attente</span>
                            <span class="filter-count" id="count-pending">0</span>
                        </button>
                        <button class="filter-btn" data-status="confirmed">
                            <div class="filter-dot confirmed"></div>
                            <span>Confirmés</span>
                            <span class="filter-count" id="count-confirmed">0</span>
                        </button>
                        <button class="filter-btn" data-status="completed">
                            <div class="filter-dot completed"></div>
                            <span>Terminés</span>
                            <span class="filter-count" id="count-completed">0</span>
                        </button>
                        <button class="filter-btn" data-status="cancelled">
                            <div class="filter-dot cancelled"></div>
                            <span>Annulés</span>
                            <span class="filter-count" id="count-cancelled">0</span>
                        </button>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-primary-bg/40 rounded-xl border border-primary-bg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-primary-light mt-0.5"></i>
                        <div>
                            <p class="text-xs font-semibold text-primary-blue mb-1">Information</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Cliquez sur un rendez-vous pour voir les détails complets.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span><i class="far fa-calendar-alt mr-1"></i> Semaine actuelle</span>
                        <span id="currentWeekRange" class="font-medium"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Calendar -->
        <div class="lg:col-span-3">
            <div class="calendar-container animate-fade-up" style="animation-delay: 0.2s">
                <div class="relative p-4 md:p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Rendez-vous -->
<div class="modal fade appointment-modal" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white font-bold text-lg">Détails du rendez-vous</h5>
                        <p class="text-white/60 text-xs mt-0.5">Informations complètes de la consultation</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center py-8">
                    <div class="spinner-custom mx-auto"></div>
                    <p class="mt-4 text-slate-500">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-5 px-6 flex justify-between">
                <button type="button" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-semibold hover:bg-slate-200 transition-all" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Fermer
                </button>
                <div id="modalActions"></div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>
// ==================== VARIABLES GLOBALES ====================
let calendar;
let currentAppointmentId = null;
let currentFilter = 'all';
let selectedDoctorId = null;

const userRole = "{{ auth()->user()->role }}";
const isSecretary = {{ $isSecretary ? 'true' : 'false' }};
const isDoctor = {{ $isDoctor ? 'true' : 'false' }};
const isPatient = {{ $isPatient ? 'true' : 'false' }};

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', function() {
    if (!isSecretary) {
        initCalendar();
    }
    setupFilters();
    updateWeekRange();
});

// ==================== CALENDRIER ====================
function initCalendar(doctorId = null) {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    let eventUrl = '';
    if (isDoctor) {
        eventUrl = '{{ route("doctor.calendar.events") }}';
    } else if (isPatient) {
        eventUrl = '{{ route("patient.calendar.events") }}';
    } else {
        eventUrl = '{{ route("secretaire.calendar.events") }}';
    }
    
    if (doctorId) {
        eventUrl += (eventUrl.includes('?') ? '&' : '?') + 'doctor_id=' + doctorId;
    }

    if (calendar) {
        calendar.destroy();
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: eventUrl,
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00',
        height: 'auto',
        firstDay: 1,
        nowIndicator: true,
        expandRows: true,
        editable: false,
        
        eventDidMount: function(info) {
            const status = info.event.extendedProps.status;
            info.el.classList.add('fc-event-' + status);
            
            // Appliquer le filtre courant
            if (currentFilter !== 'all' && status !== currentFilter) {
                info.el.style.display = 'none';
            } else {
                info.el.style.display = '';
            }
        },

        eventClick: function(info) {
            showAppointmentDetails(info.event);
        },

        datesSet: function() {
            updateWeekRange();
            updateStats();
        },

        loading: function(isLoading) {
            const container = document.querySelector('.calendar-container');
            if (isLoading) {
                let overlay = container.querySelector('.loading-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'loading-overlay';
                    overlay.innerHTML = '<div class="spinner-custom"></div>';
                    container.style.position = 'relative';
                    container.appendChild(overlay);
                }
                overlay.style.display = 'flex';
            } else {
                const overlay = container.querySelector('.loading-overlay');
                if (overlay) overlay.style.display = 'none';
                updateStats();
            }
        }
    });

    calendar.render();
}

// ==================== FILTRES ====================
function setupFilters() {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Retirer la classe active
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Ajouter la classe active
            this.classList.add('active');
            currentFilter = this.dataset.status;
            
            if (calendar) {
                calendar.getEvents().forEach(event => {
                    const eventEl = event.el;
                    if (eventEl) {
                        if (currentFilter === 'all' || event.extendedProps.status === currentFilter) {
                            eventEl.style.display = '';
                        } else {
                            eventEl.style.display = 'none';
                        }
                    }
                });
            }
        });
    });
}

// ==================== STATISTIQUES ====================
function updateStats() {
    if (!calendar) return;
    
    const events = calendar.getEvents();
    let counts = { all: 0, pending: 0, confirmed: 0, completed: 0, cancelled: 0 };
    
    events.forEach(event => {
        const status = event.extendedProps.status;
        counts.all++;
        if (counts.hasOwnProperty(status)) {
            counts[status]++;
        }
    });
    
    document.getElementById('count-all').innerText = counts.all;
    document.getElementById('count-pending').innerText = counts.pending;
    document.getElementById('count-confirmed').innerText = counts.confirmed;
    document.getElementById('count-completed').innerText = counts.completed;
    document.getElementById('count-cancelled').innerText = counts.cancelled;
}

function updateWeekRange() {
    if (!calendar) return;
    
    const view = calendar.view;
    const start = view.activeStart;
    const end = view.activeEnd;
    
    const startStr = start.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
    const endStr = end.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
    
    const rangeSpan = document.getElementById('currentWeekRange');
    if (rangeSpan) {
        rangeSpan.innerText = `${startStr} - ${endStr}`;
    }
}

// ==================== SELECTION MÉDECIN (Pour secrétaires) ====================
function selectDoctor(id, name, specialty) {
    selectedDoctorId = id;
    
    document.getElementById('doctorSelectionScreen').style.display = 'none';
    document.getElementById('calendarMainContainer').style.display = 'block';
    
    const infoDiv = document.getElementById('selectedDoctorInfo');
    if (infoDiv) {
        infoDiv.style.display = 'block';
        document.getElementById('currentDoctorName').innerHTML = `
            <div class="flex items-center gap-2">
                <span>Dr. ${name}</span>
                <span class="text-xs text-primary-blue">(${specialty})</span>
            </div>
        `;
    }
    
    document.getElementById('calendarTitle').innerText = `Agenda : Dr. ${name}`;
    document.getElementById('calendarSubtitle').innerText = specialty || 'Gestion des rendez-vous';
    document.getElementById('calendarScope').innerHTML = `PLANNING DR. ${name.toUpperCase()}`;
    
    const newBtn = document.getElementById('newAppointmentBtn');
    if (newBtn) {
        newBtn.href = "{{ route('secretaire.appointments.create') }}?doctor_id=" + id;
    }
    
    initCalendar(id);
}

function showDoctorSelection() {
    document.getElementById('doctorSelectionScreen').style.display = 'block';
    document.getElementById('calendarMainContainer').style.display = 'none';
}

function refreshCalendar() {
    if (calendar) {
        calendar.refetchEvents();
    }
}

// ==================== DÉTAILS RENDEZ-VOUS ====================
function showAppointmentDetails(event) {
    const props = event.extendedProps;
    currentAppointmentId = event.id;
    
    const statusColors = {
        pending: { bg: '#fef3c7', text: '#92400e', label: 'En attente', icon: 'fa-clock' },
        confirmed: { bg: '#d1fae5', text: '#065f46', label: 'Confirmé', icon: 'fa-check-circle' },
        completed: { bg: '#e0e7ff', text: '#3730a3', label: 'Terminé', icon: 'fa-check-double' },
        cancelled: { bg: '#fee2e2', text: '#991b1b', label: 'Annulé', icon: 'fa-times-circle' }
    };
    
    const statusInfo = statusColors[props.status] || statusColors.pending;
    
    const startDate = new Date(event.start);
    const dateStr = startDate.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    const timeStr = startDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    
    let html = `
        <div class="appointment-detail-card">
            <div class="flex items-center gap-3 mb-4">
                <div class="detail-icon" style="background: ${statusInfo.bg}; color: ${statusInfo.text};">
                    <i class="fas ${statusInfo.icon}"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase font-semibold">Statut</span>
                    <p class="font-bold text-lg mb-0" style="color: ${statusInfo.text}">${statusInfo.label}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex items-center gap-3">
                    <div class="detail-icon bg-blue-100 text-blue-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400">Patient</span>
                        <p class="font-bold text-slate-800 mb-0">${props.patient_name || 'Inconnu'}</p>
                        ${props.phone ? `<p class="text-xs text-slate-500 mt-0.5"><i class="fas fa-phone mr-1"></i>${props.phone}</p>` : ''}
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="detail-icon bg-cyan-100 text-cyan-600">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400">Date & Heure</span>
                        <p class="font-bold text-slate-800 mb-0">${dateStr}</p>
                        <p class="text-xs text-slate-500">${timeStr} • Durée: ${props.duration || 30} min</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="detail-icon bg-teal-100 text-teal-600">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400">Médecin</span>
                        <p class="font-bold text-slate-800 mb-0">Dr. ${props.doctor || 'N/A'}</p>
                        ${props.doctor_specialty ? `<p class="text-xs text-slate-500">${props.doctor_specialty}</p>` : ''}
                    </div>
                </div>
                
                ${props.reason ? `
                <div class="flex items-center gap-3">
                    <div class="detail-icon bg-purple-100 text-purple-600">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400">Motif</span>
                        <p class="text-sm text-slate-700 mb-0">${props.reason}</p>
                    </div>
                </div>
                ` : ''}
            </div>
            
            ${props.notes ? `
            <div class="mt-4 pt-4 border-t border-slate-100">
                <div class="flex items-start gap-3">
                    <i class="fas fa-comment-dots text-slate-400 mt-0.5"></i>
                    <div>
                        <span class="text-xs text-slate-400 uppercase font-semibold">Notes</span>
                        <p class="text-sm text-slate-600 mt-1">${props.notes}</p>
                    </div>
                </div>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = html;
    
    // Actions
    let actionsHtml = '';
    if (isDoctor && props.status !== 'completed' && props.status !== 'cancelled') {
        actionsHtml = `
            <a href="/doctor/consultations/create?appointment=${event.id}" class="px-5 py-2.5 bg-primary-blue text-white rounded-xl font-semibold hover:bg-primary-dark transition-all flex items-center gap-2">
                <i class="fas fa-stethoscope"></i>
                <span>Démarrer la consultation</span>
            </a>
        `;
    } else if (isSecretary && props.status === 'pending') {
        actionsHtml = `
            <a href="/secretaire/appointments/${event.id}/confirm" class="px-5 py-2.5 bg-success text-white rounded-xl font-semibold hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Confirmer</span>
            </a>
            <a href="/secretaire/appointments/${event.id}/cancel" class="px-5 py-2.5 bg-danger text-white rounded-xl font-semibold hover:bg-red-700 transition-all flex items-center gap-2 ml-2">
                <i class="fas fa-times-circle"></i>
                <span>Annuler</span>
            </a>
        `;
    }
    
    document.getElementById('modalActions').innerHTML = actionsHtml;
    
    const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    modal.show();
}

// ==================== ESCAPE HTML ====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
/* Styles additionnels pour FullCalendar */
.fc .fc-timegrid-slot-label-cushion {
    font-size: 11px !important;
    font-weight: 500 !important;
    color: #64748b !important;
}

.fc .fc-daygrid-day-number {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
}

.fc .fc-col-header-cell-cushion {
    font-size: 12px !important;
    font-weight: 700 !important;
    color: #475569 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.fc .fc-toolbar-title {
    font-size: 1.2rem !important;
    font-weight: 800 !important;
}

/* Event tooltip */
.fc-event {
    cursor: pointer;
    transition: transform 0.2s, filter 0.2s;
}

.fc-event:hover {
    transform: scale(1.02);
    filter: brightness(0.97);
    z-index: 10;
}

/* Modal responsive */
@media (max-width: 640px) {
    .appointment-modal .modal-dialog {
        margin: 16px;
    }
    .appointment-detail-card .grid {
        gap: 12px;
    }
}
</style>

@endsection