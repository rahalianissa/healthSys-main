@extends('layouts.app')

@section('page_title', 'Dossier Médical - ' . $patient->user->name)

@section('styles')
<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-soft: #48CAE4;
        --primary-bg: #f8fafc;
        --white: #ffffff;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .dossier-wrapper {
        background-color: var(--primary-bg);
        min-height: 100vh;
        padding-bottom: 50px;
    }

    /* Side Sidebar Profile */
    .profile-sidebar {
        background: var(--white);
        border-radius: 24px;
        padding: 30px 20px;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .avatar-wrapper {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 42px;
        color: white;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(2, 62, 138, 0.2);
    }

    .patient-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-active { background: #dcfce7; color: #15803d; }

    .info-list {
        margin-top: 30px;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }
    .info-icon-box {
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 14px;
    }
    .info-content .label {
        font-size: 11px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 1px;
    }
    .info-content .value {
        font-size: 14px;
        color: var(--text-main);
        font-weight: 600;
    }

    /* Tab Navigation */
    .custom-tabs {
        display: flex;
        gap: 10px;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 16px;
        margin-bottom: 25px;
    }
    .tab-btn {
        flex: 1;
        padding: 10px 15px;
        border: none;
        background: transparent;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .tab-btn i { font-size: 16px; }
    .tab-btn.active {
        background: var(--white);
        color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .tab-btn:hover:not(.active) {
        background: rgba(255,255,255,0.5);
        color: var(--text-main);
    }

    /* Content Cards */
    .medical-card {
        background: var(--white);
        border-radius: 24px;
        padding: 25px;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
        animation: fadeIn 0.4s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-title i { color: var(--primary-blue); }

    /* Consultation Items */
    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 30px;
        border-left: 2px solid var(--border-color);
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 0;
        width: 12px;
        height: 12px;
        background: var(--white);
        border: 2px solid var(--primary-blue);
        border-radius: 50%;
    }
    .timeline-date {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 5px;
    }
    .timeline-content {
        background: #f8fafc;
        border-radius: 16px;
        padding: 15px;
        border: 1px solid var(--border-color);
    }

    /* Health Vitals Grid */
    .vitals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
    }
    .vital-box {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 15px;
        text-align: center;
    }
    .vital-icon {
        font-size: 20px;
        margin-bottom: 8px;
    }
    .vital-val { font-size: 18px; font-weight: 800; color: var(--text-main); }
    .vital-lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; }

    /* Print and Actions */
    @media print {
        .profile-sidebar, .custom-tabs, .btn-actions { display: none !important; }
        .tab-pane { display: block !important; opacity: 1 !important; }
    }
</style>
@endsection

@section('content')
<div class="dossier-wrapper">
    <div class="container py-4">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="profile-sidebar">
                    <div class="text-center">
                        <div class="avatar-wrapper">
                            {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold text-main mb-1">{{ $patient->user->name }}</h4>
                        <span class="patient-status status-active">Patient Actif</span>
                    </div>

                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-venus-mars"></i></div>
                            <div class="info-content">
                                <span class="label">Genre / Âge</span>
                                <span class="value">{{ $patient->gender ?? 'Non spécifié' }} / {{ $patient->user->birth_date ? \Carbon\Carbon::parse($patient->user->birth_date)->age . ' ans' : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-tint text-danger"></i></div>
                            <div class="info-content">
                                <span class="label">Groupe Sanguin</span>
                                <span class="value">{{ $patient->blood_type ?? 'Inconnu' }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-phone"></i></div>
                            <div class="info-content">
                                <span class="label">Contact</span>
                                <span class="value">{{ $patient->user->phone ?? 'Aucun' }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-shield-alt text-success"></i></div>
                            <div class="info-content">
                                <span class="label">Assurance</span>
                                <span class="value">{{ $patient->has_cnam ? 'CNAM' : ($patient->has_mutuelle ? 'Mutuelle' : 'Aucune') }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.consultations.create') }}?patient={{ $patient->id }}" class="btn btn-primary rounded-xl py-2 fw-bold">
                            <i class="fas fa-plus-circle me-2"></i>Nouvelle Visite
                        </a>
                        <button class="btn btn-outline-secondary rounded-xl py-2 fw-bold" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Imprimer Dossier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Navigation Tabs -->
                <div class="custom-tabs">
                    <button class="tab-btn active" onclick="switchTab(event, 'overview')">
                        <i class="fas fa-th-large"></i> Aperçu
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'history')">
                        <i class="fas fa-history"></i> Historique
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'appointments')">
                        <i class="fas fa-calendar-check"></i> Rendez-vous
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'prescriptions')">
                        <i class="fas fa-pills"></i> Ordonnances
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'docs')">
                        <i class="fas fa-file-medical"></i> Documents
                    </button>
                    </div>

                    <!-- Tab: Overview -->
                    <div id="overview" class="tab-content">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="medical-card h-100">
                                <h5 class="card-title"><i class="fas fa-heartbeat"></i> Signes Vitaux (Dernier Relevé)</h5>
                                @php $lastC = $patient->consultations->first(); @endphp
                                <div class="vitals-grid">
                                    <div class="vital-box">
                                        <div class="vital-icon">🌡️</div>
                                        <div class="vital-val">{{ $lastC->temperature ?? '--' }} <small>°C</small></div>
                                        <div class="vital-lbl">Température</div>
                                    </div>
                                    <div class="vital-box">
                                        <div class="vital-icon">❤️</div>
                                        <div class="vital-val">{{ $lastC->blood_pressure ?? '--' }}</div>
                                        <div class="vital-lbl">Tension Art.</div>
                                    </div>
                                    <div class="vital-box">
                                        <div class="vital-icon">⚖️</div>
                                        <div class="vital-val">{{ $lastC->weight ?? $patient->weight ?? '--' }} <small>kg</small></div>
                                        <div class="vital-lbl">Poids</div>
                                    </div>
                                    <div class="vital-box">
                                        <div class="vital-icon">📏</div>
                                        <div class="vital-val">{{ $lastC->height ?? $patient->height ?? '--' }} <small>cm</small></div>
                                        <div class="vital-lbl">Taille</div>
                                    </div>
                                </div>
                                @if($lastC)
                                    <div class="mt-4 p-3 bg-light rounded-xl border border-dashed">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary">Dernière Note</span>
                                            <span class="text-muted extra-small">{{ $lastC->consultation_date->format('d/m/Y') }}</span>
                                        </div>
                                        <p class="mb-0 text-main small italic">"{{ Str::limit($lastC->notes, 150) }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="medical-card h-100" style="border-left: 5px solid var(--danger);">
                                <h5 class="card-title text-danger"><i class="fas fa-exclamation-triangle"></i> Alertes Médicales</h5>
                                <div class="alert-content">
                                    <label class="text-muted small fw-bold">ALLERGIES</label>
                                    <div class="p-3 mb-3 bg-danger bg-opacity-10 rounded-xl text-danger fw-bold border border-danger border-opacity-10">
                                        {{ $patient->allergies ?? 'Aucune allergie connue' }}
                                    </div>

                                    <label class="text-muted small fw-bold">ANTÉCÉDENTS</label>
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                        <p class="text-muted small mb-0">{{ $patient->medical_history ?? 'Aucun historique renseigné.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Tab: History -->
                    <div id="history" class="tab-content" style="display: none;">
                    <div class="medical-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0"><i class="fas fa-history"></i> Historique Médical</h5>
                            <span class="badge bg-primary-lighter text-primary-blue">{{ $patient->consultations->count() }} Visites</span>
                        </div>
                        @forelse($patient->consultations as $consult)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $consult->consultation_date->format('d M Y') }}</div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold text-main mb-0">Dr. {{ $consult->doctor->user->name ?? 'N/A' }}</h6>
                                            <span class="text-muted extra-small">{{ $consult->doctor->specialty ?? 'Généraliste' }}</span>
                                        </div>
                                        <button class="btn btn-sm bg-white border rounded-xl shadow-sm text-primary-blue" onclick="showConsultationDetails({{ $consult->id }})">
                                            <i class="fas fa-eye me-1"></i>Détails
                                        </button>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6 border-end border-opacity-10 border-dark">
                                            <span class="text-muted extra-small d-block text-uppercase fw-bold">Diagnostic</span>
                                            <p class="small text-main mb-0">{{ Str::limit($consult->diagnosis, 80) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted extra-small d-block text-uppercase fw-bold">Traitement</span>
                                            <p class="small text-main mb-0">{{ Str::limit($consult->treatment, 80) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-slate-200 mb-3"></i>
                                <p class="text-muted">Aucune consultation enregistrée.</p>
                            </div>
                        @endforelse
                    </div>
                    </div>

                    <!-- Tab: Appointments -->
                    <div id="appointments" class="tab-content" style="display: none;">
                    <div class="medical-card">
                        <h5 class="card-title"><i class="fas fa-calendar-check"></i> Rendez-vous</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start">Date & Heure</th>
                                        <th class="border-0">Motif</th>
                                        <th class="border-0">Statut</th>
                                        <th class="border-0 rounded-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->appointments as $apt)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-main">{{ \Carbon\Carbon::parse($apt->date_time)->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ \Carbon\Carbon::parse($apt->date_time)->format('H:i') }}</div>
                                            </td>
                                            <td class="small">{{ $apt->reason ?? 'Consultation générale' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-warning text-dark',
                                                        'confirmed' => 'bg-success',
                                                        'cancelled' => 'bg-danger',
                                                        'completed' => 'bg-secondary',
                                                        'scheduled' => 'bg-info text-white'
                                                    ][$apt->status] ?? 'bg-light';
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($apt->status) }}</span>
                                            </td>
                                            <td>
                                                @if($apt->status == 'confirmed' || $apt->status == 'scheduled')
                                                    <a href="{{ route('doctor.consultations.create') }}?patient={{ $patient->id }}&appointment={{ $apt->id }}" class="btn btn-sm btn-primary rounded-xl">Démarrer</a>
                                                @else
                                                    <button class="btn btn-sm btn-light rounded-xl" disabled>Terminé</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Aucun rendez-vous trouvé.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                <!-- Tab: Prescriptions -->
                <div id="prescriptions" class="tab-content" style="display: none;">
                    <div class="medical-card">
                        <h5 class="card-title"><i class="fas fa-pills"></i> Ordonnances Émises</h5>
                        <div class="row g-3">
                            @forelse($patient->prescriptions as $presc)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-24 d-flex justify-content-between align-items-center hover-shadow transition-all">
                                        <div>
                                            <div class="fw-bold text-main">Ordonnance #{{ $presc->id }}</div>
                                            <div class="text-muted small">Émise le : {{ $presc->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        <a href="{{ route('prescriptions.pdf', $presc) }}" target="_blank" class="btn btn-icon btn-light text-danger rounded-xl">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">Aucune ordonnance trouvée.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Documents -->
                <div id="docs" class="tab-content" style="display: none;">
                    <div class="medical-card">
                        <h5 class="card-title"><i class="fas fa-file-medical"></i> Documents & Imagerie</h5>
                        <div class="row g-3">
                            @forelse($patient->documents as $doc)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-24 d-flex justify-content-between align-items-center hover-shadow transition-all">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 p-2 bg-light rounded-circle text-primary">
                                                <i class="fas {{ $doc->type_icon }} fa-lg"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-main">{{ $doc->title }}</div>
                                                <div class="text-muted small">{{ $doc->type_label }} • {{ $doc->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-icon btn-light text-primary rounded-xl">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-slate-200 mb-3"></i>
                                    <p class="text-muted">Aucun document trouvé pour ce patient.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Consultation (Re-used) -->
<div class="modal fade modal-medical" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="fas fa-stethoscope me-2"></i> Détails de la consultation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="consultationDetails">
                <!-- Content injected via JS -->
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

function showConsultationDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('consultationModal'));
    modal.show();

    const detailsDiv = document.getElementById('consultationDetails');
    detailsDiv.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    fetch(`/consultations/${id}/details`)
        .then(response => response.json())
        .then(data => {
            const date = new Date(data.consultation_date).toLocaleDateString('fr-FR');
            detailsDiv.innerHTML = `
                <div class="p-3">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Infos Visite</h6>
                            <p class="mb-1 text-muted small">DATE</p><p class="fw-bold">${date}</p>
                            <p class="mb-1 text-muted small">MÉDECIN</p><p class="fw-bold">Dr. ${data.doctor?.user?.name || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Vitals</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="p-2 border rounded-xl text-center flex-fill">
                                    <div class="small text-muted">Tension</div><div class="fw-bold">${data.blood_pressure || '--'}</div>
                                </div>
                                <div class="p-2 border rounded-xl text-center flex-fill">
                                    <div class="small text-muted">Temp.</div><div class="fw-bold">${data.temperature || '--'}°C</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold text-main">Symptômes</h6>
                        <div class="p-3 bg-light rounded-xl">${data.symptoms || 'Non renseignés'}</div>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold text-main">Diagnostic</h6>
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">${data.diagnosis || 'Non renseigné'}</div>
                    </div>
                    <div class="mb-0">
                        <h6 class="fw-bold text-main">Traitement</h6>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">${data.treatment || 'Non renseigné'}</div>
                    </div>
                </div>
            `;
        });
}
</script>
@endsection