@extends('layouts.app')

@section('page_title', 'Paiement de la facture')
@section('page_subtitle', 'Facture N° ' . $invoice->invoice_number)

@section('content')

<style>
    .payment-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    
    .payment-header {
        background: linear-gradient(135deg, #023E8A 0%, #0077B6 100%);
        padding: 24px;
        color: white;
    }
    
    .amount-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    
    .payment-method {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        background: white;
        user-select: none;
    }
    
    .payment-method:hover {
        border-color: #00B4D8;
        transform: translateY(-2px);
    }
    
    .payment-method.active {
        border-color: #10B981;
        background: #ecfdf5;
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #10B981, #059669);
        border: none;
        padding: 16px;
        font-size: 16px;
        font-weight: 700;
        border-radius: 16px;
        transition: all 0.3s;
        color: white;
        width: 100%;
        cursor: pointer;
        display: block;
        text-align: center;
    }
    
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
    }
    
    .btn-pay:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .type-option {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        background: white;
    }
    
    .type-option:hover {
        border-color: #00B4D8;
        background: #f8fafc;
    }
    
    .type-option.active {
        border-color: #10B981;
        background: #ecfdf5;
    }
    
    .payment-method-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- En-tête -->
        <div class="mb-6">
            @php
                $showRoute = auth()->user()->role === 'patient' ? route('patient.invoices.show', $invoice) : route('invoices.show', $invoice);
            @endphp
            <a href="{{ $showRoute }}" class="text-blue-600 hover:underline inline-flex items-center gap-2 mb-4">
                <i class="fas fa-arrow-left"></i> Retour à la facture
            </a>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            
            <!-- Informations facture -->
            <div>
                <div class="payment-card">
                    <div class="payment-header">
                        <div class="flex justify-between items-center">
                            <div>
                                <i class="fas fa-file-invoice-dollar text-3xl opacity-80"></i>
                                <h2 class="text-xl font-bold mt-2">Paiement sécurisé</h2>
                                <p class="text-white/70 text-sm">Facture #{{ $invoice->invoice_number }}</p>
                            </div>
                            <div class="bg-white/20 rounded-xl px-4 py-2 text-center">
                                <div class="text-xs opacity-80">Montant total</div>
                                <div class="text-xl font-bold">{{ number_format($invoice->amount, 2) }} DT</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Récapitulatif -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500">Montant total</span>
                                <span class="font-semibold">{{ number_format($invoice->amount, 2) }} DT</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500">Déjà payé</span>
                                <span class="font-semibold text-green-600">{{ number_format($invoice->paid_amount, 2) }} DT</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg">
                                <span class="font-bold text-slate-700">Reste à payer</span>
                                <span class="font-bold text-red-600">{{ number_format($totalRemaining, 2) }} DT</span>
                            </div>
                        </div>
                        
                        <!-- Détail des prises en charge -->
                        <div class="bg-slate-50 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-slate-700 mb-3 text-sm">Détail des prises en charge</h4>
                            <div class="space-y-2 text-sm">
                                @if($invoice->cnam_amount > 0)
                                <div class="flex justify-between">
                                    <span><i class="fas fa-building text-blue-600 mr-2"></i>CNAM</span>
                                    <span class="font-medium">
                                        {{ number_format($invoice->cnam_amount, 2) }} DT
                                        @if($invoice->cnam_paid) <span class="text-green-600 text-xs">(Payé)</span> @endif
                                    </span>
                                </div>
                                @endif
                                @if($invoice->mutuelle_amount > 0)
                                <div class="flex justify-between">
                                    <span><i class="fas fa-handshake text-green-600 mr-2"></i>Mutuelle</span>
                                    <span class="font-medium">
                                        {{ number_format($invoice->mutuelle_amount, 2) }} DT
                                        @if($invoice->mutuelle_paid) <span class="text-green-600 text-xs">(Payé)</span> @endif
                                    </span>
                                </div>
                                @endif
                                @if($invoice->patient_amount > 0)
                                <div class="flex justify-between">
                                    <span><i class="fas fa-user text-orange-600 mr-2"></i>Patient</span>
                                    <span class="font-medium">
                                        {{ number_format($invoice->patient_amount, 2) }} DT
                                        @if($invoice->patient_paid) <span class="text-green-600 text-xs">(Payé)</span> @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulaire de paiement -->
            <div>
                <div class="payment-card">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Effectuer le paiement</h3>
                        
                        @php
                            $processRoute = auth()->user()->role === 'patient' ? route('patient.invoices.processPayment', $invoice) : route('invoices.processPayment', $invoice);
                        @endphp
                        <form action="{{ $processRoute }}" method="POST" id="paymentForm">
                            @csrf
                            
                            <!-- Type de paiement -->
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Type de paiement</label>
                                <div class="space-y-3">
                                    @if($invoice->cnam_amount > 0 && $remainingCnam > 0)
                                    <div class="type-option {{ $type == 'cnam' ? 'active' : '' }}" data-type="cnam" data-max="{{ $remainingCnam }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-building text-blue-600 text-xl"></i>
                                                <div>
                                                    <div class="font-bold">CNAM</div>
                                                    <div class="text-xs text-slate-500">Paiement par la CNAM</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-blue-600">{{ number_format($remainingCnam, 2) }} DT</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->mutuelle_amount > 0 && $remainingMutuelle > 0)
                                    <div class="type-option {{ $type == 'mutuelle' ? 'active' : '' }}" data-type="mutuelle" data-max="{{ $remainingMutuelle }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-handshake text-green-600 text-xl"></i>
                                                <div>
                                                    <div class="font-bold">Mutuelle</div>
                                                    <div class="text-xs text-slate-500">Paiement par la mutuelle</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-green-600">{{ number_format($remainingMutuelle, 2) }} DT</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->patient_amount > 0 && $remainingPatient > 0)
                                    <div class="type-option {{ $type == 'patient' ? 'active' : '' }}" data-type="patient" data-max="{{ $remainingPatient }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-user text-orange-600 text-xl"></i>
                                                <div>
                                                    <div class="font-bold">Patient</div>
                                                    <div class="text-xs text-slate-500">Paiement par le patient</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-orange-600">{{ number_format($remainingPatient, 2) }} DT</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($remainingPatient > 0 && $invoice->patient_amount <= 0 && $invoice->cnam_amount <= 0 && $invoice->mutuelle_amount <= 0)
                                    <div class="type-option active" data-type="patient" data-max="{{ $remainingPatient }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-user text-orange-600 text-xl"></i>
                                                <div>
                                                    <div class="font-bold">Patient</div>
                                                    <div class="text-xs text-slate-500">Reste à payer</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-orange-600">{{ number_format($remainingPatient, 2) }} DT</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="hidden" name="payment_type" id="payment_type" value="{{ $type }}">
                            </div>
                            
                            <!-- Montant -->
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Montant à payer</label>
                                <div class="amount-box">
                                    <div class="flex items-center justify-center gap-3">
                                        <span class="text-2xl font-bold text-blue-600">DT</span>
                                        <input type="number" 
                                               name="amount" 
                                               id="amount" 
                                               step="0.01" 
                                               value="{{ $maxAmount }}"
                                               class="text-3xl font-bold text-center border-none bg-transparent focus:outline-none w-40"
                                               required>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-2" id="maxAmountHint">Maximum: {{ number_format($maxAmount, 2) }} DT</div>
                                </div>
                            </div>
                            
                            <!-- Mode de paiement -->
                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Mode de paiement</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="payment-method {{ old('payment_method', 'transfer') == 'cash' ? 'active' : '' }}" data-method="cash">
                                        <div class="payment-method-icon">💰</div>
                                        <div class="text-sm font-medium">Espèces</div>
                                    </div>
                                    <div class="payment-method {{ old('payment_method', 'transfer') == 'card' ? 'active' : '' }}" data-method="card">
                                        <div class="payment-method-icon">💳</div>
                                        <div class="text-sm font-medium">Carte bancaire</div>
                                    </div>
                                    <div class="payment-method {{ old('payment_method', 'transfer') == 'check' ? 'active' : '' }}" data-method="check">
                                        <div class="payment-method-icon">📝</div>
                                        <div class="text-sm font-medium">Chèque</div>
                                    </div>
                                    <div class="payment-method {{ old('payment_method', 'transfer') == 'transfer' ? 'active' : '' }}" data-method="transfer">
                                        <div class="payment-method-icon">🏦</div>
                                        <div class="text-sm font-medium">Virement</div>
                                    </div>
                                </div>
                                <input type="hidden" name="payment_method" id="payment_method" value="{{ old('payment_method', 'transfer') }}">
                            </div>
                            
                            <!-- Référence (pour assurance) -->
                            <div class="mb-5" id="referenceField" style="{{ $type == 'cnam' || $type == 'mutuelle' ? 'display: block;' : 'display: none;' }}">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro de référence</label>
                                <input type="text" name="reference_number" id="reference_number" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400" placeholder="Numéro de bon / attestation">
                                <p class="text-xs text-slate-400 mt-1">Requis pour les paiements CNAM et Mutuelle</p>
                            </div>
                            
                            <!-- Notes -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Notes (optionnel)</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400" placeholder="Informations complémentaires..."></textarea>
                            </div>
                            
                            <!-- Bouton de paiement -->
                            <button type="submit" class="btn-pay" id="submitBtn">
                                <i class="fas fa-lock mr-2"></i>
                                Confirmer le paiement
                            </button>
                            
                            <p class="text-center text-xs text-slate-400 mt-4">
                                <i class="fas fa-shield-alt mr-1"></i> Paiement sécurisé
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Éléments
        const typeOptions = document.querySelectorAll('.type-option');
        const paymentMethods = document.querySelectorAll('.payment-method');
        const amountInput = document.getElementById('amount');
        const paymentTypeInput = document.getElementById('payment_type');
        const paymentMethodInput = document.getElementById('payment_method');
        const referenceField = document.getElementById('referenceField');
        const referenceNumberInput = document.getElementById('reference_number');
        const maxAmountHint = document.getElementById('maxAmountHint');
        const submitBtn = document.getElementById('submitBtn');
        const paymentForm = document.getElementById('paymentForm');
        
        let currentMaxAmount = {{ $maxAmount }};
        
        // Sélection du type de paiement
        typeOptions.forEach(option => {
            option.addEventListener('click', function() {
                typeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const type = this.dataset.type;
                const maxAmount = parseFloat(this.dataset.max);
                
                paymentTypeInput.value = type;
                currentMaxAmount = maxAmount;
                
                // Mettre à jour le champ montant
                amountInput.value = maxAmount.toFixed(2);
                
                // Afficher le hint
                if (maxAmountHint) {
                    maxAmountHint.textContent = `Maximum: ${maxAmount.toFixed(2)} DT`;
                }
                
                // Afficher/cacher le champ référence
                if (referenceField) {
                    if (type === 'cnam' || type === 'mutuelle') {
                        referenceField.style.display = 'block';
                    } else {
                        referenceField.style.display = 'none';
                        if (referenceNumberInput) referenceNumberInput.value = '';
                    }
                }
            });
        });
        
        // Sélection du mode de paiement
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                paymentMethods.forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                if (paymentMethodInput) {
                    paymentMethodInput.value = this.dataset.method;
                }
            });
        });
        
        // Validation du montant
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                let value = parseFloat(this.value);
                if (isNaN(value)) return;
                
                if (value > currentMaxAmount) {
                    this.value = currentMaxAmount.toFixed(2);
                }
            });
        }
        
        // Validation du formulaire
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                const amount = parseFloat(amountInput.value);
                
                if (isNaN(amount) || amount <= 0) {
                    e.preventDefault();
                    alert('Veuillez saisir un montant valide');
                    return false;
                }
                
                if (amount > (currentMaxAmount + 0.01)) { // Marge d'erreur de calcul
                    e.preventDefault();
                    alert('Le montant ne peut pas dépasser ' + currentMaxAmount.toFixed(2) + ' DT');
                    return false;
                }
                
                const paymentType = paymentTypeInput.value;
                if ((paymentType === 'cnam' || paymentType === 'mutuelle')) {
                    const reference = referenceNumberInput ? referenceNumberInput.value.trim() : '';
                    if (!reference) {
                        e.preventDefault();
                        alert('Veuillez saisir le numéro de référence pour le paiement ' + (paymentType === 'cnam' ? 'CNAM' : 'Mutuelle'));
                        if (referenceNumberInput) referenceNumberInput.focus();
                        return false;
                    }
                }
                
                // Effet visuel
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
                    submitBtn.style.opacity = '0.7';
                }
                
                return true;
            });
        }
    });
</script>

@endsection