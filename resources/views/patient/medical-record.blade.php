@extends('layouts.app')

@section('title', 'Mon dossier médical')
@section('page-title', 'Dossier médical')

@section('styles')
<style>
    .info-card {
        background: white;
        border-radius: 15px;
        transition: all 0.3s;
        border-left: 4px solid #1a5f7a;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stat-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .stat-badge.blood {
        background: #ffebee;
        color: #c62828;
    }
    .stat-badge.allergy {
        background: #fff3e0;
        color: #ef6c00;
    }
    .stat-badge.insurance {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .consultation-row {
        transition: all 0.2s;
        cursor: pointer;
    }
    .consultation-row:hover {
        background-color: #e8f4f8 !important;
        transform: scale(1.01);
    }
    .prescription-item {
        transition: all 0.2s;
    }
    .prescription-item:hover {
        background-color: #f5f5f5;
        transform: translateX(5px);
    }
    .modal-medical {
        border-radius: 20px;
        overflow: hidden;
    }
    .modal-medical .modal-header {
        background: linear-gradient(135deg, #1a5f7a, #0d3b4f);
        border: none;
    }
    .vital-sign {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        transition: all 0.3s;
    }
    .vital-sign:hover {
        background: #e8f4f8;
        transform: translateY(-2px);
    }
    .vital-icon {
        width: 40px;
        height: 40px;
        background: #1a5f7a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: white;
        font-size: 18px;
    }
    .vital-value {
        font-size: 18px;
        font-weight: bold;
        color: #1a5f7a;
    }
    .vital-label {
        font-size: 11px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- ========== CARTE INFORMATIONS PATIENT ========== -->
    <div class="col-md-4 mb-4">
        <div class="card info-card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle text-primary me-2"></i> Mes informations
                </h5>
            </div>
            <div class="card-body">
                @php
                    $user = auth()->user();
                    $patient = $user->patient;
                    $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : null;
                @endphp
                
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                        <i class="fas fa-user-md fa-3x text-primary"></i>
                    </div>
                    <h4 class="mt-2 mb-0">{{ $user->name }}</h4>
                    <small class="text-muted">Patient ID: #{{ $patient->id ?? 'N/A' }}</small>
                </div>
                
                <div class="border-top pt-3">
                    <div class="row mb-2">
                        <div class="col-5 text-muted">📧 Email</div>
                        <div class="col-7 fw-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">📞 Téléphone</div>
                        <div class="col-7 fw-semibold">{{ $user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">🎂 Date naissance</div>
                        <div class="col-7 fw-semibold">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : 'Non renseignée' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">📊 Âge</div>
                        <div class="col-7 fw-semibold">{{ $age ? $age . ' ans' : 'Non renseigné' }}</div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-6">
                        <div class="vital-sign">
                            <div class="vital-icon"><i class="fas fa-tint"></i></div>
                            <div class="vital-value">{{ $patient->blood_type ?? '—' }}</div>
                            <div class="vital-label">Groupe sanguin</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="vital-sign">
                            <div class="vital-icon"><i class="fas fa-allergies"></i></div>
                            <div class="vital-value">{{ $patient->allergies ? 'Oui' : 'Non' }}</div>
                            <div class="vital-label">Allergies</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                        <span><i class="fas fa-credit-card text-primary"></i> Mutuelle</span>
                        <span class="fw-semibold">{{ $patient->insurance_company ?? 'Aucune' }}</span>
                    </div>
                    @if($patient->insurance_number)
                    <div class="mt-2 small text-muted text-center">
                        N°: {{ $patient->insurance_number }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== HISTORIQUE CONSULTATIONS ========== -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-history text-primary me-2"></i> Historique des consultations
                </h5>
            </div>
            <div class="card-body">
                @if($consultations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Médecin</th>
                                    <th>Spécialité</th>
                                    <th>Diagnostic</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultations as $consultation)
                                <tr class="consultation-row">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $consultation->consultation_date->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $consultation->consultation_date->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="fas fa-user-md text-info"></i>
                                            </div>
                                            <div>
                                                <strong>Dr. {{ $consultation->doctor->user->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $consultation->doctor->specialty ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $consultation->doctor->specialty ?? 'Généraliste' }}</span>
                                    </td>
                                    <td>
                                        @if($consultation->diagnosis)
                                            <span class="badge bg-info">{{ Str::limit($consultation->diagnosis, 30) }}</span>
                                        @else
                                            <span class="badge bg-secondary">Non renseigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="showConsultationDetails({{ $consultation->id }})" title="Voir détails">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-stethoscope fa-4x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-0">Aucune consultation enregistrée</p>
                        <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route("patient.appointments") }}'">
                            <i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- ========== MES ORDONNANCES ========== -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-prescription text-primary me-2"></i> Mes ordonnances
                </h5>
            </div>
            <div class="card-body">
                @php
                    $prescriptions = \App\Models\Prescription::where('patient_id', $patient->id ?? 0)
                        ->with('doctor.user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($prescriptions->count() > 0)
                    <div class="list-group">
                        @foreach($prescriptions as $prescription)
                            <div class="list-group-item prescription-item border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-file-prescription text-danger me-2"></i>
                                            <strong>Ordonnance du {{ $prescription->created_at->format('d/m/Y') }}</strong>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-user-md me-1"></i> Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}
                                        </small>
                                        @php $meds = is_array($prescription->medications) ? $prescription->medications : json_decode($prescription->medications, true); @endphp
                                        @if(is_array($meds))
                                            <div class="mt-1">
                                                @foreach(array_slice($meds, 0, 2) as $med)
                                                    <span class="badge bg-light text-dark me-1">{{ $med['name'] ?? '' }}</span>
                                                @endforeach
                                                @if(count($meds) > 2)
                                                    <span class="badge bg-secondary">+{{ count($meds) - 2 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ url('/prescriptions/'.$prescription->id.'/pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($prescriptions->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('patient.prescriptions') }}" class="text-decoration-none">
                            Voir toutes mes ordonnances <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-prescription fa-3x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-0">Aucune ordonnance</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL DÉTAILS CONSULTATION ========== -->
<div class="modal fade" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-medical">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="fas fa-stethoscope me-2"></i> Détails de la consultation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="consultationDetails">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-3 text-muted">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showConsultationDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('consultationModal'));
    modal.show();
    
    const detailsDiv = document.getElementById('consultationDetails');
    detailsDiv.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3 text-muted">Chargement des détails...</p>
        </div>
    `;
    
    fetch(`/consultations/${id}/details`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            const date = new Date(data.consultation_date);
            const formattedDate = date.toLocaleDateString('fr-FR');
            
            let html = `
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>Informations générales</h6>
                                <p><strong>📅 Date:</strong> ${formattedDate || 'N/A'}</p>
                                <p><strong>👨‍⚕️ Médecin:</strong> Dr. ${data.doctor?.user?.name || 'N/A'}</p>
                                <p><strong>🏥 Spécialité:</strong> ${data.doctor?.specialty || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="text-primary mb-3"><i class="fas fa-heartbeat me-2"></i>Signes vitaux</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>⚖️ Poids:</strong> ${data.weight ? data.weight + ' kg' : 'N/A'}</p>
                                        <p><strong>📏 Taille:</strong> ${data.height ? data.height + ' cm' : 'N/A'}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>❤️ Tension:</strong> ${data.blood_pressure || 'N/A'}</p>
                                        <p><strong>🌡️ Température:</strong> ${data.temperature ? data.temperature + ' °C' : 'N/A'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0" style="background: #e8f4f8;">
                            <div class="card-body">
                                <h6 class="text-primary mb-2"><i class="fas fa-notes-medical me-2"></i>Symptômes</h6>
                                <p class="mb-0">${data.symptoms || 'Non renseignés'}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0" style="background: #e8f5e9;">
                            <div class="card-body">
                                <h6 class="text-success mb-2"><i class="fas fa-clipboard-list me-2"></i>Diagnostic</h6>
                                <p class="mb-0">${data.diagnosis || 'Non renseigné'}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0" style="background: #fff8e1;">
                            <div class="card-body">
                                <h6 class="text-warning mb-2"><i class="fas fa-pills me-2"></i>Traitement prescrit</h6>
                                <p class="mb-0">${data.treatment || 'Non renseigné'}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0" style="background: #f3e5f5;">
                            <div class="card-body">
                                <h6 class="text-purple mb-2"><i class="fas fa-comment-dots me-2"></i>Notes supplémentaires</h6>
                                <p class="mb-0">${data.notes || 'Non renseignées'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            detailsDiv.innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur:', error);
            detailsDiv.innerHTML = `
                <div class="alert alert-danger text-center m-3">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                    <strong>Erreur de chargement</strong><br>
                    <small>${error.message}</small>
                    <button class="btn btn-outline-danger btn-sm mt-3" onclick="location.reload()">
                        <i class="fas fa-sync me-2"></i>Réessayer
                    </button>
                </div>
            `;
        });
}
</script>
@endsection