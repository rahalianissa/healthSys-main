@extends('layouts.app')

@section('title', 'Mes consultations')
@section('page-title', 'Liste des consultations')

@section('styles')
<style>
    .consultation-card {
        transition: all 0.3s ease;
        border-left: 4px solid #1a5f7a;
    }
    .consultation-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    .stat-badge.diagnosis {
        background: #e8f4f8;
        color: #1a5f7a;
    }
    .stat-badge.treatment {
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
    .btn-action {
        transition: all 0.2s;
        margin: 0 2px;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .patient-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1a5f7a, #0d3b4f);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
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
    .empty-state h5 {
        color: #64748b;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #94a3b8;
        margin-bottom: 20px;
    }
    
    @media print {
        .no-print, .btn-group, .btn-action, .table-hover tbody tr:hover {
            display: none !important;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="consultation-card card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0">
            <i class="fas fa-stethoscope me-2 text-primary"></i> Mes consultations
        </h5>
        <div class="mt-2 mt-md-0">
            <span class="badge bg-primary rounded-pill">
                <i class="fas fa-chart-line me-1"></i> Total: {{ $consultations->count() }}
            </span>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($consultations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Diagnostic</th>
                            <th>Traitement</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultations as $consultation)
                        <tr class="consultation-row">
                            <td class="fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="patient-avatar me-2">
                                        {{ substr($consultation->patient->user->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $consultation->patient->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt me-1"></i> {{ $consultation->patient->user->phone ?? 'Non renseigné' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $consultation->consultation_date->format('d/m/Y') }}</span>
                                <br>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i> {{ $consultation->consultation_date->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @if($consultation->diagnosis)
                                    <span class="stat-badge diagnosis">
                                        <i class="fas fa-clipboard-list me-1"></i> {{ Str::limit($consultation->diagnosis, 40) }}
                                    </span>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                            <td>
                                @if($consultation->treatment)
                                    <span class="stat-badge treatment">
                                        <i class="fas fa-pills me-1"></i> {{ Str::limit($consultation->treatment, 40) }}
                                    </span>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ url('/consultations/'.$consultation->id) }}" 
                                       class="btn btn-info btn-action" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @php
                                        $prescription = $consultation->prescriptions->first();
                                    @endphp
                                    @if($prescription)
                                        <a href="{{ url('/prescriptions/'.$prescription->id.'/pdf') }}" 
                                           class="btn btn-danger btn-action" 
                                           title="Télécharger ordonnance PDF"
                                           target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-secondary btn-action" 
                                            onclick="window.print()" 
                                            title="Imprimer">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (si nécessaire) -->
            @if(method_exists($consultations, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $consultations->links() }}
            </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-stethoscope"></i>
                <h5>Aucune consultation enregistrée</h5>
                <p>Vous n'avez pas encore effectué de consultations.</p>
                <a href="{{ url('/doctor/dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Retour au tableau de bord
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Statistiques rapides -->
@if($consultations->count() > 0)
<div class="row mt-4 no-print">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Total consultations</small>
                        <h3 class="mb-0">{{ $consultations->count() }}</h3>
                    </div>
                    <i class="fas fa-stethoscope fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Avec diagnostic</small>
                        <h3 class="mb-0">{{ $consultations->whereNotNull('diagnosis')->count() }}</h3>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Avec traitement</small>
                        <h3 class="mb-0">{{ $consultations->whereNotNull('treatment')->count() }}</h3>
                    </div>
                    <i class="fas fa-pills fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Ce mois</small>
                        <h3 class="mb-0">{{ $consultations->where('consultation_date', '>=', now()->startOfMonth())->count() }}</h3>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
/* Animation pour les lignes du tableau */
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

.consultation-row {
    animation: fadeInUp 0.3s ease forwards;
    animation-delay: calc(0.05s * var(--index, 0));
}

/* Amélioration du scroll sur mobile */
@media (max-width: 768px) {
    .table-responsive {
        margin-bottom: 0;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .patient-avatar {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
}
</style>
@endsection