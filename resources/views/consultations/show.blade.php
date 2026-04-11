@extends('layouts.app')

@section('title', 'Détails de la consultation')
@section('page-title', 'Fiche de consultation médicale')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-stethoscope me-2"></i> Détails de la consultation</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Patient:</strong> {{ $consultation->patient->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $consultation->patient->user->email ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $consultation->patient->user->phone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Médecin:</strong> Dr. {{ $consultation->doctor->user->name ?? 'N/A' }}</p>
                    <p><strong>Spécialité:</strong> {{ $consultation->doctor->specialty ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $consultation->consultation_date->format('d/m/Y') }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Poids:</strong> {{ $consultation->weight ? $consultation->weight . ' kg' : 'N/A' }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Taille:</strong> {{ $consultation->height ? $consultation->height . ' cm' : 'N/A' }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Tension:</strong> {{ $consultation->blood_pressure ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Température:</strong> {{ $consultation->temperature ? $consultation->temperature . ' °C' : 'N/A' }}</p>
                </div>
            </div>
            <hr>
            <h6>Symptômes:</h6>
            <p>{{ $consultation->symptoms ?? 'Non renseignés' }}</p>
            <h6>Diagnostic:</h6>
            <p>{{ $consultation->diagnosis ?? 'Non renseigné' }}</p>
            <h6>Traitement:</h6>
            <p>{{ $consultation->treatment ?? 'Non renseigné' }}</p>
            <h6>Notes:</h6>
            <p>{{ $consultation->notes ?? 'Non renseignées' }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour</a>
            <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        </div>
    </div>
</div>
@endsection