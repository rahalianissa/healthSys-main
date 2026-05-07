@extends('layouts.app')

@section('page_title', 'Modifier le patient')
@section('page_subtitle', 'Mettre à jour les informations du patient')

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

    .form-header {
        background: linear-gradient(135deg, #B45309 0%, var(--warning) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
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
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .form-section {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        width: 32px;
        height: 32px;
        background: var(--primary-bg);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 14px;
    }
    
    .form-input {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        width: 100%;
        transition: all 0.2s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-label .required {
        color: var(--danger);
        margin-left: 3px;
    }
    
    .info-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        color: white;
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(2, 62, 138, 0.3);
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .patient-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--success), #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 28px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: var(--success);
    }
    
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    
    .insurance-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        transition: all 0.3s;
    }
    
    .insurance-card:hover {
        background: #f1f5f9;
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
    
    .animate-fade {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    hr {
        margin: 20px 0;
        border-color: #e2e8f0;
    }
</style>

<!-- Form Header -->
<div class="form-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-user-edit text-amber-200 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">MODIFICATION DOSSIER PATIENT</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier le patient</h1>
        <p class="text-white/60 text-sm">Mettez à jour les informations du patient</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('secretaire.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- En-tête avec avatar -->
        <div class="form-section bg-slate-50/50">
            <div class="flex items-center gap-5 flex-wrap">
                <div class="patient-avatar-large">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $patient->user->name }}</h2>
                    <p class="text-slate-500 text-sm">ID: #{{ $patient->id }} | Code: P{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <span class="inline-flex items-center gap-1 text-xs text-emerald-600 mt-1">
                        <i class="fas fa-calendar-alt"></i>
                        Patient depuis le {{ $patient->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user-circle"></i>
                <span>Informations personnelles</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nom complet <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $patient->user->name) }}" required>
                    @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $patient->user->email) }}" required>
                    @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Téléphone <span class="required">*</span></label>
                    <input type="text" name="phone" class="form-input @error('phone') is-invalid @enderror" value="{{ old('phone', $patient->user->phone) }}" required>
                    @error('phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Date de naissance <span class="required">*</span></label>
                    <input type="date" name="birth_date" class="form-input @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $patient->user->birth_date) }}" required>
                    @error('birth_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Groupe sanguin</label>
                    <select name="blood_type" class="form-input">
                        <option value="">Sélectionner</option>
                        <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ old('blood_type', $patient->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_type', $patient->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                    @error('blood_type') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Poids (kg)</label>
                    <input type="number" step="0.1" name="weight" class="form-input" value="{{ old('weight', $patient->weight) }}" placeholder="70.5">
                </div>
                
                <div>
                    <label class="form-label">Taille (cm)</label>
                    <input type="number" step="0.1" name="height" class="form-input" value="{{ old('height', $patient->height) }}" placeholder="175">
                </div>
                
                <div class="md:col-span-2">
                    <label class="form-label">Adresse</label>
                    <textarea name="address" class="form-input @error('address') is-invalid @enderror" rows="2">{{ old('address', $patient->user->address) }}</textarea>
                    @error('address') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Informations médicales -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-notes-medical"></i>
                <span>Informations médicales</span>
            </div>
            
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="form-label">Allergies</label>
                    <textarea name="allergies" class="form-input" rows="2" placeholder="Liste des allergies (médicaments, aliments, etc.)">{{ old('allergies', $patient->allergies) }}</textarea>
                    <div class="info-text">Ex: Pénicilline, Arachides, Pollen</div>
                </div>
                
                <div>
                    <label class="form-label">Antécédents médicaux</label>
                    <textarea name="medical_history" class="form-input" rows="3" placeholder="Antécédents chirurgicaux, maladies chroniques, traitements en cours...">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    <div class="info-text">Ex: Diabète, Hypertension, Appendicectomie (2019)</div>
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-phone-alt"></i>
                <span>Contact d'urgence</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nom du contact</label>
                    <input type="text" name="emergency_contact" class="form-input" value="{{ old('emergency_contact', $patient->emergency_contact) }}" placeholder="Personne à contacter en cas d'urgence">
                </div>
                
                <div>
                    <label class="form-label">Téléphone d'urgence</label>
                    <input type="text" name="emergency_phone" class="form-input" value="{{ old('emergency_phone', $patient->emergency_phone) }}" placeholder="Numéro d'urgence">
                </div>
            </div>
        </div>

        <!-- Section Assurance (CNAM + Mutuelle) -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-shield-alt"></i>
                <span>Couverture d'assurance</span>
            </div>
            
            <!-- CNAM -->
            <div class="insurance-card mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="form-label mb-0">
                            <i class="fas fa-building mr-1 text-primary-blue"></i> CNAM
                        </label>
                        <p class="text-xs text-slate-500">Couverture nationale d'assurance maladie</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="has_cnam" value="1" id="cnamSwitch" {{ old('has_cnam', $patient->has_cnam) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div id="cnamFields" style="{{ old('has_cnam', $patient->has_cnam) ? 'display: block;' : 'display: none;' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="form-label text-sm">Numéro CNAM</label>
                            <input type="text" name="cnam_number" class="form-input" value="{{ old('cnam_number', $patient->cnam_number) }}" placeholder="Numéro d'immatriculation">
                        </div>
                        <div>
                            <label class="form-label text-sm">Date d'expiration</label>
                            <input type="date" name="cnam_expiry_date" class="form-input" value="{{ old('cnam_expiry_date', $patient->cnam_expiry_date ? $patient->cnam_expiry_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mutuelle -->
            <div class="insurance-card">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="form-label mb-0">
                            <i class="fas fa-handshake mr-1 text-success"></i> Mutuelle complémentaire
                        </label>
                        <p class="text-xs text-slate-500">Assurance santé complémentaire</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="has_mutuelle" value="1" id="mutuelleSwitch" {{ old('has_mutuelle', $patient->has_mutuelle) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div id="mutuelleFields" style="{{ old('has_mutuelle', $patient->has_mutuelle) ? 'display: block;' : 'display: none;' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="form-label text-sm">Compagnie mutuelle</label>
                            <select name="mutuelle_company" class="form-input">
                                <option value="">Sélectionner une mutuelle</option>
                                <option value="CNSS" {{ old('mutuelle_company', $patient->mutuelle_company) == 'CNSS' ? 'selected' : '' }}>CNSS</option>
                                <option value="CNOPS" {{ old('mutuelle_company', $patient->mutuelle_company) == 'CNOPS' ? 'selected' : '' }}>CNOPS</option>
                                <option value="La Preservatrice" {{ old('mutuelle_company', $patient->mutuelle_company) == 'La Preservatrice' ? 'selected' : '' }}>La Preservatrice</option>
                                <option value="Star Assurances" {{ old('mutuelle_company', $patient->mutuelle_company) == 'Star Assurances' ? 'selected' : '' }}>Star Assurances</option>
                                <option value="GAT" {{ old('mutuelle_company', $patient->mutuelle_company) == 'GAT' ? 'selected' : '' }}>GAT</option>
                                <option value="Amana" {{ old('mutuelle_company', $patient->mutuelle_company) == 'Amana' ? 'selected' : '' }}>Amana</option>
                                <option value="Zitouna Takaful" {{ old('mutuelle_company', $patient->mutuelle_company) == 'Zitouna Takaful' ? 'selected' : '' }}>Zitouna Takaful</option>
                                <option value="BIAT Assurances" {{ old('mutuelle_company', $patient->mutuelle_company) == 'BIAT Assurances' ? 'selected' : '' }}>BIAT Assurances</option>
                                <option value="Maghrebia" {{ old('mutuelle_company', $patient->mutuelle_company) == 'Maghrebia' ? 'selected' : '' }}>Maghrebia</option>
                                <option value="Autre" {{ old('mutuelle_company', $patient->mutuelle_company) == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-sm">Numéro d'affiliation</label>
                            <input type="text" name="mutuelle_number" class="form-input" value="{{ old('mutuelle_number', $patient->mutuelle_number) }}" placeholder="Numéro de contrat">
                        </div>
                        <div>
                            <label class="form-label text-sm">Taux de couverture (%)</label>
                            <input type="number" step="0.01" name="mutuelle_rate" class="form-input" value="{{ old('mutuelle_rate', $patient->mutuelle_rate) }}" placeholder="Ex: 80">
                        </div>
                        <div>
                            <label class="form-label text-sm">Date d'expiration</label>
                            <input type="date" name="mutuelle_expiry_date" class="form-input" value="{{ old('mutuelle_expiry_date', $patient->mutuelle_expiry_date ? $patient->mutuelle_expiry_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-primary-bg/30 rounded-xl">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-primary-light mt-0.5 text-sm"></i>
                    <div class="text-xs text-slate-600">
                        <span class="font-semibold">Note importante:</span> Les informations d'assurance sont utilisées pour le calcul automatique des factures (CNAM 70% + Mutuelle)
                    </div>
                </div>
            </div>
        </div>

        <!-- Changer mot de passe -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-key"></i>
                <span>Changer le mot de passe</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-input" placeholder="Laisser vide pour ne pas changer">
                </div>
                
                <div>
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Laisser vide pour ne pas changer">
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-amber-50 rounded-xl">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-amber-600 mt-0.5 text-sm"></i>
                    <div class="text-xs text-amber-700">
                        <span class="font-semibold">Note:</span> Laissez les champs vides pour conserver le mot de passe actuel.
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="flex justify-end gap-3 flex-wrap">
                <a href="{{ route('secretaire.patients.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <a href="{{ route('secretaire.patients.show', $patient) }}" class="btn-cancel" style="background: var(--primary-bg); color: var(--primary-blue);">
                    <i class="fas fa-eye mr-2"></i> Voir le dossier
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Toggle CNAM fields
    const cnamSwitch = document.getElementById('cnamSwitch');
    const cnamFields = document.getElementById('cnamFields');
    
    if (cnamSwitch) {
        cnamSwitch.addEventListener('change', function() {
            cnamFields.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Toggle Mutuelle fields
    const mutuelleSwitch = document.getElementById('mutuelleSwitch');
    const mutuelleFields = document.getElementById('mutuelleFields');
    
    if (mutuelleSwitch) {
        mutuelleSwitch.addEventListener('change', function() {
            mutuelleFields.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Calcul automatique de l'âge
    const birthDateInput = document.querySelector('input[name="birth_date"]');
    if (birthDateInput) {
        birthDateInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age > 0 && age < 120) {
                // Optionnel: afficher l'âge calculé
                console.log('Âge calculé:', age);
            }
        });
    }
</script>

@endsection