@extends('layouts.app')

@section('title', 'Résultats de recherche')
@section('page-title', 'Recherche : "' . $query . '"')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2 text-primary"></i>
                    Résultats de recherche
                </h5>
                <span class="badge bg-primary rounded-pill">{{ $total }} résultat(s)</span>
            </div>
            <div class="card-body">
                
                @if($total == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3 opacity-25"></i>
                        <h5 class="text-muted">Aucun résultat trouvé</h5>
                        <p class="text-muted small">Essayez avec d'autres mots-clés</p>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                @else
                    
                    <!-- Patients -->
                    @if(isset($results['patients']) && $results['patients']->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-users text-primary me-2"></i>
                            Patients ({{ $results['patients']->count() }})
                        </h6>
                        <div class="list-group">
                            @foreach($results['patients'] as $patient)
                            <a href="{{ $patient['url'] }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $patient['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>{{ $patient['email'] ?? 'Email non renseigné' }}
                                                    @if(isset($patient['phone']))
                                                    &nbsp;|&nbsp;
                                                    <i class="fas fa-phone me-1"></i>{{ $patient['phone'] }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Médecins -->
                    @if(isset($results['doctors']) && $results['doctors']->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-user-md text-success me-2"></i>
                            Médecins ({{ $results['doctors']->count() }})
                        </h6>
                        <div class="list-group">
                            @foreach($results['doctors'] as $doctor)
                            <a href="{{ $doctor['url'] }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user-md text-success"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $doctor['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-stethoscope me-1"></i>{{ $doctor['specialty'] ?? 'Généraliste' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Rendez-vous -->
                    @if(isset($results['appointments']) && $results['appointments']->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>
                            Rendez-vous ({{ $results['appointments']->count() }})
                        </h6>
                        <div class="list-group">
                            @foreach($results['appointments'] as $appointment)
                            <a href="{{ $appointment['url'] }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-calendar-check text-warning"></i>
                                            </div>
                                            <div>
                                                <strong>
                                                    @if(isset($appointment['patient']))
                                                        {{ $appointment['patient'] }}
                                                    @elseif(isset($appointment['doctor']))
                                                        Dr. {{ $appointment['doctor'] }}
                                                    @endif
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar me-1"></i>{{ $appointment['date'] }}
                                                    @if(isset($appointment['status']))
                                                    &nbsp;|&nbsp;
                                                    <span class="badge bg-{{ $appointment['status'] == 'confirmed' ? 'success' : ($appointment['status'] == 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ $appointment['status'] }}
                                                    </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Ordonnances -->
                    @if(isset($results['prescriptions']) && $results['prescriptions']->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-prescription text-danger me-2"></i>
                            Ordonnances ({{ $results['prescriptions']->count() }})
                        </h6>
                        <div class="list-group">
                            @foreach($results['prescriptions'] as $prescription)
                            <a href="{{ $prescription['url'] }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-file-prescription text-danger"></i>
                                            </div>
                                            <div>
                                                <strong>Ordonnance du {{ $prescription['date'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-user-md me-1"></i>{{ $prescription['doctor'] }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                @endif
            </div>
        </div>
    </div>
</div>
@endsection