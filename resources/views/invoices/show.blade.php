{{-- resources/views/invoices/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Facture #' . $invoice->invoice_number)
@section('page-title', 'Détails de la facture')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        
        <div id="invoice-print" class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">HealthSys</h3>
                        <p class="text-muted">Cabinet médical</p>
                    </div>
                    <div class="col-6 text-end">
                        <h5>Facture #{{ $invoice->invoice_number }}</h5>
                        <p class="text-muted">Date: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                {{-- Patient Info --}}
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="border p-3 rounded">
                            <strong><i class="fas fa-user me-2"></i>Patient:</strong><br>
                            {{ $invoice->patient->user->name }}<br>
                            <i class="fas fa-envelope me-2"></i>{{ $invoice->patient->user->email }}<br>
                            <i class="fas fa-phone me-2"></i>{{ $invoice->patient->user->phone ?? 'Non renseigné' }}
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="border p-3 rounded">
                            <strong><i class="fas fa-calendar me-2"></i>Dates:</strong><br>
                            <i class="fas fa-calendar-plus me-2"></i>Émission: {{ $invoice->issue_date->format('d/m/Y') }}<br>
                            <i class="fas fa-calendar-check me-2"></i>Échéance: {{ $invoice->due_date->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                
                {{-- Insurance Breakdown (NEW - Important for PFE) --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #1a5f7a, #0d3b4f);">
                        <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Détail des prises en charge</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="border rounded p-3 {{ $invoice->cnam_paid ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                    <h6>CNAM</h6>
                                    <h4 class="text-primary">{{ number_format($invoice->cnam_amount, 2) }} DT</h4>
                                    @if($invoice->cnam_paid)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>En attente</span>
                                    @endif
                                    @if($invoice->cnam_reference)
                                        <small class="d-block text-muted mt-2">Ref: {{ $invoice->cnam_reference }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 {{ $invoice->mutuelle_paid ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                                    <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                    <h6>Mutuelle</h6>
                                    <h4 class="text-success">{{ number_format($invoice->mutuelle_amount, 2) }} DT</h4>
                                    @if($invoice->mutuelle_paid)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>En attente</span>
                                    @endif
                                    @if($invoice->mutuelle_reference)
                                        <small class="d-block text-muted mt-2">Ref: {{ $invoice->mutuelle_reference }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 {{ $invoice->patient_paid ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                                    <i class="fas fa-user fa-2x text-danger mb-2"></i>
                                    <h6>Patient</h6>
                                    <h4 class="text-danger">{{ number_format($invoice->patient_amount, 2) }} DT</h4>
                                    @if($invoice->patient_paid)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>En attente</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        @php
                            $totalAmount = $invoice->amount;
                            $cnamPercent = $totalAmount > 0 ? round(($invoice->cnam_amount / $totalAmount) * 100, 1) : 0;
                            $mutuellePercent = $totalAmount > 0 ? round(($invoice->mutuelle_amount / $totalAmount) * 100, 1) : 0;
                            $patientPercent = $totalAmount > 0 ? round(($invoice->patient_amount / $totalAmount) * 100, 1) : 0;
                        @endphp
                        
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>CNAM ({{ $cnamPercent }}%)</span>
                                <span>Mutuelle ({{ $mutuellePercent }}%)</span>
                                <span>Patient ({{ $patientPercent }}%)</span>
                            </div>
                            <div class="progress" style="height: 30px; border-radius: 15px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $cnamPercent }}%">
                                    {{ $invoice->cnam_amount > 0 ? number_format($invoice->cnam_amount, 2) : '' }}
                                </div>
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $mutuellePercent }}%">
                                    {{ $invoice->mutuelle_amount > 0 ? number_format($invoice->mutuelle_amount, 2) : '' }}
                                </div>
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ $patientPercent }}%">
                                    {{ $invoice->patient_amount > 0 ? number_format($invoice->patient_amount, 2) : '' }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- Legend --}}
                        <div class="row mt-4 text-center small">
                            <div class="col-md-4">
                                <span class="badge bg-primary me-1">&nbsp;&nbsp;&nbsp;</span> CNAM (Premier remboursement)
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-success me-1">&nbsp;&nbsp;&nbsp;</span> Mutuelle (Second remboursement)
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-danger me-1">&nbsp;&nbsp;&nbsp;</span> Patient (Reste à charge)
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Invoice Items --}}
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Quantité</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $invoice->description ?? 'Consultation médicale' }}</td>
                            <td class="text-end">1</td>
                            <td class="text-end">{{ number_format($invoice->amount, 2) }} DT</td>
                            <td class="text-end">{{ number_format($invoice->amount, 2) }} DT</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="table-primary">
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">{{ number_format($invoice->amount, 2) }} DT</th>
                        </tr>
                        <tr class="table-info">
                            <th colspan="3" class="text-end">Pris en charge par CNAM</th>
                            <th class="text-end text-primary">- {{ number_format($invoice->cnam_amount, 2) }} DT</th>
                        </tr>
                        @if($invoice->mutuelle_amount > 0)
                        <tr class="table-success">
                            <th colspan="3" class="text-end">Pris en charge par Mutuelle</th>
                            <th class="text-end text-success">- {{ number_format($invoice->mutuelle_amount, 2) }} DT</th>
                        </tr>
                        @endif
                        <tr class="table-danger">
                            <th colspan="3" class="text-end">Reste à charge patient</th>
                            <th class="text-end text-danger">{{ number_format($invoice->patient_amount, 2) }} DT</th>
                        </tr>
                    </tfoot>
                </table>
                
                {{-- Payment History --}}
                @if($invoice->payments && $invoice->payments->count() > 0)
                <div class="mt-4">
                    <h6><i class="fas fa-history me-2"></i>Historique des paiements</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Type</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                                <td class="text-success">{{ number_format($payment->amount, 2) }} DT</td>
                                <td>
                                    @if($payment->payment_method == 'cash') 💰 Espèces
                                    @elseif($payment->payment_method == 'card') 💳 Carte
                                    @elseif($payment->payment_method == 'check') 📝 Chèque
                                    @elseif($payment->payment_method == 'transfer') 🏦 Virement
                                    @endif
                                </td>
                                <td>
                                    @if(str_contains($payment->notes, 'CNAM'))
                                        <span class="badge bg-primary">CNAM</span>
                                    @elseif(str_contains($payment->notes, 'MUTUELLE'))
                                        <span class="badge bg-success">Mutuelle</span>
                                    @else
                                        <span class="badge bg-danger">Patient</span>
                                    @endif
                                </td>
                                <td>{{ $payment->transaction_id ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <div class="mt-4 text-center">
                    <p class="text-muted small">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Cette facture est générée électroniquement et fait foi
                    </p>
                </div>
            </div>
            
            <div class="card-footer text-center bg-white">
                <p class="mb-0">Merci de votre confiance !</p>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="text-center mt-4 no-print">
            <div class="btn-group">
                <button onclick="printSection('invoice-print', 'Facture #{{ $invoice->invoice_number }}')" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i> Imprimer
                </button>
                <button onclick="exportToPDF('invoice-print', 'facture_{{ $invoice->invoice_number }}')" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i> Exporter PDF
                </button>
                @if(auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire')
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                @endif
            </div>
            
            {{-- Payment Buttons for remaining amounts --}}
            @php $remaining = $invoice->remaining_breakdown; @endphp
            <div class="mt-3">
                @if($remaining['cnam'] > 0 && (auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire'))
                    <button class="btn btn-outline-primary" onclick="showPaymentModal('cnam', {{ $remaining['cnam'] }})">
                        <i class="fas fa-building me-2"></i> Encaisser CNAM ({{ number_format($remaining['cnam'], 2) }} DT)
                    </button>
                @endif
                @if($remaining['mutuelle'] > 0 && (auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire'))
                    <button class="btn btn-outline-success" onclick="showPaymentModal('mutuelle', {{ $remaining['mutuelle'] }})">
                        <i class="fas fa-handshake me-2"></i> Encaisser Mutuelle ({{ number_format($remaining['mutuelle'], 2) }} DT)
                    </button>
                @endif
                @if($remaining['patient'] > 0)
                    <button class="btn btn-outline-danger" onclick="showPaymentModal('patient', {{ $remaining['patient'] }})">
                        <i class="fas fa-user me-2"></i> Paiement patient ({{ number_format($remaining['patient'], 2) }} DT)
                    </button>
                @endif
            </div>
            
            <div class="mt-3">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                </a>
            </div>
        </div>
        
    </div>
</div>

{{-- Payment Modal --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalTitle">Encaissement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('invoices.processPayment', $invoice) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="payment_type" id="payment_type">
                    <div class="mb-3">
                        <label class="form-label">Montant à encaisser</label>
                        <input type="number" step="0.01" name="amount" id="payment_amount" class="form-control" readonly>
                    </div>
                    <div class="mb-3" id="reference_field" style="display: none;">
                        <label class="form-label">Numéro de référence</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="Numéro de bon / attestation">
                    </div>
                    <div class="mb-3" id="method_field">
                        <label class="form-label">Mode de paiement</label>
                        <select name="payment_method" class="form-control">
                            <option value="cash">💵 Espèces</option>
                            <option value="card">💳 Carte bancaire</option>
                            <option value="check">📝 Chèque</option>
                            <option value="transfer">🏦 Virement</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function printSection(elementId, title) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    const content = element.cloneNode(true);
    content.querySelectorAll('.no-print, .btn, button').forEach(el => el.remove());
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>
            <meta charset="UTF-8">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; font-family: Arial, sans-serif; }
                @media print { body { padding: 0; } }
            </style>
        </head>
        <body>
            ${content.outerHTML}
            <script>window.print(); setTimeout(() => window.close(), 500);<\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function exportToPDF(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    const content = element.cloneNode(true);
    content.querySelectorAll('.no-print, .btn, button').forEach(el => el.remove());
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${filename}</title>
            <meta charset="UTF-8">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; font-family: Arial, sans-serif; }
            </style>
        </head>
        <body>
            ${content.outerHTML}
            <script>window.print();<\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function showPaymentModal(type, amount) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const titleMap = {
        'cnam': 'Encaissement CNAM',
        'mutuelle': 'Encaissement Mutuelle',
        'patient': 'Paiement Patient'
    };
    
    document.getElementById('paymentModalTitle').innerText = titleMap[type];
    document.getElementById('payment_type').value = type;
    document.getElementById('payment_amount').value = amount;
    
    // Show/hide reference field for insurance payments
    if (type === 'cnam' || type === 'mutuelle') {
        document.getElementById('reference_field').style.display = 'block';
    } else {
        document.getElementById('reference_field').style.display = 'none';
    }
    
    modal.show();
}
</script>
@endsection