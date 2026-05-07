@extends('layouts.app')

@section('page_title', 'Modifier le rendez-vous')
@section('page_subtitle', 'Mettre à jour les informations du rendez-vous')

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
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-confirmed {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-pending {
        background: #fffbeb;
        color: #d97706;
    }
    
    .status-cancelled {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .status-completed {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .info-card {
        background: var(--primary-bg);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 20px;
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
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Modifier le rendez-vous</h1>
        <p class="text-white/60 text-sm">Mettez à jour les informations du rendez-vous</p>
    </div>
</div>

<!-- Form -->
<div class="form-card animate-fade" style="animation-delay: 0.1s">
    <form action="{{ route('secretaire.appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- En-tête avec statut -->
        <div class="form-section bg-slate-50/50">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-bg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-primary-blue text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Rendez-vous #{{ $appointment->id }}</h2>
                        <p class="text-slate-500 text-sm">Créé le {{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                <div>
                    @php
                        $statusClass = match($appointment->status) {
                            'confirmed' => 'status-confirmed',
                            'pending' => 'status-pending',
                            'cancelled' => 'status-cancelled',
                            'completed' => 'status-completed',
                            default => ''
                        };
                        $statusLabel = match($appointment->status) {
                            'confirmed' => 'Confirmé',
                            'pending' => 'En attente',
                            'cancelled' => 'Annulé',
                            'completed' => 'Terminé',
                            default => $appointment->status
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <i class="fas fa-circle me-1 text-[8px]"></i>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                <span>Informations générales</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Patient <span class="required">*</span></label>
                    <select name="patient_id" class="form-input @error('patient_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name }} - {{ $patient->user->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Médecin <span class="required">*</span></label>
                    <select name="doctor_id" class="form-input @error('doctor_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un médecin</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->user->name }} - {{ $doctor->specialty }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Date et heure <span class="required">*</span></label>
                    <input type="datetime-local" name="date_time" class="form-input @error('date_time') is-invalid @enderror" 
                           value="{{ old('date_time', \Carbon\Carbon::parse($appointment->date_time)->format('Y-m-d\TH:i')) }}" required>
                    @error('date_time') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Durée (minutes)</label>
                    <select name="duration" class="form-input">
                        <option value="15" {{ old('duration', $appointment->duration) == 15 ? 'selected' : '' }}>15 minutes</option>
                        <option value="30" {{ old('duration', $appointment->duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                        <option value="45" {{ old('duration', $appointment->duration) == 45 ? 'selected' : '' }}>45 minutes</option>
                        <option value="60" {{ old('duration', $appointment->duration) == 60 ? 'selected' : '' }}>60 minutes</option>
                        <option value="90" {{ old('duration', $appointment->duration) == 90 ? 'selected' : '' }}>90 minutes</option>
                        <option value="120" {{ old('duration', $appointment->duration) == 120 ? 'selected' : '' }}>120 minutes</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Type et statut -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-tag"></i>
                <span>Type et statut</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Type de consultation <span class="required">*</span></label>
                    <select name="type" class="form-input @error('type') is-invalid @enderror" required>
                        <option value="general" {{ old('type', $appointment->type) == 'general' ? 'selected' : '' }}>🩺 Générale</option>
                        <option value="emergency" {{ old('type', $appointment->type) == 'emergency' ? 'selected' : '' }}>🚨 Urgence</option>
                        <option value="follow_up" {{ old('type', $appointment->type) == 'follow_up' ? 'selected' : '' }}>📋 Suivi</option>
                        <option value="specialist" {{ old('type', $appointment->type) == 'specialist' ? 'selected' : '' }}>👨‍⚕️ Spécialiste</option>
                    </select>
                    @error('type') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Statut <span class="required">*</span></label>
                    <select name="status" class="form-input @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>⏳ En attente</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>✅ Confirmé</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>✔️ Terminé</option>
                    </select>
                    @error('status') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Motif et notes -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                <span>Détails supplémentaires</span>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="form-label">Motif de la consultation</label>
                    <textarea name="reason" class="form-input @error('reason') is-invalid @enderror" rows="3" 
                              placeholder="Décrivez le motif de la consultation...">{{ old('reason', $appointment->reason) }}</textarea>
                    @error('reason') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="form-label">Notes internes</label>
                    <textarea name="notes" class="form-input @error('notes') is-invalid @enderror" rows="3" 
                              placeholder="Notes pour le médecin ou le secrétariat...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Informations des acteurs -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <span>Informations sur les acteurs</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @if($appointment->confirmed_by)
                <div class="info-card">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-primary-blue">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Confirmé par</div>
                            <div class="font-semibold text-slate-700">
                                @php
                                    $confirmedUser = \App\Models\User::find($appointment->confirmed_by);
                                @endphp
                                {{ $confirmedUser->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-slate-400">{{ $appointment->confirmed_at ? \Carbon\Carbon::parse($appointment->confirmed_at)->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($appointment->cancelled_by)
                <div class="info-card" style="background: #fef2f2;">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-red-500">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Annulé par</div>
                            <div class="font-semibold text-slate-700">
                                @php
                                    $cancelledUser = \App\Models\User::find($appointment->cancelled_by);
                                @endphp
                                {{ $cancelledUser->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-slate-400">{{ $appointment->cancelled_at ? \Carbon\Carbon::parse($appointment->cancelled_at)->format('d/m/Y H:i') : 'N/A' }}</div>
                            @if($appointment->cancellation_reason)
                            <div class="text-xs text-red-600 mt-1">Motif: {{ $appointment->cancellation_reason }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="flex justify-end gap-3">
                <a href="{{ route('secretaire.appointments.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Lien pour annuler le rendez-vous directement -->
<div class="mt-4 text-center animate-fade" style="animation-delay: 0.2s">
    @if($appointment->status != 'cancelled' && $appointment->status != 'completed')
    <a href="{{ route('secretaire.appointments.cancel', $appointment->id) }}" 
       class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 text-sm font-medium transition-colors"
       onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action est irréversible.')">
        <i class="fas fa-trash-alt"></i>
        <span>Annuler ce rendez-vous</span>
    </a>
    @endif
</div>

@endsection