@extends('layouts.app')

@section('page_title', 'Ajouter un médecin')
@section('page_subtitle', 'Enregistrer un nouveau praticien')

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
</style>

<!-- Form Header -->
<div class="form-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-user-md text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">NOUVEAU PRATICIEN</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Ajouter un médecin</h1>
        <p class="text-white/60 text-sm">Remplissez les informations ci-dessous pour ajouter un nouveau médecin</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        <!-- Informations personnelles -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user-circle"></i>
                <span>Informations personnelles</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nom complet <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Dr. Jean Dupont">
                    @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="dr.dupont@healthsys.com">
                    @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Téléphone <span class="required">*</span></label>
                    <input type="text" name="phone" class="form-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="+216 XX XXX XXX">
                    @error('phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="birth_date" class="form-input @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                    @error('birth_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="form-label">Adresse</label>
                    <textarea name="address" class="form-input @error('address') is-invalid @enderror" rows="2" placeholder="Adresse complète du médecin">{{ old('address') }}</textarea>
                    @error('address') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-stethoscope"></i>
                <span>Informations professionnelles</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Spécialité <span class="required">*</span></label>
                    <select name="specialty" class="form-input @error('specialty') is-invalid @enderror" required>
                        <option value="">Sélectionner une spécialité</option>
                        <option value="Cardiologue" {{ old('specialty') == 'Cardiologue' ? 'selected' : '' }}>❤️ Cardiologue</option>
                        <option value="Dermatologue" {{ old('specialty') == 'Dermatologue' ? 'selected' : '' }}>🔬 Dermatologue</option>
                        <option value="Pédiatre" {{ old('specialty') == 'Pédiatre' ? 'selected' : '' }}>👶 Pédiatre</option>
                        <option value="Gynécologue" {{ old('specialty') == 'Gynécologue' ? 'selected' : '' }}>🤰 Gynécologue</option>
                        <option value="Ophtalmologue" {{ old('specialty') == 'Ophtalmologue' ? 'selected' : '' }}>👁️ Ophtalmologue</option>
                        <option value="Dentiste" {{ old('specialty') == 'Dentiste' ? 'selected' : '' }}>🦷 Dentiste</option>
                        <option value="Orthopédiste" {{ old('specialty') == 'Orthopédiste' ? 'selected' : '' }}>🦴 Orthopédiste</option>
                        <option value="Neurologue" {{ old('specialty') == 'Neurologue' ? 'selected' : '' }}>🧠 Neurologue</option>
                        <option value="Psychiatre" {{ old('specialty') == 'Psychiatre' ? 'selected' : '' }}>🧘 Psychiatre</option>
                        <option value="Généraliste" {{ old('specialty') == 'Généraliste' ? 'selected' : '' }}>🩺 Généraliste</option>
                    </select>
                    @error('specialty') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Numéro d'inscription <span class="required">*</span></label>
                    <input type="text" name="registration_number" class="form-input @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}" required placeholder="MED12345">
                    @error('registration_number') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Honoraire de consultation (DT) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="consultation_fee" class="form-input @error('consultation_fee') is-invalid @enderror" value="{{ old('consultation_fee') }}" required placeholder="150.00">
                    @error('consultation_fee') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Diplôme</label>
                    <input type="text" name="diploma" class="form-input @error('diploma') is-invalid @enderror" value="{{ old('diploma') }}" placeholder="Doctorat en médecine">
                    @error('diploma') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Téléphone cabinet</label>
                    <input type="text" name="cabinet_phone" class="form-input @error('cabinet_phone') is-invalid @enderror" value="{{ old('cabinet_phone') }}" placeholder="+216 XX XXX XXX">
                    @error('cabinet_phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Compte utilisateur -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-lock"></i>
                <span>Compte utilisateur</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Mot de passe <span class="required">*</span></label>
                    <input type="password" name="password" class="form-input @error('password') is-invalid @enderror" required placeholder="••••••••">
                    @error('password') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Confirmer le mot de passe <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="••••••••">
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-primary-bg/30 rounded-xl">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-primary-light mt-0.5 text-sm"></i>
                    <div class="text-xs text-slate-600">
                        <span class="font-semibold">Note:</span> Le médecin recevra un email avec ses identifiants de connexion.
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Enregistrer le médecin
                </button>
            </div>
        </div>
    </form>
</div>

@endsection