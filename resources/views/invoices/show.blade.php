@extends('layouts.app')

@section('title', 'Facture #' . $invoice->invoice_number)
@section('page-title', 'Détails de la facture')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        
        <!-- Section à imprimer -->
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
                        @if($invoice->paid_amount > 0)
                        <tr>
                            <th colspan="3" class="text-end">Sous-total</th>
                            <th class="text-end">{{ number_format($invoice->amount, 2) }} DT</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end text-success">Déjà payé</th>
                            <th class="text-end text-success">{{ number_format($invoice->paid_amount, 2) }} DT</th>
                        </tr>
                        @endif
                        @php $remaining = $invoice->amount - $invoice->paid_amount; @endphp
                        <tr class="table-primary">
                            <th colspan="3" class="text-end">Total à payer</th>
                            <th class="text-end">{{ number_format($remaining, 2) }} DT</th>
                        </tr>
                    </tfoot>
                </table>
                
                @if($invoice->payments && $invoice->payments->count() > 0)
                <div class="mt-4">
                    <h6><i class="fas fa-history me-2"></i>Historique des paiements</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Montant</th><th>Mode</th><th>Référence</th></tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td class="text-success">{{ number_format($payment->amount, 2) }} DT</td>
                                <td>
                                    @if($payment->payment_method == 'cash') 💰 Espèces
                                    @elseif($payment->payment_method == 'card') 💳 Carte
                                    @elseif($payment->payment_method == 'check') 📝 Chèque
                                    @elseif($payment->payment_method == 'transfer') 🏦 Virement
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
        
        <!-- Boutons d'action -->
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
                @if($remaining > 0 && $invoice->status != 'cancelled' && (auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire'))
                    <a href="{{ route('invoices.pay', $invoice) }}" class="btn btn-success">
                        <i class="fas fa-money-bill me-2"></i> Encaisser
                    </a>
                @endif
            </div>
            <div class="mt-3">
                <!-- ===== BOUTON RETOUR CORRIGÉ SELON LE RÔLE ===== -->
                @if(auth()->user()->role == 'patient')
                    <a href="{{ route('patient.invoices') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à mes factures
                    </a>
                @elseif(auth()->user()->role == 'secretaire')
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste des factures
                    </a>
                @elseif(auth()->user()->role == 'chef_medecine')
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste des factures
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </a>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
function printSection(elementId, title) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    const content = element.cloneNode(true);
    
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
                .no-print { display: none; }
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
</script>
@endpush