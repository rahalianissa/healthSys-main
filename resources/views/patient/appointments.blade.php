@extends('layouts.app')

@section('page_title', 'Mes rendez-vous')
@section('page_subtitle', 'Prenez ou gérez vos rendez-vous médicaux')

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

    .welcome-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 50%, var(--primary-light) 100%);
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .booking-card, .appointments-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .booking-card:hover, .appointments-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        padding: 18px 24px;
        color: white;
    }
    
    .doctor-option {
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .doctor-option:hover {
        background-color: var(--primary-bg);
        transform: translateX(4px);
    }
    
    .doctor-avatar-sm {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: white;
    }
    
    .appointment-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .appointment-item:hover {
        transform: translateX(5px);
        background-color: #f8fafc;
        border-left-color: var(--primary-light);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
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
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-lighter));
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 119, 182, 0.3);
    }
    
    .btn-outline-custom {
        border: 2px solid white;
        background: transparent;
        transition: all 0.3s ease;
    }
    
    .btn-outline-custom:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-state-icon i {
        font-size: 36px;
        color: var(--primary-blue);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .form-control-custom {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 16px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    select.form-control-custom {
        cursor: pointer;
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- ==================== PRENDRE RENDEZ-VOUS ==================== -->
    <div class="booking-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="card-header-custom">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-plus text-xl"></i>
                <div>
                    <h3 class="font-bold text-lg mb-0">Prendre un rendez-vous</h3>
                    <p class="text-white/70 text-xs mt-0.5">Choisissez un médecin et une date</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <form id="appointmentForm">
                @csrf
                
                <!-- Sélection du médecin -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-user-md text-primary-light mr-2"></i>
                        Médecin <span class="text-danger">*</span>
                    </label>
                    <select id="doctor_id" class="form-control-custom" required>
                        <option value="">Sélectionner un médecin</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-specialty="{{ $doctor->specialty ?? 'Généraliste' }}">
                                Dr. {{ $doctor->user->name ?? 'N/A' }} - {{ $doctor->specialty ?? 'Généraliste' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-calendar-day text-primary-light mr-2"></i>
                        Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="date" class="form-control-custom" min="{{ date('Y-m-d') }}" required>
                </div>
                
                <!-- Heure -->
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-clock text-primary-light mr-2"></i>
                        Heure
                    </label>
                    <select id="time" class="form-control-custom">
                        <option value="08:00">08:00</option>
                        <option value="08:30">08:30</option>
                        <option value="09:00" selected>09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Disponible de 08:00 à 18:00</p>
                </div>
                
                <!-- Motif -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-sticky-note text-primary-light mr-2"></i>
                        Motif (optionnel)
                    </label>
                    <textarea id="reason" class="form-control-custom" rows="3" placeholder="Décrivez brièvement la raison de votre consultation..."></textarea>
                </div>
                
                <!-- Bouton -->
                <button type="button" id="bookBtn" class="w-full btn-primary-custom text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-calendar-check"></i>
                    <span>Prendre rendez-vous</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- ==================== MES RENDEZ-VOUS ==================== -->
    <div class="appointments-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="card-header-custom" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-alt text-xl"></i>
                <div>
                    <h3 class="font-bold text-lg mb-0">Mes rendez-vous</h3>
                    <p class="text-white/70 text-xs mt-0.5">Liste de vos consultations</p>
                </div>
            </div>
        </div>
        
        <div id="myAppointments" class="p-4 max-h-[500px] overflow-y-auto">
            <div class="text-center py-8">
                <div class="inline-block w-8 h-8 border-2 border-primary-light border-t-transparent rounded-full animate-spin"></div>
                <p class="text-slate-500 text-sm mt-3">Chargement de vos rendez-vous...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
const csrfToken = '{{ csrf_token() }}';
const appointmentsUrl = '{{ route("patient.appointments") }}';
const bookUrl = '{{ route("patient.book") }}';
const cancelUrlBase = '{{ url("/patient/appointments/cancel") }}';

// Fonction pour charger les rendez-vous
function loadAppointments() {
    fetch(appointmentsUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur HTTP ' + response.status);
        return response.json();
    })
    .then(data => {
        const appointments = Array.isArray(data) ? data : (data.data || []);
        const container = document.getElementById('myAppointments');
        
        if (appointments.length > 0) {
            let html = '<div class="space-y-3">';
            
            appointments.forEach(app => {
                const doctorName = app.doctor?.user?.name || 'Médecin';
                const doctorSpecialty = app.doctor?.specialty || 'Généraliste';
                const appointmentDate = new Date(app.date_time);
                const formattedDate = appointmentDate.toLocaleDateString('fr-FR');
                const formattedTime = appointmentDate.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                
                let statusClass = '', statusLabel = '';
                switch(app.status) {
                    case 'confirmed':
                        statusClass = 'status-confirmed';
                        statusLabel = 'Confirmé';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        statusLabel = 'En attente';
                        break;
                    case 'cancelled':
                        statusClass = 'status-cancelled';
                        statusLabel = 'Annulé';
                        break;
                    case 'completed':
                        statusClass = 'status-completed';
                        statusLabel = 'Terminé';
                        break;
                    default:
                        statusClass = 'status-pending';
                        statusLabel = app.status || 'En attente';
                }
                
                html += `
                    <div class="appointment-item bg-white rounded-xl p-4 border border-slate-100" data-id="${app.id}">
                        <div class="flex justify-between items-start">
                            <div class="flex gap-3">
                                <div class="doctor-avatar-sm flex-shrink-0">
                                    ${doctorName.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">Dr. ${escapeHtml(doctorName)}</h4>
                                    <p class="text-xs text-slate-500">${escapeHtml(doctorSpecialty)}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs text-slate-500">
                                            <i class="far fa-calendar mr-1"></i>${formattedDate}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            <i class="far fa-clock mr-1"></i>${formattedTime}
                                        </span>
                                    </div>
                                    ${app.reason ? `<p class="text-xs text-slate-400 mt-2"><i class="fas fa-sticky-note mr-1"></i>${escapeHtml(app.reason)}</p>` : ''}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="status-badge ${statusClass}">${statusLabel}</span>
                                ${app.status !== 'cancelled' && app.status !== 'completed' ? `
                                    <button onclick="cancelAppointment(${app.id})" class="block mt-2 text-xs text-danger hover:text-red-700 transition-colors">
                                        <i class="fas fa-times-circle mr-1"></i>Annuler
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon mx-auto">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-700 mb-1">Aucun rendez-vous</h4>
                    <p class="text-slate-400 text-sm mb-4">Vous n'avez pas encore de rendez-vous programmé</p>
                    <button onclick="document.querySelector('.booking-card').scrollIntoView({behavior: 'smooth'})" class="btn-primary-custom text-white px-5 py-2 rounded-xl text-sm font-semibold">
                        <i class="fas fa-plus mr-1"></i>Prendre un rendez-vous
                    </button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('myAppointments').innerHTML = `
            <div class="text-center py-8">
                <div class="empty-state-icon mx-auto bg-red-50">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <p class="text-red-500 text-sm">Erreur de chargement</p>
                <button onclick="loadAppointments()" class="mt-3 text-primary-blue text-sm">Réessayer</button>
            </div>
        `;
    });
}

// Fonction pour prendre un rendez-vous
function bookAppointment() {
    const doctorId = document.getElementById('doctor_id').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value || '09:00';
    const reason = document.getElementById('reason').value;
    
    if (!doctorId) {
        alert('Veuillez sélectionner un médecin');
        return;
    }
    if (!date) {
        alert('Veuillez sélectionner une date');
        return;
    }
    
    const dateTime = date + ' ' + time;
    const btn = document.getElementById('bookBtn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<div class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Envoi...';
    btn.disabled = true;
    
    fetch(bookUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            doctor_id: doctorId, 
            date: dateTime,
            reason: reason 
        }),
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            const doctorSelect = document.getElementById('doctor_id');
            const doctorText = doctorSelect.options[doctorSelect.selectedIndex].text;
            alert(`✓ Rendez-vous pris avec succès!\n\n📅 Date: ${new Date(dateTime).toLocaleDateString('fr-FR')}\n🕐 Heure: ${time}\n👨‍⚕️ Médecin: ${doctorText}\n\n📧 Un email de confirmation vous sera envoyé.`);
            
            loadAppointments();
            document.getElementById('appointmentForm').reset();
            document.getElementById('time').value = '09:00';
            document.getElementById('myAppointments').scrollIntoView({behavior: 'smooth'});
        } else {
            alert('❌ ' + (data.message || 'Erreur lors de la réservation. Veuillez réessayer.'));
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error('Erreur:', error);
        alert('❌ Erreur de connexion. Veuillez réessayer.');
    });
}

// Fonction pour annuler un rendez-vous
function cancelAppointment(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        fetch(`${cancelUrlBase}/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Rendez-vous annulé avec succès');
                loadAppointments();
            } else {
                alert('❌ ' + (data.message || 'Erreur lors de l\'annulation'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('❌ Erreur de connexion. Veuillez réessayer.');
        });
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    loadAppointments();
    
    const bookBtn = document.getElementById('bookBtn');
    if (bookBtn) {
        bookBtn.onclick = bookAppointment;
    }
});
</script>

@endsection