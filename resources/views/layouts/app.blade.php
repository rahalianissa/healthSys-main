<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HealthSys')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a5f7a;
            --primary-dark: #0d3b4f;
            --primary-light: #e8f4f8;
            --secondary-color: #f0b429;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #2c3e50;
            --light-color: #f8f9fc;
            --gray-100: #f8f9fc;
            --gray-200: #eaecf4;
            --gray-300: #dddfeb;
            --gray-400: #d1d3e2;
            --gray-500: #b7b9cc;
            --gray-600: #858796;
            --gray-700: #6e707e;
            --gray-800: #5a5c69;
            --gray-900: #3a3b45;
            --shadow-sm: 0 0.125rem 0.25rem 0 rgba(58,59,69,0.2);
            --shadow-md: 0 0.5rem 1rem 0 rgba(58,59,69,0.15);
            --shadow-lg: 0 1rem 3rem 0 rgba(58,59,69,0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.5;
        }

        /* RTL Support */
        body[dir="rtl"] {
            text-align: right;
        }
        
        body[dir="rtl"] .ms-auto {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
        
        body[dir="rtl"] .me-auto {
            margin-right: 0 !important;
            margin-left: auto !important;
        }
        
        body[dir="rtl"] .dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        /* ========== SIDEBAR ========== */
        .layout-menu {
            position: fixed;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            transition: var(--transition);
            z-index: 1040;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        body[dir="rtl"] .layout-menu {
            right: 0;
            left: auto;
        }

        .layout-menu::-webkit-scrollbar {
            width: 5px;
        }

        .layout-menu::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .layout-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .app-brand {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--secondary-color);
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.9rem;
            gap: 0.75rem;
        }

        body[dir="rtl"] .menu-link {
            padding: 0.75rem 1.5rem;
        }

        .menu-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        body[dir="rtl"] .menu-link:hover {
            padding-right: 2rem;
            padding-left: 1.5rem;
        }
        
        body[dir="ltr"] .menu-link:hover {
            padding-left: 2rem;
            padding-right: 1.5rem;
        }

        .menu-link.active {
            background: var(--secondary-color);
            color: var(--primary-dark);
            border-left: 4px solid white;
        }
        
        body[dir="rtl"] .menu-link.active {
            border-left: none;
            border-right: 4px solid white;
        }

        .menu-icon {
            width: 24px;
            font-size: 1.1rem;
            text-align: center;
        }

        .menu-header {
            padding: 1rem 1.5rem 0.5rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* ========== LAYOUT PAGE ========== */
        .layout-page {
            margin-left: 280px;
            transition: var(--transition);
        }
        
        body[dir="rtl"] .layout-page {
            margin-left: 0;
            margin-right: 280px;
        }

        /* ========== NAVBAR ========== */
        .layout-navbar {
            background: white;
            padding: 0.75rem 1.5rem;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Style pour la barre de recherche avec autocomplete */
        .search-wrapper {
            position: relative;
            width: 280px;
        }

        .search-input {
            background: var(--gray-100);
            border: none;
            border-radius: 30px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.85rem;
            width: 100%;
            transition: var(--transition);
        }
        
        body[dir="rtl"] .search-input {
            padding: 0.5rem 2.5rem 0.5rem 1rem;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(26,95,122,0.1);
            background: white;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            font-size: 0.9rem;
            z-index: 1;
        }
        
        body[dir="rtl"] .search-icon {
            left: auto;
            right: 12px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            margin-top: 8px;
            z-index: 1050;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }

        .search-results .list-group-item {
            border: none;
            border-radius: 0;
            padding: 12px 16px;
            transition: var(--transition);
        }

        .search-results .list-group-item:first-child {
            border-radius: 12px 12px 0 0;
        }

        .search-results .list-group-item:last-child {
            border-radius: 0 0 12px 12px;
        }

        .search-results .list-group-item:hover {
            background: var(--gray-100);
        }

        .search-result-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        body[dir="rtl"] .search-result-icon {
            margin-right: 0;
            margin-left: 12px;
        }

        .search-result-icon.patient {
            background: rgba(26, 95, 122, 0.1);
            color: var(--primary-color);
        }

        .search-result-icon.appointment {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .search-result-icon.doctor {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .search-result-icon.prescription {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
            border-radius: 50%;
            font-weight: 600;
        }
        
        body[dir="rtl"] .notification-badge {
            right: auto;
            left: -8px;
        }

        /* ========== CONTENT ========== */
        .content-wrapper {
            padding: 1.5rem;
            min-height: calc(100vh - 60px);
        }

        .page-title {
            margin-bottom: 1.5rem;
        }

        .page-title h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .page-title p {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        /* ========== CARDS ========== */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            background: white;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-radius: 1rem 1rem 0 0;
        }

        /* ========== STATS CARDS ========== */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
        }
        
        body[dir="rtl"] .stat-card {
            border-left: none;
            border-right: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1.2;
        }

        /* ========== BUTTONS ========== */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26,95,122,0.3);
        }

        /* ========== FOOTER ========== */
        .footer {
            background: white;
            padding: 1rem 1.5rem;
            text-align: center;
            border-top: 1px solid var(--gray-200);
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .layout-menu {
                transform: translateX(-100%);
            }
            
            body[dir="rtl"] .layout-menu {
                transform: translateX(100%);
            }
            
            .layout-menu.open {
                transform: translateX(0);
            }
            
            .layout-page {
                margin-left: 0;
            }
            
            body[dir="rtl"] .layout-page {
                margin-right: 0;
            }
            
            .search-wrapper {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.4rem;
            }
            
            .page-title h1 {
                font-size: 1.3rem;
            }
            
            .search-wrapper {
                display: none;
            }
        }

        /* ========== ANIMATIONS ========== */
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

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease forwards;
        }
        
        /* ========== MODE SOMBRE GLOBAL ========== */
        body.dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        
        body.dark-mode .card {
            background-color: #16213e;
            border-color: #0f3460;
            color: #e0e0e0;
        }
        
        body.dark-mode .card-header {
            background-color: #0f3460;
            border-bottom-color: #1a5f7a;
            color: #e0e0e0;
        }
        
        body.dark-mode .card-body {
            color: #e0e0e0;
        }
        
        body.dark-mode .table {
            color: #e0e0e0;
        }
        
        body.dark-mode .table thead th {
            background-color: #0f3460;
            color: #e0e0e0;
            border-color: #1a5f7a;
        }
        
        body.dark-mode .table tbody td {
            border-color: #0f3460;
            color: #d0d0d0;
        }
        
        body.dark-mode .table tbody tr:hover {
            background-color: #1a2a4a;
        }
        
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #0f3460;
            color: #e0e0e0;
            border-color: #1a5f7a;
        }
        
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background-color: #1a3a6a;
            color: #e0e0e0;
            border-color: #f0b429;
            box-shadow: 0 0 0 0.2rem rgba(240, 180, 41, 0.25);
        }
        
        body.dark-mode .form-label {
            color: #e0e0e0;
        }
        
        body.dark-mode .input-group-text {
            background-color: #0f3460;
            color: #e0e0e0;
            border-color: #1a5f7a;
        }
        
        body.dark-mode .btn-outline-secondary {
            color: #e0e0e0;
            border-color: #1a5f7a;
        }
        
        body.dark-mode .btn-outline-secondary:hover {
            background-color: #1a5f7a;
            color: white;
        }
        
        body.dark-mode .list-group-item {
            background-color: #16213e;
            color: #e0e0e0;
            border-color: #0f3460;
        }
        
        body.dark-mode .list-group-item:hover {
            background-color: #1a2a4a;
        }
        
        body.dark-mode .search-results {
            background-color: #16213e;
            border-color: #0f3460;
        }
        
        body.dark-mode .search-results .list-group-item {
            color: #e0e0e0;
        }
        
        body.dark-mode .search-results .list-group-item:hover {
            background-color: #1a2a4a;
        }
        
        body.dark-mode .alert {
            background-color: #0f3460;
            color: #e0e0e0;
            border-color: #1a5f7a;
        }
        
        body.dark-mode .alert-success {
            background-color: #0a2a1a;
            color: #90ee90;
            border-color: #28a745;
        }
        
        body.dark-mode .alert-danger {
            background-color: #3a1a1a;
            color: #ff9999;
            border-color: #dc3545;
        }
        
        body.dark-mode .alert-warning {
            background-color: #3a2a1a;
            color: #ffcc80;
            border-color: #ffc107;
        }
        
        body.dark-mode .alert-info {
            background-color: #1a2a3a;
            color: #80d4ff;
            border-color: #17a2b8;
        }
        
        body.dark-mode .modal-content {
            background-color: #16213e;
            color: #e0e0e0;
            border-color: #0f3460;
        }
        
        body.dark-mode .modal-header {
            border-bottom-color: #0f3460;
        }
        
        body.dark-mode .modal-footer {
            border-top-color: #0f3460;
        }
        
        body.dark-mode .dropdown-menu {
            background-color: #16213e;
            color: #e0e0e0;
            border-color: #0f3460;
        }
        
        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }
        
        body.dark-mode .dropdown-item:hover {
            background-color: #1a5f7a;
            color: white;
        }
        
        body.dark-mode .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        body.dark-mode .nav-link:hover {
            color: white;
        }
        
        body.dark-mode .navbar {
            background-color: #0d3b4f !important;
        }
        
        body.dark-mode .footer {
            background-color: #0f3460;
            color: #e0e0e0;
            border-top-color: #1a5f7a;
        }
        
        body.dark-mode .bg-white {
            background-color: #16213e !important;
        }
        
        body.dark-mode .text-muted {
            color: #aaa !important;
        }
        
        body.dark-mode .border {
            border-color: #0f3460 !important;
        }
        
        body.dark-mode .border-bottom {
            border-bottom-color: #0f3460 !important;
        }
        
        body.dark-mode .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Sidebar en mode sombre */
        body.dark-mode .layout-menu {
            background: linear-gradient(135deg, #0d3b4f 0%, #061a24 100%);
        }
        
        body.dark-mode .menu-link {
            color: rgba(255, 255, 255, 0.7);
        }
        
        body.dark-mode .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        body.dark-mode .menu-link.active {
            background: #f0b429;
            color: #0d3b4f;
        }
        
        /* Badges en mode sombre */
        body.dark-mode .badge.bg-primary {
            background-color: #1a5f7a !important;
        }
        
        body.dark-mode .badge.bg-success {
            background-color: #0a5a2a !important;
        }
        
        body.dark-mode .badge.bg-warning {
            background-color: #8a6a0a !important;
            color: #e0e0e0;
        }
        
        body.dark-mode .badge.bg-danger {
            background-color: #8a1a1a !important;
        }
        
        body.dark-mode .badge.bg-info {
            background-color: #0a5a6a !important;
        }
        
        /* Progress bar en mode sombre */
        body.dark-mode .progress {
            background-color: #0f3460;
        }
        
        body.dark-mode .progress-bar {
            background-color: #1a5f7a;
        }

        /* Animations pour les toasts */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body>
<div class="layout-wrapper">
    <div class="layout-container">

        <!-- SIDEBAR -->
        <aside id="layout-menu" class="layout-menu">
            <div class="app-brand">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="fas fa-heartbeat me-2"></i>Health<span>Sys</span>
                </a>
            </div>

            <ul class="menu-inner" style="list-style: none; padding-left: 0;">
            @auth
                @if(auth()->user()->role == 'chef_medecine')
                    <li class="menu-item">
                        <a href="{{ url('/admin') }}" class="menu-link {{ request()->is('admin') ? 'active' : '' }}">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span>{{ __('messages.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/admin/doctors') }}" class="menu-link">
                            <i class="menu-icon fas fa-user-md"></i>
                            <span>{{ __('messages.doctors') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/admin/secretaries') }}" class="menu-link">
                            <i class="menu-icon fas fa-user-tie"></i>
                            <span>Secrétaires</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/patients') }}" class="menu-link">
                            <i class="menu-icon fas fa-users"></i>
                            <span>{{ __('messages.patients') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/admin/specialites') }}" class="menu-link">
                            <i class="menu-icon fas fa-stethoscope"></i>
                            <span>Spécialités</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/admin/departements') }}" class="menu-link">
                            <i class="menu-icon fas fa-building"></i>
                            <span>Départements</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/appointments') }}" class="menu-link">
                            <i class="menu-icon fas fa-calendar-alt"></i>
                            <span>{{ __('messages.appointments') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/admin/reports') }}" class="menu-link">
                            <i class="menu-icon fas fa-chart-bar"></i>
                            <span>Rapports</span>
                        </a>
                    </li>

                @elseif(auth()->user()->role == 'doctor')
                    <li class="menu-item">
                        <a href="{{ url('/doctor/dashboard') }}" class="menu-link">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span>{{ __('messages.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/doctor/waiting-room') }}" class="menu-link">
                            <i class="menu-icon fas fa-clock"></i>
                            <span>Salle d'attente</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/doctor/consultations') }}" class="menu-link">
                            <i class="menu-icon fas fa-stethoscope"></i>
                            <span>Consultations</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/doctor/history') }}" class="menu-link">
                            <i class="menu-icon fas fa-history"></i>
                            <span>Historique</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/doctor/patients') }}" class="menu-link">
                            <i class="menu-icon fas fa-user-injured"></i>
                            <span>{{ __('messages.patients') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/doctor/establish-document') }}" class="menu-link">
                            <i class="menu-icon fas fa-file-alt"></i>
                            <span>Établir document</span>
                        </a>
                    </li>

                @elseif(auth()->user()->role == 'secretaire')
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/dashboard') }}" class="menu-link">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span>{{ __('messages.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/appointments') }}" class="menu-link">
                            <i class="menu-icon fas fa-calendar-alt"></i>
                            <span>{{ __('messages.appointments') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/patients') }}" class="menu-link">
                            <i class="menu-icon fas fa-users"></i>
                            <span>{{ __('messages.patients') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/comptabilite') }}" class="menu-link">
                            <i class="menu-icon fas fa-chart-line"></i>
                            <span>Comptabilité</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/documents') }}" class="menu-link">
                            <i class="menu-icon fas fa-file-alt"></i>
                            <span>Documents</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/secretaire/waiting-room') }}" class="menu-link">
                            <i class="menu-icon fas fa-clock"></i>
                            <span>Salle d'attente</span>
                        </a>
                    </li>

                @elseif(auth()->user()->role == 'patient')
                    <li class="menu-item">
                        <a href="{{ url('/patient/dashboard') }}" class="menu-link">
                            <i class="menu-icon fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/patient/appointments') }}" class="menu-link">
                            <i class="menu-icon fas fa-calendar-alt"></i>
                            <span>{{ __('messages.appointments') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/patient/medical-record') }}" class="menu-link">
                            <i class="menu-icon fas fa-folder-medical"></i>
                            <span>Dossier médical</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/patient/prescriptions') }}" class="menu-link">
                            <i class="menu-icon fas fa-prescription"></i>
                            <span>{{ __('messages.prescriptions') }}</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ url('/patient/invoices') }}" class="menu-link">
                            <i class="menu-icon fas fa-file-invoice-dollar"></i>
                            <span>{{ __('messages.invoices') }}</span>
                        </a>
                    </li>
                @endif

                <li class="menu-header">
                    <span>COMPTE</span>
                </li>
                <li class="menu-item">
                    <a href="{{ url('/profile') }}" class="menu-link">
                        <i class="menu-icon fas fa-user-circle"></i>
                        <span>{{ __('messages.profile') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ url('/settings') }}" class="menu-link">
                        <i class="menu-icon fas fa-cog"></i>
                        <span>{{ __('messages.settings') }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link text-danger" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="menu-icon fas fa-sign-out-alt"></i>
                        <span>{{ __('messages.logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endauth
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="layout-page">

            <!-- NAVBAR -->
            <nav class="layout-navbar">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <i class="fas fa-bars d-xl-none fs-4" id="mobile-menu-toggle" style="cursor: pointer;"></i>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <!-- BARRE DE RECHERCHE AVEC AUTOCOMPLETE -->
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="globalSearchInput" class="search-input" placeholder="{{ __('messages.search') }}...">
                            <div id="globalSearchResults" class="search-results"></div>
                        </div>
                        
                        <!-- LANGUAGE SWITCHER -->
                        <div class="dropdown ms-2">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                @if(session('locale') == 'fr')
                                    🇫🇷 FR
                                @elseif(session('locale') == 'ar')
                                    🇸🇦 AR
                                @else
                                    🇬🇧 EN
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'fr' ? 'active' : '' }}" 
                                       href="{{ url('/lang/fr') }}">
                                        <span>🇫🇷</span> Français
                                        @if(session('locale') == 'fr')
                                            <i class="fas fa-check ms-auto text-success"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'ar' ? 'active' : '' }}" 
                                       href="{{ url('/lang/ar') }}">
                                        <span>🇸🇦</span> العربية
                                        @if(session('locale') == 'ar')
                                            <i class="fas fa-check ms-auto text-success"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'en' ? 'active' : '' }}" 
                                       href="{{ url('/lang/en') }}">
                                        <span>🇬🇧</span> English
                                        @if(session('locale') == 'en')
                                            <i class="fas fa-check ms-auto text-success"></i>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <a class="nav-link position-relative" href="#">
                            <i class="fas fa-bell fa-lg text-muted"></i>
                            <span class="notification-badge">3</span>
                        </a>
                        
                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown" style="cursor: pointer;">
                                @php
                                    $avatarImg = asset('assets/img/avatars/user.png');
                                    if (auth()->user()->avatar) {
                                        $avatarPath = public_path('assets/img/avatars/' . auth()->user()->avatar);
                                        if (file_exists($avatarPath)) {
                                            $avatarImg = asset('assets/img/avatars/' . auth()->user()->avatar);
                                        }
                                    }
                                @endphp
                                <img src="{{ $avatarImg }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                <div class="ms-2 d-none d-md-block">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <div class="small text-muted">{{ auth()->user()->email }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li><a class="dropdown-item py-2" href="{{ url('/profile') }}">
                                    <i class="fas fa-user-circle me-2"></i> {{ __('messages.profile') }}
                                </a></li>
                                <li><a class="dropdown-item py-2" href="{{ url('/settings') }}">
                                    <i class="fas fa-cog me-2"></i> {{ __('messages.settings') }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- CONTENT -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="page-title">
                    <h1>@yield('title', __('messages.dashboard'))</h1>
                    <p>@yield('page-title', __('messages.welcome'))</p>
                </div>
                
                @yield('content')
            </div>

            <!-- FOOTER -->
            <footer class="footer">
                <div>© {{ date('Y') }} HealthSys — Plateforme de Gestion Médicale</div>
            </footer>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ========== MOBILE MENU TOGGLE ==========
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const sidebar = document.getElementById('layout-menu');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    // ========== ANIMATION ON SCROLL ==========
    const animateElements = document.querySelectorAll('.stat-card, .card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
            }
        });
    }, { threshold: 0.1 });
    
    animateElements.forEach(el => observer.observe(el));
    
    // ========== MODE SOMBRE ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    });
    
    window.toggleDarkMode = function(enabled) {
        if (enabled) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    };
    
    // ========== FONCTIONS D'IMPRESSION AMÉLIORÉES ==========

    /**
     * Afficher un toast de notification
     */
    function showToast(type, message) {
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `custom-toast alert alert-${type}`;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            min-width: 250px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'danger' ? 'fa-exclamation-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        toast.innerHTML = `<i class="fas ${icon} me-2"></i> ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /**
     * Imprimer une section spécifique
     */
    function printSection(elementId, title = 'Document') {
        const element = document.getElementById(elementId);
        if (!element) {
            showToast('danger', 'Élément non trouvé');
            return;
        }
        
        const printWindow = window.open('', '_blank');
        const content = element.cloneNode(true);
        
        // Supprimer les éléments non imprimables
        content.querySelectorAll('.no-print, .btn, button, .btn-group').forEach(el => el.remove());
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${title}</title>
                <meta charset="UTF-8">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; font-family: Arial, sans-serif; }
                    .print-header {
                        text-align: center;
                        margin-bottom: 30px;
                        padding-bottom: 20px;
                        border-bottom: 2px solid #1a5f7a;
                    }
                    .print-header h1 { color: #1a5f7a; }
                    .print-footer {
                        text-align: center;
                        margin-top: 30px;
                        padding-top: 20px;
                        border-top: 1px solid #ddd;
                        font-size: 10px;
                    }
                    @media print { body { padding: 0; } }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>HealthSys</h1>
                    <p>Date: ${new Date().toLocaleDateString('fr-FR')}</p>
                </div>
                ${content.outerHTML}
                <div class="print-footer">
                    <p>© ${new Date().getFullYear()} HealthSys</p>
                </div>
                <script>window.print(); setTimeout(() => window.close(), 500);<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
        showToast('success', 'Impression lancée');
    }

    /**
     * Exporter en PDF
     */
    function exportToPDF(elementId, filename = 'document') {
        const element = document.getElementById(elementId);
        if (!element) {
            showToast('danger', 'Élément non trouvé');
            return;
        }
        
        showToast('info', 'Préparation du PDF...');
        
        const printWindow = window.open('', '_blank');
        const content = element.cloneNode(true);
        content.querySelectorAll('.no-print, .btn, button, .btn-group').forEach(el => el.remove());
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${filename}</title>
                <meta charset="UTF-8">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; font-family: Arial, sans-serif; }
                    @media print { body { padding: 0; } }
                </style>
            </head>
            <body>
                ${content.outerHTML}
                <script>window.print();<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
        showToast('success', 'PDF prêt');
    }

    // Rendre les fonctions globales
    window.printSection = printSection;
    window.exportToPDF = exportToPDF;
    window.showToast = showToast;

    // ========== RECHERCHE AVEC AUTOCOMPLETE ==========
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearchInput');
        const resultsDiv = document.getElementById('globalSearchResults');
        let searchTimeout = null;
        
        if (!searchInput) return;
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function getResultIcon(type) {
            switch(type) {
                case 'patient': return '<i class="fas fa-user"></i>';
                case 'appointment': return '<i class="fas fa-calendar-check"></i>';
                case 'doctor': return '<i class="fas fa-user-md"></i>';
                case 'prescription': return '<i class="fas fa-prescription"></i>';
                default: return '<i class="fas fa-search"></i>';
            }
        }
        
        function getResultIconClass(type) {
            switch(type) {
                case 'patient': return 'patient';
                case 'appointment': return 'appointment';
                case 'doctor': return 'doctor';
                case 'prescription': return 'prescription';
                default: return 'patient';
            }
        }
        
        searchInput.addEventListener('keyup', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '<div class="list-group list-group-flush">';
                            data.forEach(item => {
                                const iconClass = getResultIconClass(item.type);
                                const iconHtml = getResultIcon(item.type);
                                html += `
                                    <a href="${item.url}" class="list-group-item list-group-item-action d-flex align-items-center">
                                        <div class="search-result-icon ${iconClass}">
                                            ${iconHtml}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">${escapeHtml(item.label)}</div>
                                            <small class="text-muted">${escapeHtml(item.subtitle)}</small>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                `;
                            });
                            html += '</div>';
                            resultsDiv.innerHTML = html;
                            resultsDiv.style.display = 'block';
                        } else {
                            resultsDiv.innerHTML = '<div class="p-3 text-center text-muted small">Aucun résultat trouvé</div>';
                            resultsDiv.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur recherche:', error);
                        resultsDiv.innerHTML = '<div class="p-3 text-center text-danger small">Erreur de recherche</div>';
                        resultsDiv.style.display = 'block';
                    });
            }, 300);
        });
        
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
        
        let selectedIndex = -1;
        
        searchInput.addEventListener('keydown', function(e) {
            const items = resultsDiv.querySelectorAll('.list-group-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelectedItem(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelectedItem(items, selectedIndex);
            } else if (e.key === 'Enter' && selectedIndex >= 0 && items[selectedIndex]) {
                e.preventDefault();
                window.location.href = items[selectedIndex].href;
            }
        });
        
        function updateSelectedItem(items, index) {
            items.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('active');
                    item.style.backgroundColor = 'var(--gray-100)';
                } else {
                    item.classList.remove('active');
                    item.style.backgroundColor = '';
                }
            });
            
            if (index >= 0 && items[index]) {
                items[index].scrollIntoView({ block: 'nearest' });
            }
        }
    });
</script>
@stack('scripts')
</body>
</html>