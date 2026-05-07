@extends('layouts.app')

@section('title', 'Détails du rendez-vous')
@section('page-title', 'Informations du rendez-vous')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i> Détails du rendez-vous
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong><i class="fas fa-user me-2 text-primary"></i>Patient:</strong>
                    <p class="mt-1">{{ $appointment->patient->user->name ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-phone me-2 text-primary"></i>Téléphone:</strong>
                    <p class="mt-1">{{ $appointment->patient->user->phone ?? 'Non renseigné' }}</p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-envelope me-2 text-primary"></i>Email:</strong>
                    <p class="mt-1">{{ $appointment->patient->user->email ?? 'Non renseigné' }}</p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-calendar me-2 text-primary"></i>Date:</strong>
                    <p class="mt-1">{{ $appointment->date_time->format('d/m/Y') }}</p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-clock me-2 text-primary"></i>Heure:</strong>
                    <p class="mt-1">{{ $appointment->date_time->format('H:i') }}</p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-tag me-2 text-primary"></i>Type:</strong>
                    <p class="mt-1">
                        @if($appointment->type == 'general')
                            <span class="badge bg-info">Générale</span>
                        @elseif($appointment->type == 'emergency')
                            <span class="badge bg-danger">Urgence</span>
                        @elseif($appointment->type == 'follow_up')
                            <span class="badge bg-warning">Suivi</span>
                        @else
                            <span class="badge bg-secondary">{{ $appointment->type }}</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-chart-simple me-2 text-primary"></i>Statut:</strong>
                    <p class="mt-1">
                        @if($appointment->status == 'confirmed')
                            <span class="badge bg-success">Confirmé</span>
                        @elseif($appointment->status == 'pending')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($appointment->status == 'cancelled')
                            <span class="badge bg-danger">Annulé</span>
                        @else
                            <span class="badge bg-secondary">Terminé</span>
                        @endif
                    </p>
                </div>
                @if($appointment->reason)
                <div class="mb-3">
                    <strong><i class="fas fa-sticky-note me-2 text-primary"></i>Motif:</strong>
                    <p class="mt-1">{{ $appointment->reason }}</p>
                </div>
                @endif
                @if($appointment->notes)
                <div class="mb-3">
                    <strong><i class="fas fa-comment me-2 text-primary"></i>Notes:</strong>
                    <p class="mt-1">{{ $appointment->notes }}</p>
                </div>
                @endif
            </div>
            <div class="card-footer text-center">
                <a href="{{ url('/doctor/dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
                @if($appointment->status == 'confirmed' || $appointment->status == 'pending')
                <a href="{{ url('/doctor/consultations/create?patient='.$appointment->patient_id.'&appointment='.$appointment->id) }}" class="btn btn-primary">
                    <i class="fas fa-stethoscope me-2"></i> Démarrer consultation
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection