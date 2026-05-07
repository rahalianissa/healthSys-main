@extends('layouts.app')

@section('page_title', 'Modifier un médecin')
@section('page_subtitle', 'Mettre à jour les informations du praticien')

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
    
    .form-input-disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
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
    
    .doctor-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 119, 182, 0.2);
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
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier le médecin</h1>
        <p class="text-white/60 text-sm">Mettez à jour les informations du praticien</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- En-tête avec avatar -->
        <div class="form-section bg-slate-50/50">
            <div class="flex items-center gap-5">
                <div class="doctor-avatar-large">
                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $doctor->user->name }}</h2>
                    <p class="text-slate-500 text-sm">ID: #{{ $doctor->id }} | Matricule: {{ $doctor->registration_number }}</p>
                    <span class="inline-flex items-center gap-1 text-xs text-primary-blue mt-1">
                        <i class="fas fa-calendar-alt"></i>
                        Inscrit le {{ $doctor->created_at->format('d/m/Y') }}
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
                    <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $doctor->user->name) }}" required>
                    @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $doctor->user->email) }}" required>
                    @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Téléphone <span class="required">*</span></label>
                    <input type="text" name="phone" class="form-input @error('phone') is-invalid @enderror" value="{{ old('phone', $doctor->user->phone) }}" required>
                    @error('phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="birth_date" class="form-input @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $doctor->user->birth_date) }}">
                    @error('birth_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="form-label">Adresse</label>
                    <textarea name="address" class="form-input @error('address') is-invalid @enderror" rows="2">{{ old('address', $doctor->user->address) }}</textarea>
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
                        <option value="Cardiologue" {{ old('specialty', $doctor->specialty) == 'Cardiologue' ? 'selected' : '' }}>❤️ Cardiologue</option>
                        <option value="Dermatologue" {{ old('specialty', $doctor->specialty) == 'Dermatologue' ? 'selected' : '' }}>🔬 Dermatologue</option>
                        <option value="Pédiatre" {{ old('specialty', $doctor->specialty) == 'Pédiatre' ? 'selected' : '' }}>👶 Pédiatre</option>
                        <option value="Gynécologue" {{ old('specialty', $doctor->specialty) == 'Gynécologue' ? 'selected' : '' }}>🤰 Gynécologue</option>
                        <option value="Ophtalmologue" {{ old('specialty', $doctor->specialty) == 'Ophtalmologue' ? 'selected' : '' }}>👁️ Ophtalmologue</option>
                        <option value="Dentiste" {{ old('specialty', $doctor->specialty) == 'Dentiste' ? 'selected' : '' }}>🦷 Dentiste</option>
                        <option value="Orthopédiste" {{ old('specialty', $doctor->specialty) == 'Orthopédiste' ? 'selected' : '' }}>🦴 Orthopédiste</option>
                        <option value="Neurologue" {{ old('specialty', $doctor->specialty) == 'Neurologue' ? 'selected' : '' }}>🧠 Neurologue</option>
                        <option value="Psychiatre" {{ old('specialty', $doctor->specialty) == 'Psychiatre' ? 'selected' : '' }}>🧘 Psychiatre</option>
                        <option value="Généraliste" {{ old('specialty', $doctor->specialty) == 'Généraliste' ? 'selected' : '' }}>🩺 Généraliste</option>
                    </select>
                    @error('specialty') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Numéro d'inscription <span class="required">*</span></label>
                    <input type="text" name="registration_number" class="form-input @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $doctor->registration_number) }}" required>
                    @error('registration_number') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Honoraire de consultation (DT) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="consultation_fee" class="form-input @error('consultation_fee') is-invalid @enderror" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required>
                    @error('consultation_fee') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Diplôme</label>
                    <input type="text" name="diploma" class="form-input @error('diploma') is-invalid @enderror" value="{{ old('diploma', $doctor->diploma) }}" placeholder="Doctorat en médecine">
                    @error('diploma') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Téléphone cabinet</label>
                    <input type="text" name="cabinet_phone" class="form-input @error('cabinet_phone') is-invalid @enderror" value="{{ old('cabinet_phone', $doctor->cabinet_phone) }}">
                    @error('cabinet_phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
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
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
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