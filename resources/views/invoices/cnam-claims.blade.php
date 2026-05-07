@extends('layouts.app')

@section('page_title', 'Demandes de remboursement CNAM')
@section('page_subtitle', 'Gestion des demandes de remboursement à la CNAM')

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-soft: #48CAE4;
        --primary-bg: #CAF0F8;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .claim-card {
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .claim-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .claim-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .claim-body {
        padding: 20px;
    }
    
    .claim-footer {
        padding: 16px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .patient-avatar {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: white;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }
    
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-dark);
    }
    
    .amount-cnam {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary-blue);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 62, 138, 0.2);
    }
    
    .btn-outline-custom {
        background: transparent;
        border: 1px solid var(--primary-light);
        color: var(--primary-blue);
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-outline-custom:hover {
        background: var(--primary-bg);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-state-icon i {
        font-size: 36px;
        color: var(--primary-blue);
    }
    
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
    
    .animate-fade-up {
        animation: fadeInUp 0.5s ease forwards;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-building text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">CNAM - REQUÊTES DE REMBOURSEMENT</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Demandes CNAM</h1>
        <p class="text-white/60 text-sm">Gérez les demandes de remboursement à la CNAM</p>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Demandes en attente</div>
                <div class="stats-value">{{ $claims->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Montant total à rembourser</div>
                <div class="stats-value">{{ number_format($claims->sum('cnam_amount'), 0) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-tag"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Factures concernées</div>
                <div class="stats-value">{{ $claims->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-file-invoice"></i>
            </div>
        </div>
    </div>
</div>

<!-- Liste des demandes -->
@if($claims->count() > 0)
    <div class="space-y-4">
        @foreach($claims as $claim)
        <div class="claim-card animate-fade-up" style="animation-delay: {{ 0.2 + ($loop->iteration * 0.05) }}s">
            <div class="claim-header">
                <div class="flex items-center gap-3">
                    <div class="patient-avatar">
                        {{ strtoupper(substr($claim->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $claim->patient->user->name ?? 'N/A' }}</h3>
                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                            <span><i class="fas fa-envelope mr-1"></i> {{ $claim->patient->user->email ?? 'N/A' }}</span>
                            <span><i class="fas fa-phone mr-1"></i> {{ $claim->patient->user->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="text-xs font-mono bg-slate-100 px-3 py-1 rounded-full">{{ $claim->invoice_number }}</span>
                </div>
            </div>
            
            <div class="claim-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="info-row flex-col items-start gap-1 border-0">
                        <div class="info-label">Date facture</div>
                        <div class="info-value">{{ $claim->issue_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row flex-col items-start gap-1 border-0">
                        <div class="info-label">Montant total</div>
                        <div class="info-value">{{ number_format($claim->amount, 2) }} DT</div>
                    </div>
                    <div class="info-row flex-col items-start gap-1 border-0">
                        <div class="info-label">Part CNAM</div>
                        <div class="amount-cnam">{{ number_format($claim->cnam_amount, 2) }} DT</div>
                    </div>
                </div>
                
                @if($claim->cnam_reference)
                <div class="mt-3 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-hashtag text-slate-400"></i>
                        <span class="text-slate-500">Référence CNAM:</span>
                        <span class="font-mono text-slate-700">{{ $claim->cnam_reference }}</span>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="claim-footer">
                <button onclick="markAsPaid({{ $claim->id }}, 'cnam')" class="btn-primary-custom">
                    <i class="fas fa-check-circle mr-2"></i> Marquer comme remboursé
                </button>
                <button onclick="showClaimDetails({{ $claim->id }})" class="btn-outline-custom">
                    <i class="fas fa-eye mr-2"></i> Détails
                </button>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade-up">
        <div class="empty-state-icon">
            <i class="fas fa-building"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune demande en attente</h3>
        <p class="text-slate-500">Toutes les demandes de remboursement CNAM ont été traitées</p>
    </div>
@endif

<!-- Modal Détails -->
<div class="modal fade" id="claimModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #023E8A, #03045E); border: none; padding: 20px 24px;">
                <h5 class="modal-title text-white">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Détails de la demande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-6" id="modalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function markAsPaid(invoiceId, type) {
        Swal.fire({
            title: 'Confirmation',
            text: 'Marquer cette demande comme remboursée ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#023E8A',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Oui, marquer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Rediriger vers la page de paiement avec le type
                window.location.href = '/invoices/' + invoiceId + '/pay?type=' + type;
            }
        });
    }
    
    function showClaimDetails(invoiceId) {
        const modal = new bootstrap.Modal(document.getElementById('claimModal'));
        modal.show();
        
        fetch(`/invoices/${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-slate-500 text-xs mb-1">N° Facture</div>
                                <div class="font-mono font-bold text-slate-800">${data.invoice_number}</div>
                            </div>
                            <div>
                                <div class="text-slate-500 text-xs mb-1">Date d'émission</div>
                                <div class="font-medium text-slate-700">${new Date(data.issue_date).toLocaleDateString('fr-FR')}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 p-3 bg-slate-50 rounded-xl">
                            <div class="text-center">
                                <div class="text-slate-500 text-xs mb-1">Montant total</div>
                                <div class="font-bold text-slate-800">${data.amount.toFixed(2)} DT</div>
                            </div>
                            <div class="text-center">
                                <div class="text-slate-500 text-xs mb-1">Part CNAM</div>
                                <div class="font-bold text-primary-blue">${data.cnam_amount.toFixed(2)} DT</div>
                            </div>
                            <div class="text-center">
                                <div class="text-slate-500 text-xs mb-1">Status</div>
                                <div class="font-bold ${data.cnam_paid ? 'text-success' : 'text-warning'}">
                                    ${data.cnam_paid ? 'Remboursé' : 'En attente'}
                                </div>
                            </div>
                        </div>
                        ${data.description ? `
                        <div>
                            <div class="text-slate-500 text-xs mb-1">Description</div>
                            <div class="text-slate-600">${data.description}</div>
                        </div>
                        ` : ''}
                    </div>
                `;
                document.getElementById('modalContent').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>Erreur lors du chargement des détails</p>
                    </div>
                `;
            });
    }
</script>

@endsection