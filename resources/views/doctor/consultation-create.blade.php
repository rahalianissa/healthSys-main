@extends('layouts.app')

@section('title', 'Nouvelle consultation')
@section('page-title', 'Créer une consultation')

@section('styles')
<style>
    .patient-card {
        background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 25px;
    }
    .patient-avatar {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: bold;
    }
    .vital-input {
        transition: all 0.3s;
    }
    .vital-input:focus {
        border-color: #f0b429;
        box-shadow: 0 0 0 0.2rem rgba(240,180,41,0.25);
        transform: translateY(-2px);
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #1a5f7a;
    }
    .form-section h6 {
        color: #1a5f7a;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .btn-save {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
    }
    .btn-cancel {
        background: #6c757d;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    .form-label i {
        color: #1a5f7a;
        width: 25px;
    }
    textarea {
        resize: vertical;
    }
    .vital-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }
    .vital-item {
        background: white;
        border-radius: 10px;
        padding: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
    }
    .vital-item:hover {
        border-color: #1a5f7a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    @media (max-width: 768px) {
        .patient-card {
            text-align: center;
        }
        .patient-avatar {
            margin: 0 auto 15px;
        }
        .vital-grid {
            grid-template-columns: 1fr;
        }
        .btn-save, .btn-cancel {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-stethoscope me-2"></i> Nouvelle consultation médicale
                </h5>
            </div>
            <div class="card-body">
                <!-- Carte Patient -->
                <div class="patient-card">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center text-md-start">
                            <div class="patient-avatar mx-auto mx-md-0">
                                {{ substr($patient->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-5 text-center text-md-start">
                            <h5 class="mb-1">{{ $patient->user->name }}</h5>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-envelope me-1"></i> {{ $patient->user->email }}
                            </p>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-phone me-1"></i> {{ $patient->user->phone ?? 'Non renseigné' }}
                            </p>
                        </div>
                        <div class="col-md-5 text-center text-md-end">
                            <div class="mt-2 mt-md-0">
                                <span class="badge bg-light text-dark">
                                    <i class="far fa-calendar-alt me-1"></i> {{ now()->format('d/m/Y') }}
                                </span>
                                <span class="badge bg-light text-dark ms-2">
                                    <i class="far fa-clock me-1"></i> {{ now()->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('doctor.consultations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <!-- Signes vitaux -->
                    <div class="form-section">
                        <h6><i class="fas fa-heartbeat me-2"></i> Signes vitaux</h6>
                        <div class="vital-grid">
                            <div class="vital-item">
                                <label class="form-label">
                                    <i class="fas fa-weight-scale"></i> Poids (kg)
                                </label>
                                <input type="number" step="0.1" name="weight" class="form-control vital-input" placeholder="ex: 70.5">
                            </div>
                            <div class="vital-item">
                                <label class="form-label">
                                    <i class="fas fa-ruler"></i> Taille (cm)
                                </label>
                                <input type="number" step="0.1" name="height" class="form-control vital-input" placeholder="ex: 175">
                            </div>
                            <div class="vital-item">
                                <label class="form-label">
                                    <i class="fas fa-tachometer-alt"></i> Tension artérielle
                                </label>
                                <input type="text" name="blood_pressure" class="form-control vital-input" placeholder="ex: 120/80">
                            </div>
                            <div class="vital-item">
                                <label class="form-label">
                                    <i class="fas fa-thermometer-half"></i> Température (°C)
                                </label>
                                <input type="number" step="0.1" name="temperature" class="form-control vital-input" placeholder="ex: 36.5">
                            </div>
                            <div class="vital-item">
                                <label class="form-label">
                                    <i class="fas fa-heart"></i> Fréquence cardiaque
                                </label>
                                <input type="text" name="heart_rate" class="form-control vital-input" placeholder="ex: 72 bpm">
                            </div>
                        </div>
                    </div>

                    <!-- Symptômes -->
                    <div class="form-section">
                        <h6><i class="fas fa-notes-medical me-2"></i> Symptômes</h6>
                        <textarea name="symptoms" class="form-control" rows="3" 
                            placeholder="Décrivez les symptômes du patient...&#10;Ex: Fièvre, toux, fatigue, douleur thoracique..."></textarea>
                    </div>

                    <!-- Diagnostic -->
                    <div class="form-section">
                        <h6><i class="fas fa-clipboard-list me-2"></i> Diagnostic</h6>
                        <textarea name="diagnosis" class="form-control" rows="3" 
                            placeholder="Diagnostic médical...&#10;Ex: Grippe saisonnière, Hypertension artérielle..."></textarea>
                    </div>

                    <!-- Traitement -->
                    <div class="form-section">
                        <h6><i class="fas fa-pills me-2"></i> Traitement prescrit</h6>
                        <textarea name="treatment" class="form-control" rows="3" 
                            placeholder="Traitement et médicaments prescrits...&#10;Ex: Paracétamol 500mg, 3 fois par jour pendant 5 jours"></textarea>
                    </div>

                    <!-- Notes supplémentaires -->
                    <div class="form-section">
                        <h6><i class="fas fa-comment-dots me-2"></i> Notes supplémentaires</h6>
                        <textarea name="notes" class="form-control" rows="2" 
                            placeholder="Informations complémentaires..."></textarea>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('doctor.consultations') }}" class="btn btn-cancel">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i> Enregistrer la consultation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-formatage pour la tension artérielle
document.querySelector('input[name="blood_pressure"]')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9/]/g, '');
    e.target.value = value;
});

// Validation du poids et taille
document.querySelector('input[name="weight"]')?.addEventListener('change', function(e) {
    let value = parseFloat(e.target.value);
    if (value < 0) e.target.value = 0;
    if (value > 300) e.target.value = 300;
});

document.querySelector('input[name="height"]')?.addEventListener('change', function(e) {
    let value = parseFloat(e.target.value);
    if (value < 0) e.target.value = 0;
    if (value > 250) e.target.value = 250;
});

// Validation de la température
document.querySelector('input[name="temperature"]')?.addEventListener('change', function(e) {
    let value = parseFloat(e.target.value);
    if (value < 30) e.target.value = 30;
    if (value > 45) e.target.value = 45;
});
</script>
@endpush