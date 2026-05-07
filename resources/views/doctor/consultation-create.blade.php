@extends('layouts.app')

@section('page_title', 'Nouvelle consultation')
@section('page_subtitle', 'Créer une consultation médicale')

@section('content')

<style>
    .patient-card {
        background: linear-gradient(135deg, #023E8A 0%, #0077B6 100%);
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 24px;
        color: white;
    }
    
    .patient-avatar {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
    }
    
    .form-section {
        background: white;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .form-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #023E8A;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .vital-input, .form-textarea, .form-input {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        width: 100%;
        transition: all 0.2s;
    }
    
    .vital-input:focus, .form-textarea:focus, .form-input:focus {
        outline: none;
        border-color: #00B4D8;
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .btn-save {
        background: linear-gradient(135deg, #10B981, #059669);
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
        color: white;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
    }
    
    .btn-cancel {
        background: #f1f5f9;
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
        color: #475569;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
    }
    
    .vital-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .vital-item {
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    
    .vital-item:hover {
        border-color: #00B4D8;
    }
    
    .info-badge {
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 4px 12px;
        font-size: 13px;
    }
</style>

<div class="max-w-4xl mx-auto px-4 py-6">
    
    <!-- En-tête de sélection du patient ou Infos Patient -->
    @if($patient)
        <div class="patient-card">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="patient-avatar">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold">{{ $patient->user->name }}</h2>
                    <div class="flex flex-wrap gap-4 mt-1 text-white/70 text-sm">
                        <span><i class="fas fa-envelope mr-1"></i> {{ $patient->user->email }}</span>
                        <span><i class="fas fa-phone mr-1"></i> {{ $patient->user->phone ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    @if($appointment)
                        <div class="text-sm opacity-75">Rendez-vous du</div>
                        <div class="font-bold">{{ \Carbon\Carbon::parse($appointment->date_time)->format('d/m/Y H:i') }}</div>
                    @else
                        <div class="text-sm opacity-75">Consultation directe</div>
                        <div class="font-bold">{{ date('d/m/Y H:i') }}</div>
                    @endif
                    <span class="info-badge inline-block mt-2">
                        <i class="fas fa-id-card mr-1"></i> N° Patient: {{ $patient->id }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-user-plus mr-2 text-primary-blue"></i> Sélectionner un patient
            </h3>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Patient</label>
                <select id="patient_select" class="form-input select2" name="patient_id" required>
                    <option value="">-- Choisir un patient --</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->user->name }} ({{ $p->user->phone ?? 'Sans téléphone' }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    
    <!-- Formulaire -->
    <form action="{{ route('doctor.consultations.store') }}" method="POST">
        @csrf
        @if($patient)
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        @else
            <input type="hidden" name="patient_id" id="hidden_patient_id">
        @endif
        
        @if($appointment)
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
        @endif
        
        @if($waitingId)
            <input type="hidden" name="waiting_id" value="{{ $waitingId }}">
        @endif
        
        <!-- Signes vitaux -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-heartbeat mr-2 text-primary-blue"></i> Signes vitaux
            </h3>
            <div class="vital-grid">
                <div class="vital-item">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Poids (kg)</label>
                    <input type="number" step="0.1" name="weight" class="vital-input" placeholder="70.5" id="weight">
                </div>
                <div class="vital-item">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Taille (cm)</label>
                    <input type="number" step="0.1" name="height" class="vital-input" placeholder="175" id="height">
                </div>
                <div class="vital-item">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tension artérielle</label>
                    <input type="text" name="blood_pressure" class="vital-input" placeholder="120/80" id="blood_pressure">
                </div>
                <div class="vital-item">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Température (°C)</label>
                    <input type="number" step="0.1" name="temperature" class="vital-input" placeholder="36.5" id="temperature">
                </div>
                <div class="vital-item">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Fréquence cardiaque</label>
                    <input type="text" name="heart_rate" class="vital-input" placeholder="72 bpm" id="heart_rate">
                </div>
            </div>
        </div>
        
        <!-- Symptômes -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-notes-medical mr-2 text-primary-blue"></i> Symptômes
            </h3>
            <textarea name="symptoms" class="form-textarea" rows="3" placeholder="Décrivez les symptômes du patient..." id="symptoms"></textarea>
        </div>
        
        <!-- Diagnostic -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-clipboard-list mr-2 text-primary-blue"></i> Diagnostic
            </h3>
            <textarea name="diagnosis" class="form-textarea" rows="3" placeholder="Diagnostic médical..." id="diagnosis"></textarea>
        </div>
        
        <!-- Traitement -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-pills mr-2 text-primary-blue"></i> Traitement prescrit
            </h3>
            <textarea name="treatment" class="form-textarea" rows="3" placeholder="Médicaments et traitements prescrits..." id="treatment"></textarea>
        </div>
        
        <!-- Notes -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-comment-dots mr-2 text-primary-blue"></i> Notes supplémentaires
            </h3>
            <textarea name="notes" class="form-textarea" rows="2" placeholder="Informations complémentaires..." id="notes"></textarea>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('doctor.consultations') }}" class="btn-cancel">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save mr-2"></i> Enregistrer la consultation
            </button>
        </div>
    </form>
</div>

<script>
    // Validation des champs numériques
    const weightInput = document.getElementById('weight');
    if (weightInput) {
        weightInput.addEventListener('change', function(e) {
            let val = parseFloat(e.target.value);
            if (isNaN(val)) return;
            if (val < 0) e.target.value = 0;
            if (val > 300) e.target.value = 300;
        });
    }
    
    const heightInput = document.getElementById('height');
    if (heightInput) {
        heightInput.addEventListener('change', function(e) {
            let val = parseFloat(e.target.value);
            if (isNaN(val)) return;
            if (val < 0) e.target.value = 0;
            if (val > 250) e.target.value = 250;
        });
    }
    
    const tempInput = document.getElementById('temperature');
    if (tempInput) {
        tempInput.addEventListener('change', function(e) {
            let val = parseFloat(e.target.value);
            if (isNaN(val)) return;
            if (val < 30) e.target.value = 30;
            if (val > 45) e.target.value = 45;
        });
    }
    
    // Auto-format blood pressure
    const bpInput = document.getElementById('blood_pressure');
    if (bpInput) {
        bpInput.addEventListener('input', function(e) {
            let val = e.target.value.replace(/[^0-9/]/g, '');
            if (val.length > 0 && !val.includes('/') && val.length > 3) {
                val = val.slice(0, 3) + '/' + val.slice(3);
            }
            e.target.value = val;
        });
    }

    // Gestion du sélecteur de patient
    const patientSelect = document.getElementById('patient_select');
    const hiddenPatientId = document.getElementById('hidden_patient_id');
    if (patientSelect && hiddenPatientId) {
        patientSelect.addEventListener('change', function() {
            hiddenPatientId.value = this.value;
        });
    }
</script>

@endsection