@extends('layouts.app')

@section('title', 'Espace médecin')
@section('page-title', 'Tableau de bord médecin')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="welcome-banner">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2 fw-bold">Bonjour, Dr. {{ auth()->user()->name }} !</h2>
                    <p class="mb-0 opacity-75">Voici le résumé de votre activité du jour</p>
                </div>
                <div class="text-end">
                    <i class="fas fa-user-md fa-3x opacity-50"></i>
                    <p class="mb-0 mt-2 small">{{ now()->format('l d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase small fw-semibold mb-1">Patients en attente</p>
                        <h2 class="fw-bold mb-0">
                            {{ \App\Models\WaitingRoom::where('doctor_id', auth()->user()->doctor->id)->where('status', 'waiting')->count() }}
                        </h2>
                        <small class="text-primary mt-2 d-block">
                            <i class="fas fa-clock me-1"></i> En file d'attente
                        </small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10">
                        <i class="fas fa-clock text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase small fw-semibold mb-1">Rendez-vous aujourd'hui</p>
                        <h2 class="fw-bold mb-0">
                            {{ \App\Models\Appointment::where('doctor_id', auth()->user()->doctor->id)
                                ->whereDate('date_time', today())
                                ->whereIn('status', ['confirmed', 'pending'])->count() }}
                        </h2>
                        <small class="text-success mt-2 d-block">
                            <i class="fas fa-calendar-check me-1"></i> Programme du jour
                        </small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10">
                        <i class="fas fa-calendar-check text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card warning shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase small fw-semibold mb-1">Consultations effectuées</p>
                        <h2 class="fw-bold mb-0">
                            {{ \App\Models\Consultation::where('doctor_id', auth()->user()->doctor->id)->count() }}
                        </h2>
                        <small class="text-warning mt-2 d-block">
                            <i class="fas fa-stethoscope me-1"></i> Total cumulé
                        </small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10">
                        <i class="fas fa-stethoscope text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Rapides -->

<!-- Programme du jour -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-day me-2 text-primary"></i>Programme du jour</h5>
            </div>
            <div class="card-body">
                @php
                    $todayAppointments = \App\Models\Appointment::with(['patient.user'])
                        ->where('doctor_id', auth()->user()->doctor->id)
                        ->whereDate('date_time', today())
                        ->orderBy('date_time')
                        ->get();
                @endphp
                
                @if($todayAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Heure</th>
                                    <th>Patient</th>
                                    <th>Téléphone</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->date_time->format('H:i') }}</td>
                                    <td>{{ $appointment->patient->user->name }}</td>
                                    <td>{{ $appointment->patient->user->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($appointment->status == 'confirmed')
                                            <span class="badge bg-success">Confirmé</span>
                                        @elseif($appointment->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge bg-secondary">Terminé</span>
                                        @else
                                            <span class="badge bg-danger">Annulé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ url('/doctor/consultations/create?patient='.$appointment->patient->id.'&appointment='.$appointment->id) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-play"></i> Consulter
                                            </a>
                                            <a href="{{ url('/doctor/patients/'.$appointment->patient->id) }}" 
                                               class="btn btn-info">
                                                <i class="fas fa-folder"></i> Dossier
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-day fa-4x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-0">Aucun rendez-vous aujourd'hui</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.welcome-banner {
    background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
    border-radius: 20px;
    padding: 25px;
    color: white;
}
.stat-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection