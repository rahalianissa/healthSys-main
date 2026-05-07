@extends('layouts.app')

@section('page_title', 'Prendre un rendez-vous')
@section('page_subtitle', 'Planification d\'une nouvelle consultation')

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
    
    .info-box {
        background: var(--primary-bg);
        border-radius: 16px;
        padding: 16px;
        margin-top: 20px;
    }
    
    .info-box i {
        color: var(--primary-blue);
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
    
    /* Date picker customization */
    input[type="datetime-local"] {
        padding: 12px 16px;
    }
    
    /* Select customization */
    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-calendar-plus text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">NOUVEAU RENDEZ-VOUS</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Prendre un rendez-vous</h1>
        <p class="text-white/60 text-sm">Planifiez une nouvelle consultation pour un patient</p>
    </div>
</div>

<!-- Formulaire -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('secretaire.appointments.store') }}" method="POST">
        @csrf

        <!-- Sélection Patient et Médecin -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <span>Informations du rendez-vous</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-user mr-1 text-slate-400"></i> Patient 
                        <span class="required">*</span>
                    </label>
                    <select name="patient_id" class="form-input @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name }} - {{ $patient->user->phone ?? 'Pas de téléphone' }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                </div>
                
                <!-- Médecin -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-user-md mr-1 text-slate-400"></i> Médecin 
                        <span class="required">*</span>
                    </label>
                    <select name="doctor_id" id="doctor_id" class="form-input @error('doctor_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un médecin --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-fee="{{ $doctor->consultation_fee }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->user->name }} - {{ $doctor->specialty }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                </div>
            </div>
        </div>

        <!-- Date et Heure -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-calendar-alt"></i>
                <span>Date et horaire</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">
                        <i class="fas fa-calendar-day mr-1 text-slate-400"></i> Date et heure 
                        <span class="required">*</span>
                    </label>
                    <input type="datetime-local" name="date_time" class="form-input @error('date_time') is-invalid @enderror" 
                           value="{{ old('date_time') }}" required>
                    @error('date_time') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                </div>
                
                <div>
                    <label class="form-label">
                        <i class="fas fa-hourglass-half mr-1 text-slate-400"></i> Durée (minutes)
                    </label>
                    <select name="duration" class="form-input">
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">60 minutes</option>
                    </select>
                </div>
            </div>
            
            <!-- Info horaires -->
            <div class="info-box flex items-start gap-3">
                <i class="fas fa-clock mt-0.5"></i>
                <div class="text-sm text-slate-600">
                    <span class="font-semibold">Horaires de consultation:</span> 
                    Lundi - Vendredi: 08:00 - 18:00 | Samedi: 09:00 - 13:00
                </div>
            </div>
        </div>

        <!-- Type et Motif -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-notes-medical"></i>
                <span>Détails de la consultation</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">
                        <i class="fas fa-tag mr-1 text-slate-400"></i> Type de consultation 
                        <span class="required">*</span>
                    </label>
                    <select name="type" class="form-input @error('type') is-invalid @enderror" required>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>🩺 Générale</option>
                        <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>🚨 Urgence</option>
                        <option value="follow_up" {{ old('type') == 'follow_up' ? 'selected' : '' }}>📋 Suivi</option>
                        <option value="specialist" {{ old('type') == 'specialist' ? 'selected' : '' }}>👨‍⚕️ Spécialiste</option>
                    </select>
                    @error('type') 
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                    @enderror
                </div>
                
                <div>
                    <label class="form-label">
                        <i class="fas fa-coins mr-1 text-slate-400"></i> Honoraire estimé
                    </label>
                    <div class="form-input bg-slate-50" id="fee_display" style="cursor: default;">
                        -- DT
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <label class="form-label">
                    <i class="fas fa-comment-medical mr-1 text-slate-400"></i> Motif de la consultation
                </label>
                <textarea name="reason" class="form-input @error('reason') is-invalid @enderror" rows="3" 
                          placeholder="Décrivez brièvement le motif de la consultation...">{{ old('reason') }}</textarea>
                @error('reason') 
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                @enderror
            </div>
            
            <div class="mt-4">
                <label class="form-label">
                    <i class="fas fa-sticky-note mr-1 text-slate-400"></i> Notes internes
                </label>
                <textarea name="notes" class="form-input" rows="2" 
                          placeholder="Informations supplémentaires (visibles seulement par le personnel)">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="flex justify-end gap-3">
                <a href="{{ route('secretaire.appointments.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Prendre rendez-vous
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Afficher l'honoraire du médecin sélectionné
    const doctorSelect = document.getElementById('doctor_id');
    const feeDisplay = document.getElementById('fee_display');
    
    if (doctorSelect) {
        doctorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const fee = selectedOption.getAttribute('data-fee');
            
            if (fee && fee > 0) {
                feeDisplay.innerHTML = '<span class="font-semibold text-primary-blue">' + parseFloat(fee).toFixed(2) + ' DT</span>';
                feeDisplay.classList.remove('bg-slate-50');
                feeDisplay.classList.add('bg-primary-bg');
            } else {
                feeDisplay.innerHTML = '-- DT';
                feeDisplay.classList.remove('bg-primary-bg');
                feeDisplay.classList.add('bg-slate-50');
            }
        });
        
        // Déclencher l'événement si une valeur est déjà sélectionnée
        if (doctorSelect.value) {
            doctorSelect.dispatchEvent(new Event('change'));
        }
    }
    
    // Validation de la date (ne peut pas être dans le passé)
    const dateInput = document.querySelector('input[name="date_time"]');
    if (dateInput) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        dateInput.setAttribute('min', minDateTime);
    }
</script>

@endsection