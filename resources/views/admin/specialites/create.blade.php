@extends('layouts.app')

@section('page_title', 'Ajouter une spécialité')
@section('page_subtitle', 'Créer une nouvelle spécialité médicale')

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-bg: #CAF0F8;
        --success: #10B981;
        --danger: #EF4444;
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
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
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .form-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .form-section {
        padding: 28px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        width: 36px;
        height: 36px;
        background: var(--primary-bg);
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 16px;
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
    
    textarea.form-input {
        resize: vertical;
        min-height: 100px;
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
    
    .icon-preview {
        width: 50px;
        height: 50px;
        background: var(--primary-bg);
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--primary-blue);
    }
    
    .suggestions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    
    .suggestion-badge {
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .suggestion-badge:hover {
        background: var(--primary-bg);
        color: var(--primary-blue);
    }
</style>

<!-- Form Header -->
<div class="form-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-plus-circle text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">NOUVELLE SPÉCIALITÉ</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Ajouter une spécialité</h1>
        <p class="text-white/60 text-sm">Créez une nouvelle spécialité médicale pour le cabinet</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('admin.specialites.store') }}" method="POST">
        @csrf

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                <span>Informations de la spécialité</span>
            </div>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="form-label">Nom de la spécialité <span class="required">*</span></label>
                    <input type="text" name="nom" class="form-input @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required placeholder="Ex: Cardiologie, Dermatologie...">
                    @error('nom') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    
                    <!-- Suggestions -->
                    <div class="suggestions-list">
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Cardiologie</span>
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Dermatologie</span>
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Pédiatrie</span>
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Gynécologie</span>
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Ophtalmologie</span>
                        <span class="suggestion-badge" onclick="document.querySelector('input[name=nom]').value = this.innerText;">Neurologie</span>
                    </div>
                </div>
                
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input @error('description') is-invalid @enderror" placeholder="Description de la spécialité...">{{ old('description') }}</textarea>
                    @error('description') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    <div class="text-xs text-slate-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i> Une description détaillée aide à mieux identifier la spécialité
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="form-section bg-slate-50/50">
            <div class="section-title">
                <i class="fas fa-eye"></i>
                <span>Aperçu</span>
            </div>
            
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200">
                <div class="icon-preview">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-800 text-lg" id="previewName">Nom de la spécialité</div>
                    <div class="text-slate-500 text-sm" id="previewDesc">Description de la spécialité</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section border-t border-slate-100">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.specialites.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Créer la spécialité
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Live preview
    const nameInput = document.querySelector('input[name="nom"]');
    const descInput = document.querySelector('textarea[name="description"]');
    const previewName = document.getElementById('previewName');
    const previewDesc = document.getElementById('previewDesc');
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Nom de la spécialité';
        });
    }
    
    if (descInput) {
        descInput.addEventListener('input', function() {
            previewDesc.textContent = this.value || 'Description de la spécialité';
        });
    }
</script>

@endsection