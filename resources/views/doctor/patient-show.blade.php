@extends('layouts.app')

@section('title', 'Dossier patient - ' . $patient->user->name)
@section('page-title', 'Dossier médical')

@section('content')
<div class="row">
    <!-- Informations patient -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle text-primary me-2"></i>
                    Informations patient
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                        <i class="fas fa-user fa-3x text-primary"></i>
                    </div>
                    <h4 class="mt-2 mb-0">{{ $patient->user->name }}</h4>
                    <small class="text-muted">Patient ID: #{{ $patient->id }}</small>
                </div>
                
                <div class="border-top pt-3">
                    <div class="row mb-2">
                        <div class="col-5 text-muted">📧 Email</div>
                        <div class="col-7">{{ $patient->user->email ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">📞 Téléphone</div>
                        <div class="col-7">{{ $patient->user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">🎂 Date naissance</div>
                        <div class="col-7">{{ $patient->user->birth_date ? \Carbon\Carbon::parse($patient->user->birth_date)->format('d/m/Y') : 'Non renseignée' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">🩸 Groupe sanguin</div>
                        <div class="col-7">{{ $patient->blood_type ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">🏢 Mutuelle</div>
                        <div class="col-7">{{ $patient->insurance_company ?? 'Aucune' }}</div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">{{ $stats['total_appointments'] }}</h5>
                            <small class="text-muted">RDV</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="text-success mb-0">{{ $stats['total_consultations'] }}</h5>
                            <small class="text-muted">Consult.</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning mb-0">{{ $stats['total_prescriptions'] }}</h5>
                        <small class="text-muted">Ordonn.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique des consultations -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-history text-info me-2"></i>
                    Historique des consultations
                </h5>
            </div>
            <div class="card-body">
                @if($patient->consultations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>Date</th><th>Diagnostic</th><th>Traitement</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @foreach($patient->consultations as $consultation)
                                <tr>
                                    <td>{{ $consultation->consultation_date->format('d/m/Y') }}<br><small class="text-muted">{{ $consultation->consultation_date->format('H:i') }}</small></td>
                                    <td>{{ Str::limit($consultation->diagnosis ?? 'Non renseigné', 50) }}</td>
                                    <td>{{ Str::limit($consultation->treatment ?? 'Non renseigné', 50) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="showConsultationDetails({{ $consultation->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-stethoscope fa-3x text-muted opacity-25"></i>
                        <p class="text-muted mt-2">Aucune consultation</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ordonnances -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0">
                    <i class="fas fa-prescription text-danger me-2"></i>
                    Ordonnances
                </h5>
            </div>
            <div class="card-body">
                @if($patient->prescriptions->count() > 0)
                    <div class="list-group">
                        @foreach($patient->prescriptions as $prescription)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Ordonnance du {{ $prescription->created_at->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">Dr. {{ $prescription->doctor->user->name }}</small>
                                    </div>
                                    <a href="{{ route('prescriptions.pdf', $prescription) }}" class="btn btn-sm btn-danger" target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-prescription fa-3x text-muted opacity-25"></i>
                        <p class="text-muted mt-2">Aucune ordonnance</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal consultation details -->
<div class="modal fade" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Détails de la consultation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="consultationDetails">
                <div class="text-center py-4">
                    <div class="spinner-border text-info"></div>
                    <p class="mt-2">Chargement...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showConsultationDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('consultationModal'));
    modal.show();
    
    fetch(`/consultations/${id}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('consultationDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> ${data.consultation_date}</p>
                        <p><strong>Médecin:</strong> Dr. ${data.doctor.user.name}</p>
                        <p><strong>Spécialité:</strong> ${data.doctor.specialty}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Poids:</strong> ${data.weight ? data.weight + ' kg' : 'N/A'}</p>
                        <p><strong>Taille:</strong> ${data.height ? data.height + ' cm' : 'N/A'}</p>
                        <p><strong>Tension:</strong> ${data.blood_pressure || 'N/A'}</p>
                    </div>
                </div>
                <hr>
                <h6>Symptômes:</h6>
                <p>${data.symptoms || 'Non renseignés'}</p>
                <h6>Diagnostic:</h6>
                <p>${data.diagnosis || 'Non renseigné'}</p>
                <h6>Traitement:</h6>
                <p>${data.treatment || 'Non renseigné'}</p>
                <h6>Notes:</h6>
                <p>${data.notes || 'Non renseignées'}</p>
            `;
        });
}
</script>
@endsection