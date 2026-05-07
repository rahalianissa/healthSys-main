<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HealthSys') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind Engine -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ===== GLOBAL PREFERENCES ===== */
        :root {
            --sidebar-bg: linear-gradient(180deg, #03045E 0%, #023E8A 100%);
            --content-bg: #f5f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e5e7eb;
        }

        .dark-mode {
            --content-bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        body { 
            background-color: var(--content-bg) !important; 
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #topbar { background-color: var(--card-bg) !important; border-bottom-color: var(--border-color) !important; }
        #content { background-color: var(--content-bg) !important; }
        .topbar-title { color: var(--text-main) !important; }
        .topbar-sub { color: var(--text-muted) !important; }

        /* Compact Mode */
        .compact-mode #content { padding: 16px !important; }
        .compact-mode .stats-card, .compact-mode .chart-card { padding: 16px !important; }
        .compact-mode .invoice-table td { padding: 8px 16px !important; }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f9; display: flex; }
        [x-cloak] { display: none !important; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        /* ===== SIDEBAR - تصميم أزرق متناسق ===== */
        #sidebar {
            width: 280px;
            min-width: 280px;
            background: linear-gradient(180deg, #03045E 0%, #023E8A 100%);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: #023E8A; }
        #sidebar::-webkit-scrollbar-thumb { background: #00B4D8; border-radius: 4px; }

        /* Logo */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #00B4D8, #48CAE4);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 180, 216, 0.3);
        }
        .logo-text { 
            font-size: 20px; 
            font-weight: 800; 
            color: #ffffff; 
            letter-spacing: -0.5px; 
        }
        .logo-text span { color: #00B4D8; }
        .logo-sub { 
            font-size: 10px; 
            color: #90E0EF; 
            font-weight: 500; 
            margin-top: 2px; 
            letter-spacing: 0.3px;
        }

        /* Navigation */
        .nav-section {
            padding: 20px 16px 8px;
        }
        .nav-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #90E0EF;
            padding: 0 12px;
            margin-bottom: 12px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #CAF0F8;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }
        .nav-item i { 
            width: 20px; 
            text-align: center; 
            font-size: 16px; 
            color: #90E0EF;
            transition: color 0.2s;
        }
        .nav-item:hover { 
            background: rgba(255, 255, 255, 0.1); 
            color: #ffffff; 
        }
        .nav-item:hover i { 
            color: #00B4D8; 
        }
        .nav-item.active { 
            background: rgba(0, 180, 216, 0.2); 
            color: #ffffff; 
            font-weight: 600; 
        }
        .nav-item.active i { 
            color: #00B4D8; 
        }

        /* User Card */
        .sidebar-user {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 12px 14px;
        }
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #00B4D8, #48CAE4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 180, 216, 0.2);
        }
        .user-name { 
            font-size: 14px; 
            font-weight: 700; 
            color: #ffffff; 
            line-height: 1.2; 
        }
        .user-role { 
            font-size: 11px; 
            color: #90E0EF; 
            margin-top: 3px; 
            font-weight: 500;
        }
        .logout-btn {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            color: #ff6b6b;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover { 
            background: rgba(255, 107, 107, 0.2); 
            color: #ff8888;
        }

        /* ===== MAIN CONTENT ===== */
        #main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        /* Top Bar */
        #topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .topbar-title { 
            font-size: 18px; 
            font-weight: 700; 
            color: #0f172a; 
        }
        .topbar-sub { 
            font-size: 12px; 
            color: #64748b; 
            margin-top: 2px; 
        }

        .topbar-right { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }
        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            text-decoration: none;
        }
        .icon-btn:hover { 
            background: #eef2ff; 
            color: #023E8A; 
            border-color: #cbd5e1;
        }
        .notif-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid white;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .date-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
        }

        /* ===== GLOBAL SEARCH ===== */
        .search-container {
            flex: 1;
            max-width: 450px;
            margin: 0 20px;
            position: relative;
        }
        .search-wrapper {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 16px;
            height: 42px;
            transition: all 0.2s;
        }
        .search-wrapper:focus-within {
            background: white;
            border-color: #00B4D8;
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.1);
        }
        .search-input-global {
            background: transparent;
            border: none;
            flex: 1;
            padding: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            outline: none;
        }
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 8px;
            z-index: 1000;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            transition: all 0.2s;
            border-bottom: 1px solid #f1f5f9;
        }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: #f8fafc; }
        .result-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Content area */
        #content { 
            flex: 1; 
            overflow-y: auto; 
            padding: 28px; 
            background: #f8fafc;
        }
        #content::-webkit-scrollbar { width: 6px; }
        #content::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 3px; }
        #content::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                height: 100vh;
            }
            #sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            }
            .mobile-menu-btn {
                display: flex !important;
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1001;
                background: #023E8A;
                border: none;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                color: white;
                font-size: 20px;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(2, 62, 138, 0.3);
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
    </style>
    <script>
        // Apply preferences immediately to avoid flickering
        (function() {
            const saved = localStorage.getItem('healthsys_settings');
            if (saved) {
                const settings = JSON.parse(saved);
                if (settings.darkMode) document.documentElement.classList.add('dark-mode');
                if (settings.compact) document.documentElement.classList.add('compact-mode');
            }
        })();
    </script>
    @yield('styles')
</head>
<body class="font-sans text-slate-900 antialiased h-full">

<!-- Mobile Menu Toggle Button -->
<button class="mobile-menu-btn" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- ===== SIDEBAR ===== -->
<aside id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fa-solid fa-heart-pulse" style="color:white;font-size:18px;"></i>
        </div>
        <div>
            <div class="logo-text">Health<span>Sys</span></div>
            <div class="logo-sub">Gestion médicale</div>
        </div>
    </div>

    @php $role = auth()->user()->role; @endphp

    <!-- Main Navigation -->
    <div class="nav-section">
        <div class="nav-label">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Tableau de bord
        </a>

        @if($role === 'chef_medecine')
            <a href="{{ route('admin.doctors.index') }}"
               class="nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-doctor"></i> Médecins
            </a>
            <a href="{{ route('secretaire.patients.index') }}"
               class="nav-item {{ request()->routeIs('secretaire.patients.*') ? 'active' : '' }}">
                <i class="fa-solid fa-hospital-user"></i> Patients
            </a>
            <a href="{{ route('admin.specialites.index') }}"
               class="nav-item {{ request()->routeIs('admin.specialites.*') ? 'active' : '' }}">
                <i class="fa-solid fa-stethoscope"></i> Spécialités
            </a>
            <a href="{{ route('admin.departements.index') }}"
               class="nav-item {{ request()->routeIs('admin.departements.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-columns"></i> Départements
            </a>
            <a href="{{ route('admin.secretaries.index') }}"
               class="nav-item {{ request()->routeIs('admin.secretaries.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> Secrétaires
            </a>

        @elseif($role === 'doctor')
            <a href="{{ route('doctor.waiting-room') }}"
               class="nav-item {{ request()->routeIs('doctor.waiting-room') ? 'active' : '' }}">
                <i class="fa-solid fa-clock"></i> Salle d'attente
            </a>
            <a href="{{ route('doctor.consultations') }}"
               class="nav-item {{ request()->routeIs('doctor.consultations*') ? 'active' : '' }}">
                <i class="fa-solid fa-notes-medical"></i> Consultations
            </a>
            <a href="{{ route('doctor.patients') }}"
               class="nav-item {{ request()->routeIs('doctor.patients*') ? 'active' : '' }}">
                <i class="fa-solid fa-hospital-user"></i> Mes patients
            </a>
            <a href="{{ route('doctor.calendar') }}"
               class="nav-item {{ request()->routeIs('doctor.calendar') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-alt"></i> Calendrier
            </a>

        @elseif($role === 'secretaire' || $role === 'secretary')
            <a href="{{ route('secretaire.appointments.index') }}"
               class="nav-item {{ request()->routeIs('secretaire.appointments*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> Rendez-vous
            </a>
            <a href="{{ route('secretaire.calendar') }}"
               class="nav-item {{ request()->routeIs('secretaire.calendar') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-alt"></i> Calendrier
            </a>
            <a href="{{ route('secretaire.patients.index') }}"
               class="nav-item {{ request()->routeIs('secretaire.patients*') ? 'active' : '' }}">
                <i class="fa-solid fa-hospital-user"></i> Patients
            </a>
            <a href="{{ route('secretaire.waiting-room') }}"
               class="nav-item {{ request()->routeIs('secretaire.waiting-room') ? 'active' : '' }}">
                <i class="fa-solid fa-clock"></i> Salle d'attente
            </a>
            <a href="{{ route('secretaire.comptabilite') }}"
               class="nav-item {{ request()->routeIs('secretaire.comptabilite*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Comptabilité
            </a>

        @elseif($role === 'patient')
            <a href="{{ route('patient.appointments') }}"
               class="nav-item {{ request()->routeIs('patient.appointments*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-day"></i> Mes rendez-vous
            </a>
            <a href="{{ route('patient.medical-record') }}"
               class="nav-item {{ request()->routeIs('patient.medical-record') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-medical"></i> Dossier médical
            </a>
            <a href="{{ route('patient.prescriptions') }}"
               class="nav-item {{ request()->routeIs('patient.prescriptions') ? 'active' : '' }}">
                <i class="fa-solid fa-prescription"></i> Ordonnances
            </a>
            <a href="{{ route('patient.invoices') }}"
               class="nav-item {{ request()->routeIs('patient.invoices') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> Factures
            </a>
        @endif
    </div>

    <!-- Account Navigation -->
    <div class="nav-section">
        <div class="nav-label">Compte</div>
        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear"></i> Mon profil
        </a>
        <a href="{{ route('settings') }}"
           class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
            <i class="fa-solid fa-sliders"></i> Paramètres
        </a>
    </div>

    <!-- User Card -->
    <div class="sidebar-user">
        <div class="user-card">
            <div class="user-avatar" id="sidebar-avatar-container">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/avatars/'.auth()->user()->avatar) }}" class="w-full h-full object-cover rounded-xl shadow-inner">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">
                    @if($role === 'chef_medecine') 👨‍⚕️ Chef de médecine
                    @elseif($role === 'doctor') 👨‍⚕️ Médecin
                    @elseif($role === 'secretaire') 📋 Secrétaire
                    @else 👤 Patient @endif
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Déconnexion">
                    <i class="fa-solid fa-right-from-bracket fa-sm"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<div id="main-wrap">
    <!-- Top Bar -->
    <header id="topbar">
        <div class="flex items-center">
            <div>
                <div class="topbar-title">@yield('page_title', 'Tableau de bord')</div>
                <div class="topbar-sub">@yield('page_subtitle', '')</div>
            </div>
        </div>

        <!-- Search Bar Global -->
        <div class="search-container hidden md:block" x-data="{ 
            query: '', 
            results: [], 
            showResults: false,
            role: '{{ auth()->user()->role }}',
            search() {
                if(this.query.length < 2) {
                    this.results = [];
                    this.showResults = false;
                    return;
                }
                fetch(`/search/autocomplete?q=${this.query}`)
                    .then(res => res.json())
                    .then(data => {
                        this.results = data;
                        this.showResults = true;
                    });
            }
        }">
            <div class="search-wrapper">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" 
                       x-model="query" 
                       @keyup.debounce.300ms="search()" 
                       @click.away="showResults = false"
                       @focus="if(results.length > 0) showResults = true"
                       class="search-input-global" 
                       :placeholder="role === 'doctor' ? 'Rechercher un de mes patients...' : 
                                    (role === 'patient' ? 'Rechercher un RDV, ordonnance...' : 'Rechercher partout...')">
            </div>

            <!-- Autocomplete Results -->
            <div class="search-results-dropdown" x-show="showResults" x-cloak x-transition>
                <div class="p-2 border-b border-slate-50 bg-slate-50/50">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-2">Suggestions suggérées</span>
                </div>
                <template x-for="result in results" :key="result.label + result.url">
                    <a :href="result.url" class="search-result-item">
                        <div class="result-icon">
                            <i :class="result.icon || 'fas fa-dot-circle'"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-slate-800 truncate" x-text="result.label"></div>
                            <div class="text-[11px] text-slate-400 font-medium truncate" x-text="result.subtitle"></div>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                    </a>
                </template>
                <template x-if="results.length === 0 && query.length >= 2">
                    <div class="p-4 text-center text-sm text-slate-400">Aucun résultat pour "<span x-text="query"></span>"</div>
                </template>
                <a x-show="query.length >= 2" :href="`/search?q=${query}`" class="block p-3 text-center text-xs font-black text-indigo-600 bg-indigo-50/50 hover:bg-indigo-100 transition-colors uppercase tracking-widest border-t border-slate-50">
                    Résultats complets <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="topbar-right">
            <!-- // Notification panel - no redirect -->
            <!-- Notification Dropdown -->
            <div x-data="{ 
                open: false, 
                unreadCount: {{ auth()->user()->unreadNotifications->count() }},
                notifications: {{ auth()->user()->notifications()->latest()->take(10)->get()->map(function($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['title'] ?? 'Notification',
                        'message' => $n->data['message'] ?? '',
                        'time' => $n->created_at->diffForHumans(),
                        'unread' => is_null($n->read_at),
                        'type' => $n->data['type'] ?? 'info',
                        'icon' => $n->data['icon'] ?? 'fa-bell'
                    ];
                })->toJson() }},
                markAllRead() {
                    fetch('{{ route('notifications.mark-all') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.notifications.forEach(n => n.unread = false);
                        this.unreadCount = 0;
                    });
                },
                markRead(id) {
                    let n = this.notifications.find(n => n.id === id);
                    if(n && n.unread) {
                        fetch(`/notifications/${id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        }).then(() => {
                            n.unread = false;
                            this.unreadCount--;
                        });
                    }
                }
            }" class="relative">
                
                <!-- Bell Icon & Badge -->
                <button @click="open = !open" class="icon-btn focus:outline-none transition-transform active:scale-90" title="Notifications">
                    <i class="fa-solid fa-bell" :class="open ? 'text-indigo-600' : ''" style="font-size:15px;"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-2 right-2 w-4 h-4 bg-rose-500 border-2 border-white rounded-full flex items-center justify-center">
                            <span class="text-[9px] font-black text-white" x-text="unreadCount"></span>
                        </span>
                    </template>
                </button>

                <!-- Panel Dropdown -->
                <!-- // Click outside closes dropdowns -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                     @click.away="open = false"
                     class="absolute right-0 mt-4 w-[360px] max-sm:w-[90vw] bg-white rounded-[20px] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 z-[100] overflow-hidden"
                     style="display: none;">
                    
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                        <h3 class="font-extrabold text-slate-800 text-lg tracking-tight">Notifications</h3>
                        <button @click="markAllRead()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Tout marquer comme lu</button>
                    </div>

                    <!-- List container -->
                    <div class="max-h-[420px] overflow-y-auto custom-scrollbar">
                        <template x-if="notifications.length === 0">
                            <div class="p-10 text-center">
                                <i class="fa-solid fa-bell-slash text-slate-200 text-4xl mb-3"></i>
                                <p class="text-slate-400 text-sm font-medium">Aucune notification</p>
                            </div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <div @click="markRead(notif.id)" 
                                 class="px-6 py-4 border-b border-slate-50 flex gap-4 transition-all hover:bg-slate-50 cursor-pointer relative"
                                 :class="notif.unread ? 'bg-indigo-50/30' : ''">
                                
                                <!-- Indicator Dot -->
                                <template x-if="notif.unread">
                                    <div class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-indigo-500 rounded-full shadow-[0_0_8px_rgba(79,70,229,0.6)]"></div>
                                </template>

                                <!-- Avatar / Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm"
                                         :class="{
                                            'bg-gradient-to-tr from-indigo-500 to-blue-500': notif.type === 'info' || notif.type === 'edit',
                                            'bg-gradient-to-tr from-amber-400 to-orange-500': notif.type === 'warning' || notif.type === 'comment',
                                            'bg-gradient-to-tr from-emerald-400 to-teal-500': notif.type === 'success' || notif.type === 'invite',
                                            'bg-gradient-to-tr from-rose-400 to-pink-500': notif.type === 'error',
                                            'bg-gradient-to-tr from-slate-400 to-slate-600': notif.type === 'file'
                                         }">
                                        <i class="fas" :class="notif.icon"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] leading-snug text-slate-600">
                                        <span class="font-bold text-slate-900" x-text="notif.title"></span>
                                        <span x-text="notif.message"></span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-1 font-medium" x-text="notif.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="p-4 bg-slate-50/50 text-center">
                        <a href="{{ route('notifications') }}" class="text-[11px] font-black text-slate-400 hover:text-indigo-600 transition-all uppercase tracking-widest">Voir tout l'historique</a>
                    </div>
                </div>
            </div>

            <!-- // Profile circle with dropdown -->
            <!-- User Profile Dropdown -->
            <div x-data="{ open: false }" class="relative ml-2">
                <button @click="open = !open" class="flex items-center focus:outline-none transition-transform active:scale-95">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 border-2 border-white shadow-md flex items-center justify-center text-white font-bold text-sm overflow-hidden" id="navbar-avatar-container">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/avatars/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                        @endif
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <!-- // Click outside closes dropdowns -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                     @click.away="open = false"
                     class="absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[100] py-2 overflow-hidden"
                     style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-slate-50">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Connecté en tant que</p>
                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-user-circle opacity-50"></i> Mon profil
                    </a>
                    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-gear opacity-50"></i> Paramètres
                    </a>
                    
                    <div class="border-t border-slate-50 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 transition-all text-left">
                                <i class="fa-solid fa-right-from-bracket opacity-50"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="date-chip">
                <i class="fa-regular fa-calendar" style="color:#0077B6;font-size:13px;"></i>
                {{ now()->translatedFormat('d M Y') }}
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div id="content">
        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-6 px-4">
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center gap-3 animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="text-emerald-800 font-semibold text-sm">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-6 px-4">
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center gap-3 animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="text-rose-800 font-semibold text-sm">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto mb-6 px-4">
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3 p-4">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="text-rose-800 font-bold text-sm">Veuillez corriger les erreurs suivantes :</div>
                    </div>
                    <ul class="px-16 pb-4 list-disc text-rose-700 text-xs font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@if(auth()->user()->role === 'patient')
    @include('components.chatbot')
@endif

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="{{ asset('assets/js/chat-bot.js') }}" defer></script>
<script>
    // Notification dropdown – no page redirect
    // Mobile menu toggle
    const menuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('mobile-open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close sidebar when clicking outside (on mobile)
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-open')) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('mobile-open');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>