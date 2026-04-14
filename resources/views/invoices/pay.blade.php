@extends('layouts.app')

@section('title', 'Paiement sécurisé')
@section('page-title', 'Paiement de la facture')

@section('styles')
<style>
    .payment-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
        padding: 25px;
        color: white;
    }
    .card-number-input {
        font-size: 18px;
        letter-spacing: 2px;
        font-family: monospace;
    }
    .card-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 30px;
    }
    .payment-method-box {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
    }
    .payment-method-box:hover {
        border-color: #1a5f7a;
        transform: translateY(-3px);
    }
    .payment-method-box.active {
        border-color: #28a745;
        background: #e8f5e9;
    }
    .card-preview {
        background: linear-gradient(135deg, #2c3e50, #1a1a2e);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        position: relative;
        min-height: 200px;
    }
    .card-preview .card-number {
        font-size: 20px;
        letter-spacing: 3px;
        font-family: monospace;
        margin-top: 30px;
    }
    .card-preview .card-holder {
        margin-top: 20px;
        text-transform: uppercase;
    }
    .card-preview .card-expiry {
        margin-top: 20px;
    }
    .card-chip {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
    }
    .card-brand {
        position: absolute;
        bottom: 20px;
        right: 20px;
        font-size: 40px;
    }
    .btn-pay {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50px;
        transition: all 0.3s;
    }
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(40,167,69,0.4);
    }
    .secure-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="payment-card">
            <div class="card-header-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i> Paiement sécurisé</h4>
                        <small class="opacity-75">Facture #{{ $invoice->invoice_number }}</small>
                    </div>
                    <div class="secure-badge">
                        <i class="fas fa-lock text-success"></i>
                        <span>Paiement 100% sécurisé</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Récapitulatif facture -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted">Montant total</small>
                            <h5 class="mb-0">{{ number_format($invoice->amount, 2) }} DT</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Déjà payé</small>
                            <h5 class="mb-0 text-success">{{ number_format($invoice->paid_amount, 2) }} DT</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Reste à payer</small>
                            <h5 class="mb-0 text-danger">{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</h5>
                        </div>
                    </div>
                </div>

                <!-- Aperçu de la carte -->
                <div class="card-preview" id="cardPreview">
                    <img src="https://cdn-icons-png.flaticon.com/512/217/217980.png" class="card-chip" alt="chip">
                    <div class="card-number text-center" id="previewCardNumber">•••• •••• •••• ••••</div>
                    <div class="row mt-3">
                        <div class="col-8">
                            <small>TITULAIRE</small>
                            <div class="card-holder" id="previewCardHolder">NOM PRENOM</div>
                        </div>
                        <div class="col-4">
                            <small>EXPIRATION</small>
                            <div class="card-expiry" id="previewCardExpiry">MM/AA</div>
                        </div>
                    </div>
                    <div class="card-brand" id="cardBrandIcon">
                        <i class="fab fa-cc-visa"></i>
                    </div>
                </div>

                <form action="{{ route('invoices.processPayment', $invoice) }}" method="POST" id="paymentForm">
                    @csrf

                    <!-- Montant à payer -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Montant à payer</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">DT</span>
                            <input type="number" step="0.01" name="amount" id="amount" 
                                   class="form-control form-control-lg"
                                   value="{{ $invoice->amount - $invoice->paid_amount }}"
                                   min="0.01"
                                   max="{{ $invoice->amount - $invoice->paid_amount }}"
                                   required>
                        </div>
                    </div>

                    <!-- Numéro de carte -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Numéro de carte</label>
                        <div class="position-relative">
                            <input type="text" name="card_number" id="cardNumber" 
                                   class="form-control form-control-lg card-number-input"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19"
                                   required>
                            <div class="card-icon" id="cardIcon">
                                <i class="far fa-credit-card"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date d'expiration -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date d'expiration</label>
                            <div class="row">
                                <div class="col-6">
                                    <select name="exp_month" id="expMonth" class="form-select" required>
                                        <option value="">Mois</option>
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="exp_year" id="expYear" class="form-select" required>
                                        <option value="">Année</option>
                                        @for($i=date('Y'); $i<=date('Y')+10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- CVV -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CVV / CVC</label>
                            <input type="text" name="cvv" id="cvv" 
                                   class="form-control form-control-lg"
                                   placeholder="123"
                                   maxlength="4"
                                   required>
                            <small class="text-muted">Code à 3 ou 4 chiffres</small>
                        </div>
                    </div>

                    <!-- Nom du titulaire -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom du titulaire</label>
                        <input type="text" name="card_holder" id="cardHolder" 
                               class="form-control form-control-lg"
                               placeholder="Comme inscrit sur la carte"
                               required>
                    </div>

                    <!-- Mode de paiement -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mode de paiement</label>
                        <div class="row">
                            <div class="col-3">
                                <div class="payment-method-box" data-method="card">
                                    <i class="fas fa-credit-card fa-2x mb-2"></i>
                                    <div>Carte</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method-box" data-method="cash">
                                    <i class="fas fa-money-bill fa-2x mb-2"></i>
                                    <div>Espèces</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method-box" data-method="check">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <div>Chèque</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="payment-method-box" data-method="transfer">
                                    <i class="fas fa-university fa-2x mb-2"></i>
                                    <div>Virement</div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" id="paymentMethod" value="card">
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Informations complémentaires..."></textarea>
                    </div>

                    <!-- Bouton de paiement -->
                    <button type="submit" class="btn btn-pay w-100" id="payBtn">
                        <i class="fas fa-lock me-2"></i> Payer {{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT
                    </button>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i> Transactions sécurisées par cryptage SSL
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Formatage du numéro de carte
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    let formatted = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) formatted += ' ';
        formatted += value[i];
    }
    e.target.value = formatted.substring(0, 19);
    
    // Mettre à jour l'aperçu
    let displayValue = formatted;
    if (displayValue.length < 19) {
        let missing = 19 - displayValue.length;
        displayValue += '•'.repeat(missing);
    }
    document.getElementById('previewCardNumber').innerText = displayValue || '•••• •••• •••• ••••';
    
    // Détecter le type de carte
    let firstDigit = value.charAt(0);
    let iconDiv = document.getElementById('cardIcon');
    let brandIcon = document.getElementById('cardBrandIcon');
    
    if (firstDigit === '4') {
        iconDiv.innerHTML = '<i class="fab fa-cc-visa fa-2x text-primary"></i>';
        brandIcon.innerHTML = '<i class="fab fa-cc-visa fa-3x"></i>';
    } else if (firstDigit === '5') {
        iconDiv.innerHTML = '<i class="fab fa-cc-mastercard fa-2x text-danger"></i>';
        brandIcon.innerHTML = '<i class="fab fa-cc-mastercard fa-3x"></i>';
    } else if (firstDigit === '3') {
        iconDiv.innerHTML = '<i class="fab fa-cc-amex fa-2x text-primary"></i>';
        brandIcon.innerHTML = '<i class="fab fa-cc-amex fa-3x"></i>';
    } else {
        iconDiv.innerHTML = '<i class="far fa-credit-card fa-2x"></i>';
        brandIcon.innerHTML = '<i class="fas fa-credit-card fa-3x"></i>';
    }
});

