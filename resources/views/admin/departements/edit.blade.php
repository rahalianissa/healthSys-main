@extends('layouts.app')

@section('page_title', 'Modifier un département')
@section('page_subtitle', 'Mettre à jour les informations du département')

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-soft: #48CAE4;
        --primary-bg: #CAF0F8;
        --warning: #F59E0B;
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
    }
    
    .form-section {
        padding: 28px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
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
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-label .required {
        color: #ef4444;
        margin-left: 3px;
    }
    
    .info-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
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
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .info-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        margin-top: 20px;
    }
    
    .info-card-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }
    
    .info-card-item:not(:last-child) {
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-label {
        width: 100px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }
    
    .info-value {
        font-size: 13px;
        font-weight: 500;
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
</style>

<!-- Form Header -->
<div class="form-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-edit text-amber-200 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">MODIFICATION</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier le département</h1>
        <p class="text-white/60 text-sm">Mettez à jour les informations du département</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('admin.departements.update', $departement) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informations principales -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-building"></i>
                <span>Informations du département</span>
            </div>
            
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="form-label">Nom du département <span class="required">*</span></label>
                    <input type="text" name="nom" class="form-input @error('nom') is-invalid @enderror" 
                           value="{{ old('nom', $departement->nom) }}" required 
                           placeholder="Ex: Cardiologie, Urgences, Pédiatrie...">
                    @error('nom') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                    <div class="info-text">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nom unique du département
                    </div>
                </div>
                
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input @error('description') is-invalid @enderror" 
                              rows="4" placeholder="Description du département...">{{ old('description', $departement->description) }}</textarea>
                    @error('description') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                    <div class="info-text">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informations supplémentaires sur le département
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-chart-simple"></i>
                <span>Statistiques du département</span>
            </div>
            
            <div class="info-card">
                <div class="info-card-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt text-primary-blue mr-1"></i>
                        Date de création
                    </div>
                    <div class="info-value">
                        @if($departement->created_at)
                            {{ \Carbon\Carbon::parse($departement->created_at)->format('d/m/Y à H:i') }}
                        @else
                            <span class="text-slate-400">Non disponible</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-card-item">
                    <div class="info-label">
                        <i class="fas fa-sync-alt text-primary-blue mr-1"></i>
                        Dernière modification
                    </div>
                    <div class="info-value">
                        @if($departement->updated_at)
                            {{ \Carbon\Carbon::parse($departement->updated_at)->format('d/m/Y à H:i') }}
                        @else
                            <span class="text-slate-400">Jamais modifié</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-card-item">
                    <div class="info-label">
                        <i class="fas fa-users text-primary-blue mr-1"></i>
                        Secrétaires
                    </div>
                    <div class="info-value">
                        {{ $departement->secretaries->count() }} secrétaire(s) affecté(s)
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.departements.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>

@endsection