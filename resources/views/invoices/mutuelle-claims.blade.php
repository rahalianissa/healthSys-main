@extends('layouts.app')

@section('page_title', 'Demandes de remboursement Mutuelle')
@section('page_subtitle', 'Gestion des demandes de remboursement aux mutuelles')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Demandes Mutuelle</h1>
            <p class="text-muted">Gérez les demandes de remboursement aux mutuelles</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
    </div>
    
    @if($claims->count() > 0)
        <div class="row">
            @foreach($claims as $claim)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Facture #{{ $claim->invoice_number }}</strong>
                            <span class="badge bg-warning">En attente</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Patient:</strong>
                            <p class="mb-0">{{ $claim->patient->user->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $claim->patient->user->email ?? 'N/A' }}</small>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Montant total</small>
                                <div class="fw-bold">{{ number_format($claim->amount, 2) }} DT</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Part Mutuelle</small>
                                <div class="fw-bold text-success">{{ number_format($claim->mutuelle_amount, 2) }} DT</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Date facture</small>
                            <div>{{ $claim->issue_date->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button onclick="markAsPaid({{ $claim->id }}, 'mutuelle')" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle me-2"></i> Marquer comme remboursé
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-handshake fa-3x mb-3"></i>
            <p class="mb-0">Aucune demande de remboursement mutuelle en attente</p>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function markAsPaid(invoiceId, type) {
        Swal.fire({
            title: 'Confirmation',
            text: 'Marquer cette demande comme remboursée ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Oui, marquer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/invoices/' + invoiceId + '/pay?type=' + type;
            }
        });
    }
</script>
@endsection