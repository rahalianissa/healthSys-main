@extends('layouts.app')

@section('title', 'Mes patients')
@section('page-title', 'Liste de mes patients')

@section('styles')
<style>
    .patient-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .patient-card:hover {
        transform: translateX(5px);
        border-left-color: #1a5f7a;
        background-color: #f8f9fa;
    }
    .patient-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #1a5f7a, #0d3b4f);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    .badge-last-visit {
        background: #e8f4f8;
        color: #1a5f7a;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0">
            <i class="fas fa-user-injured text-primary me-2"></i> Mes patients
        </h5>
        <div class="mt-2 mt-md-0">
            <span class="badge bg-primary rounded-pill">
                <i class="fas fa-users me-1"></i> Total: {{ $patients->count() }}
            </span>
        </div>
    </div>
    <div class="card-body">
        @if(isset($patients) && $patients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Contact</th>
                            <th>Dernière consultation</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        @php
                            $lastConsultation = $patient->consultations()
                                ->where('doctor_id', auth()->user()->doctor->id ?? 0)
                                ->latest('consultation_date')
                                ->first();
                            $lastAppointment = $patient->appointments()
                                ->where('doctor_id', auth()->user()->doctor->id ?? 0)
                                ->latest('date_time')
                                ->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="patient-avatar me-3">
                                        {{ substr($patient->user->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $patient->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: #{{ $patient->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-envelope text-muted me-1"></i> {{ $patient->user->email ?? 'N/A' }}<br>
                                    <i class="fas fa-phone text-muted me-1"></i> {{ $patient->user->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($lastConsultation)
                                    <span class="badge-last-visit">
                                        <i class="far fa-calendar me-1"></i> {{ $lastConsultation->consultation_date->format('d/m/Y') }}
                                    </span>
                                @elseif($lastAppointment)
                                    <span class="badge-last-visit">
                                        <i class="far fa-calendar me-1"></i> RDV: {{ $lastAppointment->date_time->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Jamais</span>
                                @endif
                            </td>
                            <td>
                                @if($lastConsultation)
                                    <span class="badge bg-success">Consulté</span>
                                @elseif($lastAppointment)
                                    <span class="badge bg-warning">RDV programmé</span>
                                @else
                                    <span class="badge bg-secondary">Nouveau</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-info" title="Voir dossier">
                                        <i class="fas fa-folder-medical"></i> Dossier
                                    </a>
                                    <a href="{{ route('doctor.consultations.create') }}?patient={{ $patient->id }}" class="btn btn-primary" title="Nouvelle consultation">
                                        <i class="fas fa-stethoscope"></i> Consulter
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-user-injured"></i>
                <h5>Aucun patient pour le moment</h5>
                <p>Vous n'avez pas encore consulté de patients.</p>
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Retour au tableau de bord
                </a>
            </div>
        @endif
    </div>
</div>
@endsection