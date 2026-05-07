@extends('layouts.app')

@section('page_title', 'Créer une facture')
@section('page_subtitle', 'Nouvelle facture pour patient')

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
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
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
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-label i {
        color: var(--primary-lighter);
        width: 20px;
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        width: 100%;
        transition: all 0.2s;
        font-size: 14px;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .input-group-text {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        color: var(--primary-blue);
        font-weight: 600;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-blue));
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(2, 62, 138, 0.3);
    }
    
    .btn-secondary {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    
    .info-box {
        background: var(--primary-bg);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border-left: 4px solid var(--primary-light);
    }
    
    .info-box i {
        color: var(--primary-light);
        font-size: 18px;
    }
    
    .info-box p {
        margin: 0;
        font-size: 13px;
        color: var(--primary-dark);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .required-star {
        color: var(--danger);
        margin-left: 2px;
    }
    
    .preview-box {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 16px;
        padding: 20px;
        border: 1px dashed var(--primary-lighter);
        text-align: center;
        margin-top: 20px;
    }
    
    .preview-box h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 12px;
    }
    
    .preview-amount {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-blue);
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-file-invoice-dollar text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">FACTURATION</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Créer une facture</h1>
        <p class="text-white/60 text-sm">Générer une nouvelle facture pour un patient</p>
    </div>
</div>

<div class="max-w-4xl mx-auto animate-fade-up" style="animation-delay: 0.1s">
    
    <!-- Info Box -->
    <div class="info-box">
        <div class="flex items-center gap-3">
            <i class="fas fa-info-circle"></i>
            <p>Les informations d'assurance (CNAM / Mutuelle) seront automatiquement appliquées selon le profil du patient.</p>
        </div>
    </div>
    
    <!-- Formulaire -->
    <div class="form-card">
        <div class="form-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-bg flex items-center justify-center">
                    <i class="fas fa-file-invoice text-primary-blue text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Informations de la facture</h2>
                    <p class="text-xs text-slate-500">Remplissez les détails de la facture</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf
            
            @if(isset($prefilled['consultation_id']))
                <input type="hidden" name="consultation_id" value="{{ $prefilled['consultation_id'] }}">
            @endif

            <div class="form-body">
                <div class="space-y-5">
                    
                    <!-- Sélection Patient -->
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user"></i> Patient <span class="required-star">*</span>
                        </label>
                        <select name="patient_id" id="patient_id" class="form-select" required>
                            <option value="">-- Sélectionner un patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" 
                                        {{ (isset($prefilled['patient_id']) && $prefilled['patient_id'] == $patient->id) ? 'selected' : '' }}
                                        data-name="{{ $patient->user->name }}"
                                        data-phone="{{ $patient->user->phone }}"
                                        data-email="{{ $patient->user->email }}"
                                        data-has-cnam="{{ $patient->has_cnam ? 'true' : 'false' }}"
                                        data-has-mutuelle="{{ $patient->has_mutuelle ? 'true' : 'false' }}"
                                        data-mutuelle-rate="{{ $patient->mutuelle_rate ?? 0 }}">
                                    {{ $patient->user->name }} - {{ $patient->user->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Patient Info Preview -->
                    <div id="patientPreview" style="display: none;" class="bg-slate-50 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-bg flex items-center justify-center">
                                <i class="fas fa-user-check text-primary-blue"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-700" id="previewPatientName"></div>
                                <div class="text-xs text-slate-500" id="previewPatientContact"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Montant -->
                    <div>
                        <label class="form-label">
                            <i class="fas fa-tag"></i> Montant total (DT) <span class="required-star">*</span>
                        </label>
                        <div class="input-group flex">
                            <span class="input-group-text rounded-r-none">DT</span>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control rounded-l-none" 
                                   placeholder="0.00" value="{{ $prefilled['amount'] ?? '' }}" required>
                        </div>
                    </div>
                    
                    <!-- Aperçu breakdown assurance -->
                    <div id="insurancePreview" style="display: none;" class="preview-box">
                        <h4><i class="fas fa-shield-alt mr-2"></i> Récapitulatif des prises en charge</h4>
                        <div class="grid grid-cols-3 gap-4 mt-3 text-center">
                            <div>
                                <div class="text-xs text-slate-500">CNAM</div>
                                <div class="text-lg font-bold text-primary-blue" id="previewCnam">0.00 DT</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Mutuelle</div>
                                <div class="text-lg font-bold text-emerald-600" id="previewMutuelle">0.00 DT</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Patient</div>
                                <div class="text-lg font-bold text-amber-600" id="previewPatient">0.00 DT</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400 mt-3">* Calcul basé sur les informations d'assurance du patient</div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i> Date d'émission <span class="required-star">*</span>
                            </label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div>
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Date d'échéance <span class="required-star">*</span>
                            </label>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="form-label">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Description de la prestation... (ex: Consultation cardiologique, Examens médicaux, etc.)"></textarea>
                    </div>
                    
                </div>
            </div>
            
            <div class="form-footer">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('invoices.index') }}" class="btn-secondary inline-flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="submit" class="btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Créer la facture</span>
                    </button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
    const patientSelect = document.getElementById('patient_id');
    const amountInput = document.getElementById('amount');
    const patientPreview = document.getElementById('patientPreview');
    const insurancePreview = document.getElementById('insurancePreview');
    const previewPatientName = document.getElementById('previewPatientName');
    const previewPatientContact = document.getElementById('previewPatientContact');
    const previewCnam = document.getElementById('previewCnam');
    const previewMutuelle = document.getElementById('previewMutuelle');
    const previewPatient = document.getElementById('previewPatient');
    
    // Taux CNAM par défaut (Tunisie)
    const CNAM_RATE = 0.70;
    
    function updatePreview() {
        const selectedOption = patientSelect.options[patientSelect.selectedIndex];
        const amount = parseFloat(amountInput.value) || 0;
        
        if (patientSelect.value && amount > 0) {
            // Afficher la preview patient
            const patientName = selectedOption.dataset.name || '';
            const patientPhone = selectedOption.dataset.phone || '';
            const patientEmail = selectedOption.dataset.email || '';
            
            previewPatientName.textContent = patientName;
            previewPatientContact.textContent = `${patientPhone} • ${patientEmail}`;
            patientPreview.style.display = 'block';
            
            // Calculer les montants d'assurance
            const hasCnam = selectedOption.dataset.hasCnam === 'true';
            const hasMutuelle = selectedOption.dataset.hasMutuelle === 'true';
            
            let cnamAmount = 0;
            let mutuelleAmount = 0;
            let patientAmount = amount;
            
            if (hasCnam) {
                cnamAmount = amount * CNAM_RATE;
                patientAmount -= cnamAmount;
            }
            
            if (hasMutuelle && patientAmount > 0) {
                // Utiliser le taux mutuelle réel du patient
                const mutuelleRate = parseFloat(selectedOption.dataset.mutuelleRate) / 100 || 0;
                mutuelleAmount = patientAmount * mutuelleRate;
                patientAmount -= mutuelleAmount;
            }
            
            // Arrondir à 2 décimales
            cnamAmount = Math.round(cnamAmount * 100) / 100;
            mutuelleAmount = Math.round(mutuelleAmount * 100) / 100;
            patientAmount = Math.round(patientAmount * 100) / 100;
            
            previewCnam.textContent = cnamAmount.toFixed(2) + ' DT';
            previewMutuelle.textContent = mutuelleAmount.toFixed(2) + ' DT';
            previewPatient.textContent = patientAmount.toFixed(2) + ' DT';
            insurancePreview.style.display = 'block';
        } else {
            patientPreview.style.display = 'none';
            insurancePreview.style.display = 'none';
        }
    }
    
    patientSelect.addEventListener('change', updatePreview);
    amountInput.addEventListener('input', updatePreview);
    
    // Initialiser la preview si des données sont déjà présentes (pré-remplissage)
    document.addEventListener('DOMContentLoaded', updatePreview);
    
    // Validation avant soumission
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value);
        if (isNaN(amount) || amount <= 0) {
            e.preventDefault();
            alert('Veuillez entrer un montant valide');
        }
    });
</script>

@endsection