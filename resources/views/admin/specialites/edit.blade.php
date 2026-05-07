@extends('layouts.app')

@section('page_title', 'Modifier une spécialité')
@section('page_subtitle', 'Mettre à jour les informations de la spécialité')

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
        --danger: #EF4444;
        --success: #10B981;
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
        padding-bottom: 12px;
        border-bottom: 2px solid var(--primary-bg);
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
        font-size: 14px;
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
        margin-bottom: 8px;
        display: block;
    }
    
    .form-label .required {
        color: var(--danger);
        margin-left: 3px;
    }
    
    .form-hint {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .info-card {
        background: linear-gradient(135deg, var(--primary-bg) 0%, #e0f2fe 100%);
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
    }
    
    .info-item:not(:last-child) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .info-icon {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
    }
    
    .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-dark);
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
    
    .specialty-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-bg);
        color: var(--primary-blue);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<!-- Form Header -->
<div class="form-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-tag text-amber-200 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">MODIFICATION</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier la spécialité</h1>
        <p class="text-white/60 text-sm">Mettez à jour les informations de la spécialité médicale</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Formulaire principal -->
    <div class="lg:col-span-2">
        <div class="form-card animate-fade" style="animation-delay: 0.1s">
            <form action="{{ route('admin.specialites.update', $specialite) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-edit"></i>
                        <span>Informations de la spécialité</span>
                    </div>
                    
                    <div class="space-y-5">
                        <!-- Nom -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-tag text-primary-light mr-1"></i>
                                Nom de la spécialité <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="nom" 
                                   class="form-input @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom', $specialite->nom) }}" 
                                   required
                                   placeholder="Ex: Cardiologie, Pédiatrie...">
                            @error('nom') 
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">
                                <i class="fas fa-info-circle mr-1"></i>
                                Nom unique de la spécialité médicale
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-align-left text-primary-light mr-1"></i>
                                Description
                            </label>
                            <textarea name="description" 
                                      class="form-input @error('description') is-invalid @enderror" 
                                      rows="4"
                                      placeholder="Description détaillée de la spécialité...">{{ old('description', $specialite->description) }}</textarea>
                            @error('description') 
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">
                                <i class="fas fa-info-circle mr-1"></i>
                                Description de la spécialité (optionnel)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-section">
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.specialites.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Sidebar Informations -->
    <div class="lg:col-span-1">
        <div class="animate-fade" style="animation-delay: 0.2s">
            
            <!-- Carte d'informations -->
            <div class="form-card">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Informations</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 text-sm">ID</span>
                            <span class="specialty-badge">
                                <i class="fas fa-hashtag"></i>
                                #{{ $specialite->id }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 text-sm">Statut</span>
                            <span class="inline-flex items-center gap-1 text-success text-sm">
                                <i class="fas fa-check-circle"></i>
                                Actif
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 text-sm">Date de création</span>
                            <span class="text-slate-700 text-sm font-medium">
                                {{ $specialite->created_at ? $specialite->created_at->format('d/m/Y') : 'N/A' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 text-sm">Dernière modification</span>
                            <span class="text-slate-700 text-sm font-medium">
                                {{ $specialite->updated_at ? $specialite->updated_at->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Médecins associés -->
            @if($specialite->doctors && $specialite->doctors->count() > 0)
            <div class="form-card mt-5">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user-md"></i>
                        <span>Médecins associés</span>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($specialite->doctors->take(5) as $doctor)
                        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-bg flex items-center justify-center text-primary-blue font-bold text-sm">
                                {{ strtoupper(substr($doctor->user->name ?? 'D', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-slate-700">{{ $doctor->user->name ?? 'Dr. N/A' }}</div>
                                <div class="text-xs text-slate-400">Matricule: {{ $doctor->registration_number ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($specialite->doctors->count() > 5)
                        <div class="text-center pt-2">
                            <span class="text-xs text-primary-blue">
                                +{{ $specialite->doctors->count() - 5 }} autre(s) médecin(s)
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Note -->
            <div class="info-card mt-5">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb text-warning mt-0.5"></i>
                    <div class="text-sm text-slate-600">
                        <span class="font-semibold text-slate-700">Note:</span><br>
                        La modification du nom de la spécialité affectera<br>
                        l'affichage dans tous les profils des médecins.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection