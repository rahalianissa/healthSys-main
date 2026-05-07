@extends('layouts.app')

@section('page_title', 'Tableau de bord Général')
@section('page_subtitle', 'Vue d\'ensemble du cabinet')

@section('content')

<style>
    :root {
        --primary-indigo: #4f46e5;
        --dark-indigo: #312e81;
        --emerald-accent: #10b981;
        --bg-soft: #fdfdff;
        --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        --card-hover-shadow: 0 40px 80px -15px rgba(79, 70, 229, 0.15);
    }

    body { background-color: var(--bg-soft); }

    .card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(79, 70, 229, 0.05);
        box-shadow: var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-6px);
        box-shadow: var(--card-hover-shadow);
    }
    .stat-icon {
        width: 56px; height: 56px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .stat-val {
        font-size: 42px; font-weight: 800; color: var(--dark-indigo);
        line-height: 1; letter-spacing: -2px; margin: 16px 0 6px 0;
    }
    .stat-label {
        font-size: 13px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .tbl-th {
        padding: 18px 24px; font-size: 11px; font-weight: 800;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 0.15em;
        background: #f8fafc; text-align: left;
    }
    .tbl-td { padding: 20px 24px; font-size: 15px; border-top: 1px solid #f1f5f9; }
    
    .status-pill {
        display: inline-block; padding: 5px 14px; border-radius: 99px;
        font-size: 11px; font-weight: 800; text-transform: uppercase;
    }
</style>

{{-- ===== WELCOME BANNER ===== --}}
<div style="background: linear-gradient(135deg, var(--dark-indigo) 0%, var(--primary-indigo) 100%);
            border-radius: 32px; padding: 50px; margin-bottom: 40px;
            position: relative; overflow: hidden; box-shadow: 0 30px 60px -12px rgba(49, 46, 129, 0.25);">
    <div style="position:relative; z-index:1;">
        <h2 style="color:white; font-size:42px; font-weight:800; letter-spacing:-1.5px; margin-bottom:12px;">
            Bonjour, {{ auth()->user()->name }}
        </h2>
        <p style="color:rgba(255,255,255,0.7); font-size:18px; font-weight:500;">
            Bienvenue sur votre portail HealthSys. Accédez aux outils de gestion centralisés.
        </p>
    </div>
</div>

{{-- ===== CORE STATS ===== --}}
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:24px; margin-bottom:40px;">
    <div class="card" style="padding:28px; border-top: 6px solid var(--primary-indigo);">
        <div class="stat-icon" style="background:rgba(79, 70, 229, 0.08); color:var(--primary-indigo); margin-bottom:16px;">
            <i class="fa-solid fa-hospital-user"></i>
        </div>
        <div class="stat-val">{{ \App\Models\Patient::count() }}</div>
        <div class="stat-label">Total Patients</div>
    </div>

    <div class="card" style="padding:28px; border-top: 6px solid var(--emerald-accent);">
        <div class="stat-icon" style="background:rgba(16, 185, 129, 0.08); color:var(--emerald-accent); margin-bottom:16px;">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-val">{{ \App\Models\Appointment::whereDate('date_time', today())->count() }}</div>
        <div class="stat-label">RDV Aujourd'hui</div>
    </div>

    <div class="card" style="padding:28px; border-top: 6px solid #f59e0b;">
        <div class="stat-icon" style="background:rgba(245, 158, 11, 0.08); color:#f59e0b; margin-bottom:16px;">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-val">{{ \App\Models\WaitingRoom::where('status', 'waiting')->count() }}</div>
        <div class="stat-label">Salle d'Attente</div>
    </div>
</div>

{{-- ===== RECENT ACTIVITY ===== --}}
<div class="card" style="overflow:hidden; border:none;">
    <div style="padding:28px 32px; border-bottom:1px solid #f1f5f9;">
        <h3 style="font-size:20px; font-weight:800; color:var(--dark-indigo);">Aperçu du Flux Quotidien</h3>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="tbl-th">Heure</th>
                    <th class="tbl-th">Patient</th>
                    <th class="tbl-th">État de la visite</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Appointment::whereDate('date_time', today())->orderBy('date_time')->take(5)->get() as $apt)
                <tr style="transition:background 0.3s ease;" onmouseover="this.style.background='#fdfdff'" onmouseout="this.style.background='white'">
                    <td class="tbl-td" style="font-weight:800; color:var(--primary-indigo);">{{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}</td>
                    <td class="tbl-td" style="font-weight:800; color:var(--dark-indigo);">{{ $apt->patient->user->name }}</td>
                    <td class="tbl-td">
                        <span class="status-pill" style="background:{{ $apt->status == 'confirmed' ? '#ecfdf5' : '#fffbeb' }}; color:{{ $apt->status == 'confirmed' ? '#059669' : '#d97706' }};">
                            {{ ucfirst($apt->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding:60px; text-align:center; color:#94a3b8; font-size:15px; font-weight:700;">Aucun événement enregistré pour aujourd'hui</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