// Mise à jour de la date d'expiration dans l'aperçu
document.getElementById('expMonth').addEventListener('change', updateExpiry);
document.getElementById('expYear').addEventListener('change', updateExpiry);

function updateExpiry() {
    let month = document.getElementById('expMonth').value;
    let year = document.getElementById('expYear').value;
    if (month && year) {
        document.getElementById('previewCardExpiry').innerText = month + '/' + year.toString().slice(-2);
    }
}

// Mise à jour du nom du titulaire
document.getElementById('cardHolder').addEventListener('input', function(e) {
    let value = e.target.value.toUpperCase();
    document.getElementById('previewCardHolder').innerText = value || 'NOM PRENOM';
});

// CVV focus
document.getElementById('cvv').addEventListener('focus', function() {
    document.getElementById('cardPreview').style.transform = 'rotateY(5deg)';
});
document.getElementById('cvv').addEventListener('blur', function() {
    document.getElementById('cardPreview').style.transform = 'rotateY(0)';
});

// Sélection du mode de paiement
document.querySelectorAll('.payment-method-box').forEach(box => {
    box.addEventListener('click', function() {
        document.querySelectorAll('.payment-method-box').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('paymentMethod').value = this.dataset.method;
        
        // Cacher/afficher les champs de carte selon le mode
        let cardFields = document.querySelectorAll('#cardNumber, #expMonth, #expYear, #cvv, #cardHolder');
        if (this.dataset.method === 'card') {
            cardFields.forEach(f => f.disabled = false);
            document.getElementById('cardPreview').style.display = 'block';
        } else {
            cardFields.forEach(f => f.disabled = true);
            document.getElementById('cardPreview').style.display = 'none';
        }
    });
});

// Validation avant soumission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    let method = document.getElementById('paymentMethod').value;
    
    if (method === 'card') {
        let cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
        let expMonth = document.getElementById('expMonth').value;
        let expYear = document.getElementById('expYear').value;
        let cvv = document.getElementById('cvv').value;
        let cardHolder = document.getElementById('cardHolder').value;
        
        if (cardNumber.length < 16) {
            alert('Veuillez entrer un numéro de carte valide (16 chiffres)');
            e.preventDefault();
            return false;
        }
        
        if (!expMonth || !expYear) {
            alert('Veuillez sélectionner la date d\'expiration');
            e.preventDefault();
            return false;
        }
        
        if (cvv.length < 3) {
            alert('Veuillez entrer un CVV valide');
            e.preventDefault();
            return false;
        }
        
        if (!cardHolder) {
            alert('Veuillez entrer le nom du titulaire');
            e.preventDefault();
            return false;
        }
    }
    
    let btn = document.getElementById('payBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement en cours...';
    btn.disabled = true;
});

// Activer la carte par défaut
document.querySelector('.payment-method-box[data-method="card"]').classList.add('active');
</script>
@endsection