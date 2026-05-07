@extends('layouts.app')

@section('page_title', 'Modifier la facture')
@section('page_subtitle', 'Facture n° ' . $invoice->invoice_number)

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
    
    .form-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    
    .form-header {
        padding: 20px 28px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    .form-body {
        padding: 28px;
    }
    
    .form-footer {
        padding: 20px 28px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    .info-box {
        background: var(--primary-bg);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
        border-left: 4px solid var(--primary-blue);
    }
    
    .info-box-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-box-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .info-item {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        text-align: center;
    }
    
    .info-item-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
    }
    
    .info-item-value {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .info-item-value.paid {
        color: var(--success);
    }
    
    .info-item-value.remaining {
        color: var(--danger);
    }
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-label .required {
        color: var(--danger);
        margin-left: 4px;
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .input-group-custom {
        position: relative;
    }
    
    .input-group-custom .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    
    .input-group-custom .form-control {
        padding-left: 40px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 62, 138, 0.2);
    }
    
    .btn-secondary-custom {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-secondary-custom:hover {
        background: #e2e8f0;
    }
    
    .btn-danger-custom {
        background: #fef2f2;
        color: var(--danger);
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-danger-custom:hover {
        background: #fee2e2;
    }
    
    .payment-history-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payment-history-table th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .payment-history-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    
    .alert-info-custom {
        background: var(--primary-bg);
        border: 1px solid rgba(0, 180, 216, 0.2);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-info-custom i {
        font-size: 20px;
        color: var(--primary-light);
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
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-file-invoice-dollar text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">FACTURATION</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier la facture</h1>
            <p class="text-white/60 text-sm">N° {{ $invoice->invoice_number }}</p>
        </div>
        <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center gap-2 bg-white/10 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-white/20 transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Formulaire principal -->
    <div class="lg:col-span-2 form-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="form-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center">
                    <i class="fas fa-edit text-primary-blue text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Informations de la facture</h3>
                    <p class="text-sm text-slate-500">Modifiez les détails de la facture</p>
                </div>
            </div>
        </div>
        
        <div class="form-body">
            <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="editInvoiceForm">
                @csrf
                @method('PUT')
                
                <!-- Patient -->
                <div class="mb-5">
                    <label class="form-label">
                        Patient <span class="required">*</span>
                    </label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }} - {{ $patient->user->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('patient_id')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Montant -->
                <div class="mb-5">
                    <label class="form-label">
                        Montant (DT) <span class="required">*</span>
                    </label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount', $invoice->amount) }}" required id="amountInput">
                    </div>
                    @error('amount')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label">
                            Date d'émission <span class="required">*</span>
                        </label>
                        <div class="input-group-custom">
                            <div class="input-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror" 
                                   value="{{ $invoice->issue_date->format('Y-m-d') }}" required>
                        </div>
                        @error('issue_date')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">
                            Date d'échéance <span class="required">*</span>
                        </label>
                        <div class="input-group-custom">
                            <div class="input-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                        </div>
                        @error('due_date')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Statut -->
                <div class="mb-5">
                    <label class="form-label">Statut</label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fas fa-chart-simple"></i>
                        </div>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>📋 En attente</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>✅ Payée</option>
                            <option value="partially_paid" {{ $invoice->status == 'partially_paid' ? 'selected' : '' }}>🟡 Partiellement payée</option>
                            <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                        </select>
                    </div>
                    <div class="text-xs text-slate-400 mt-2">
                        <i class="fas fa-info-circle"></i> Le statut sera automatiquement mis à jour si vous modifiez le montant payé
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-5">
                    <label class="form-label">Description</label>
                    <div class="input-group-custom">
                        <div class="input-icon" style="top: 16px; transform: none;">
                            <i class="fas fa-align-left"></i>
                        </div>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" style="padding-left: 40px;">{{ old('description', $invoice->description) }}</textarea>
                    </div>
                    @error('description')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                @if($invoice->payments && $invoice->payments->count() > 0)
                <div class="alert-info-custom">
                    <i class="fas fa-info-circle"></i>
                    <div class="text-sm text-slate-600">
                        <strong>Information :</strong> Cette facture a {{ $invoice->payments->count() }} paiement(s) enregistré(s).
                        La modification du montant total peut affecter le statut de la facture.
                    </div>
                </div>
                @endif
            </form>
        </div>
        
        <div class="form-footer">
            <div class="flex justify-between items-center">
                <a href="{{ route('invoices.show', $invoice) }}" class="btn-secondary-custom">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" form="editInvoiceForm" class="btn-primary-custom">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </div>
    
    <!-- Sidebar - Récapitulatif et paiements -->
    <div class="space-y-6">
        
        <!-- Récapitulatif -->
        <div class="form-card animate-fade-up" style="animation-delay: 0.15s">
            <div class="form-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                        <i class="fas fa-chart-pie text-success text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Récapitulatif</h3>
                        <p class="text-sm text-slate-500">Montants et statut</p>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500">N° Facture</span>
                        <span class="font-mono font-bold text-slate-700">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500">Date création</span>
                        <span class="font-medium text-slate-700">{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500">Montant total</span>
                        <span class="font-bold text-primary-blue" id="totalAmount">{{ number_format($invoice->amount, 2) }} DT</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500">Déjà payé</span>
                        <span class="font-bold text-success">{{ number_format($invoice->paid_amount, 2) }} DT</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Reste à payer</span>
                        <span class="font-bold text-danger" id="remainingAmount">{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Historique des paiements -->
        @if($invoice->payments && $invoice->payments->count() > 0)
        <div class="form-card animate-fade-up" style="animation-delay: 0.2s">
            <div class="form-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center">
                        <i class="fas fa-history text-warning text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Historique des paiements</h3>
                        <p class="text-sm text-slate-500">{{ $invoice->payments->count() }} paiement(s)</p>
                    </div>
                </div>
            </div>
            <div class="form-body p-0">
                <div class="overflow-x-auto">
                    <table class="payment-history-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                                <td class="text-success font-semibold">{{ number_format($payment->amount, 2) }} DT</td>
                                <td>
                                    @if($payment->payment_method == 'cash') 💰 Espèces
                                    @elseif($payment->payment_method == 'card') 💳 Carte
                                    @elseif($payment->payment_method == 'check') 📝 Chèque
                                    @elseif($payment->payment_method == 'transfer') 🏦 Virement
                                    @endif
                                </td>
                                <td class="text-mono text-xs">{{ $payment->transaction_id ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total payé</th>
                                <th colspan="3" class="text-success">{{ number_format($invoice->payments->sum('amount'), 2) }} DT</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Aide -->
        <div class="form-card animate-fade-up" style="animation-delay: 0.25s">
            <div class="form-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                        <i class="fas fa-question-circle text-info text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Besoin d'aide ?</h3>
                        <p class="text-sm text-slate-500">Informations utiles</p>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="space-y-3 text-sm text-slate-500">
                    <p><i class="fas fa-check-circle text-success mr-2"></i> Le montant total peut être modifié</p>
                    <p><i class="fas fa-check-circle text-success mr-2"></i> Les paiements existants ne sont pas affectés</p>
                    <p><i class="fas fa-check-circle text-success mr-2"></i> Le statut se mettra à jour automatiquement</p>
                    <p><i class="fas fa-info-circle text-primary-light mr-2"></i> Les modifications seront enregistrées après validation</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mise à jour dynamique des montants dans le récapitulatif
    const amountInput = document.getElementById('amountInput');
    const totalAmountSpan = document.getElementById('totalAmount');
    const remainingAmountSpan = document.getElementById('remainingAmount');
    const paidAmount = {{ $invoice->paid_amount }};
    
    if (amountInput) {
        amountInput.addEventListener('change', function() {
            const newAmount = parseFloat(this.value) || 0;
            const remaining = newAmount - paidAmount;
            
            totalAmountSpan.innerText = newAmount.toFixed(2) + ' DT';
            remainingAmountSpan.innerText = remaining.toFixed(2) + ' DT';
            
            // Changer la couleur du reste à payer
            if (remaining < 0) {
                remainingAmountSpan.classList.add('text-danger');
                remainingAmountSpan.classList.remove('text-success');
            } else if (remaining === 0) {
                remainingAmountSpan.classList.remove('text-danger');
                remainingAmountSpan.classList.add('text-success');
            } else {
                remainingAmountSpan.classList.add('text-danger');
                remainingAmountSpan.classList.remove('text-success');
            }
        });
    }
    
    // Confirmation avant soumission
    document.getElementById('editInvoiceForm')?.addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput?.value || 0);
        if (amount <= 0) {
            e.preventDefault();
            alert('Le montant doit être supérieur à 0');
            return false;
        }
        
        if (confirm('Êtes-vous sûr de vouloir modifier cette facture ?')) {
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>

@endsection