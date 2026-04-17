{{-- resources/views/secretaire/patients/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Ajouter un patient')
@section('page-title', 'Nouveau patient')

@section('content')
<div class="container">
    <form action="{{ url('/secretaire/patients') }}" method="POST">
        @csrf

        {{-- Informations personnelles --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="birth_date" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Groupe sanguin</label>
                        <select name="blood_type" class="form-control">
                            <option value="">Sélectionner</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informations médicales --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Informations médicales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Poids (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Taille (cm)</label>
                        <input type="number" step="0.01" name="height" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2" placeholder="Liste des allergies..."></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Antécédents médicaux</label>
                        <textarea name="medical_history" class="form-control" rows="2" placeholder="Antécédents chirurgicaux, maladies chroniques..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Assurance (IMPORTANTE POUR PFE) --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Couverture d'assurance</h5>
            </div>
            <div class="card-body">
                
                {{-- CNAM Section --}}
                <div class="mb-4 p-3 border rounded">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="has_cnam" name="has_cnam" value="1">
                        <label class="form-check-label fw-bold" for="has_cnam">
                            <i class="fas fa-building me-1 text-primary"></i> Couverture CNAM (Caisse Nationale d'Assurance Maladie)
                        </label>
                    </div>
                    <div id="cnam_fields" style="display: none;">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-1"></i> Le CNAM prend en charge environ 70% des frais médicaux
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Numéro CNAM <span class="text-muted">(Immatriculation)</span></label>
                                <input type="text" name="cnam_number" class="form-control" placeholder="Ex: 123456789">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date d'expiration CNAM</label>
                                <input type="date" name="cnam_expiry_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Mutuelle Section --}}
                <div class="mb-4 p-3 border rounded">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="has_mutuelle" name="has_mutuelle" value="1">
                        <label class="form-check-label fw-bold" for="has_mutuelle">
                            <i class="fas fa-handshake me-1 text-success"></i> Mutuelle complémentaire
                        </label>
                    </div>
                    <div id="mutuelle_fields" style="display: none;">
                        <div class="alert alert-success small">
                            <i class="fas fa-info-circle me-1"></i> La mutuelle complète le remboursement après le CNAM
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Compagnie mutuelle</label>
                                <select name="mutuelle_company" class="form-control" id="mutuelle_company">
                                    <option value="">Sélectionner une mutuelle</option>
                                    <option value="CNSS">CNSS</option>
                                    <option value="CNOPS">CNOPS</option>
                                    <option value="La Preservatrice">La Preservatrice</option>
                                    <option value="Star Assurances">Star Assurances</option>
                                    <option value="GAT">GAT</option>
                                    <option value="Amana">Amana</option>
                                    <option value="Zitouna Takaful">Zitouna Takaful</option>
                                    <option value="BIAT Assurances">BIAT Assurances</option>
                                    <option value="Maghrebia">Maghrebia</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Numéro d'affiliation</label>
                                <input type="text" name="mutuelle_number" class="form-control" placeholder="Numéro de contrat">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Taux de couverture (%)</label>
                                <input type="number" step="0.01" name="mutuelle_rate" class="form-control" placeholder="Ex: 80">
                                <small class="text-muted">Pourcentage pris en charge par la mutuelle</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date d'expiration</label>
                                <input type="date" name="mutuelle_expiry_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Contact d'urgence --}}
                <div class="p-3 border rounded">
                    <h6 class="mb-3"><i class="fas fa-phone-alt me-2"></i>Contact d'urgence</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom du contact</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="Personne à contacter en cas d'urgence">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone urgence</label>
                            <input type="text" name="emergency_phone" class="form-control" placeholder="Numéro d'urgence">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-5">
            <a href="{{ url('/secretaire/patients') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary px-4">Enregistrer le patient</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle CNAM fields
    const cnamCheckbox = document.getElementById('has_cnam');
    const cnamFields = document.getElementById('cnam_fields');
    
    cnamCheckbox.addEventListener('change', function() {
        cnamFields.style.display = this.checked ? 'block' : 'none';
    });
    
    // Toggle Mutuelle fields
    const mutuelleCheckbox = document.getElementById('has_mutuelle');
    const mutuelleFields = document.getElementById('mutuelle_fields');
    
    mutuelleCheckbox.addEventListener('change', function() {
        mutuelleFields.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endsection