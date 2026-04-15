@extends('layouts.app')

@section('title', 'Modifier la facture')
@section('page-title', 'Modification de la facture #' . $invoice->invoice_number)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Modifier la facture</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="editInvoiceForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">N° Facture</label>
                            <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" disabled>
                            <small class="text-muted">Le numéro de facture ne peut pas être modifié</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de création</label>
                            <input type="text" class="form-control" value="{{ $invoice->created_at->format('d/m/Y H:i') }}" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }} - {{ $patient->user->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Montant (DT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount', $invoice->amount) }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'émission <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror" 
                                   value="{{ $invoice->issue_date->format('Y-m-d') }}" required>
                            @error('issue_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $invoice->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>📋 En attente</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>✅ Payée</option>
                            <option value="partially_paid" {{ $invoice->status == 'partially_paid' ? 'selected' : '' }}>🟡 Partiellement payée</option>
                            <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                        </select>
                        <small class="text-muted">Le statut sera automatiquement mis à jour si vous modifiez le montant payé</small>
                    </div>

                    @if($invoice->payments && $invoice->payments->count() > 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information:</strong> Cette facture a {{ $invoice->payments->count() }} paiement(s) enregistré(s).
                        La modification du montant total peut affecter le statut de la facture.
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between gap-2">
                        <div>
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Annuler
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Information sur les paiements -->
        @if($invoice->payments && $invoice->payments->count() > 0)
        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Historique des paiements</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Référence</th>
                                <th>Statut</th>
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
                                <td>{{ $payment->transaction_id ?? '-' }}</td>
                                <td><span class="badge bg-success">Validé</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total payé</th>
                                <th colspan="4">{{ number_format($invoice->payments->sum('amount'), 2) }} DT</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Récapitulatif -->
        <div class="card mt-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted">Montant total</small>
                        <h5 class="text-primary mb-0" id="totalAmount">{{ number_format($invoice->amount, 2) }} DT</h5>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Déjà payé</small>
                        <h5 class="text-success mb-0">{{ number_format($invoice->paid_amount, 2) }} DT</h5>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Reste à payer</small>
                        <h5 class="text-danger mb-0">{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mise à jour dynamique du total
document.querySelector('input[name="amount"]')?.addEventListener('change', function() {
    const newAmount = parseFloat(this.value) || 0;
    const paidAmount = {{ $invoice->paid_amount }};
    const remaining = newAmount - paidAmount;
    
    document.getElementById('totalAmount').innerText = newAmount.toFixed(2) + ' DT';
    
    const remainingElement = document.querySelector('.text-danger.mb-0');
    if (remainingElement) {
        remainingElement.innerText = remaining.toFixed(2) + ' DT';
        if (remaining < 0) {
            remainingElement.style.color = '#dc3545';
        } else if (remaining === 0) {
            remainingElement.style.color = '#28a745';
        }
    }
});
</script>
@endsection