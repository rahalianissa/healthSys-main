@extends('layouts.app')

@section('title', 'Mes rendez-vous')
@section('page-title', 'Prendre ou annuler un rendez-vous')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="welcome-card text-center">
            <h2 class="mb-0">Prendre un rendez-vous</h2>
            <p>Dans un des meilleurs cabinets médicaux, nous avons les meilleurs médecins avec les dernières technologies</p>
            <div class="mt-3">
                <a href="#prendre" class="btn btn-custom-primary me-2">Prendre rendez-vous</a>
                <a href="#annuler" class="btn btn-custom-outline">Annuler rendez-vous</a>
            </div>
        </div>
    </div>
</div>

<div class="row" id="prendre">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Prendre un rendez-vous</h5>
            </div>
            <div class="card-body">
                <form id="appointmentForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Médecin <span class="text-danger">*</span></label>
                        <select id="doctor_id" class="form-control" required>
                            <option value="">Choisir un médecin</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">
                                    Dr. {{ $doctor->user->name ?? 'N/A' }} - {{ $doctor->specialty ?? 'Généraliste' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" id="date" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Heure <span class="text-danger">*</span></label>
                        <input type="time" id="time" class="form-control" min="08:00" max="18:00" value="09:00" required>
                        <small class="text-muted">Disponible de 08:00 à 18:00</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea id="reason" class="form-control" rows="3" placeholder="Décrivez brièvement la raison de votre consultation..."></textarea>
                    </div>
                    
                    <button type="button" class="btn btn-custom w-100" id="bookBtn">
                        <i class="fas fa-calendar-check me-2"></i>Prendre rendez-vous
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Mes rendez-vous</h5>
            </div>
            <div class="card-body" id="myAppointments">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement de vos rendez-vous...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let csrfToken = '{{ csrf_token() }}';
let appointmentsUrl = '{{ url("/patient/appointments") }}';
let bookUrl = '{{ url("/patient/appointments/book") }}';
let cancelUrl = '{{ url("/patient/appointments/cancel") }}';

console.log('URLs:', { appointmentsUrl, bookUrl, cancelUrl });

// Fonction pour charger les rendez-vous
function loadAppointments() {
    console.log('Chargement des rendez-vous...');
    
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
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        let html = '';
        
        // Vérifier si data est un tableau
        let appointments = Array.isArray(data) ? data : (data.data || []);
        
        if(appointments.length > 0) {
            appointments.forEach(app => {
                // Sécurité pour le nom du médecin
                let doctorName = 'Médecin';
                let doctorSpecialty = 'Généraliste';
                
                if(app.doctor) {
                    if(app.doctor.user && app.doctor.user.name) {
                        doctorName = app.doctor.user.name;
                    }
                    if(app.doctor.specialty) {
                        doctorSpecialty = app.doctor.specialty;
                    }
                }
                
                // Formater la date
                let appointmentDate = new Date(app.date_time);
                let formattedDate = appointmentDate.toLocaleDateString('fr-FR');
                let formattedTime = appointmentDate.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                
                // Badge de statut
                let statusBadge = '';
                switch(app.status) {
                    case 'confirmed':
                        statusBadge = '<span class="badge bg-success">Confirmé</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="badge bg-warning text-dark">En attente</span>';
                        break;
                    case 'cancelled':
                        statusBadge = '<span class="badge bg-danger">Annulé</span>';
                        break;
                    case 'completed':
                        statusBadge = '<span class="badge bg-secondary">Terminé</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge bg-info">' + (app.status || 'Inconnu') + '</span>';
                }
                
                html += `
                    <div class="list-group-item mb-3 rounded shadow-sm" data-id="${app.id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-user-md text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>Dr. ${escapeHtml(doctorName)}</strong>
                                        <br>
                                        <small class="text-muted">${escapeHtml(doctorSpecialty)}</small>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <small class="text-muted d-block mb-1">
                                        <i class="far fa-calendar me-2"></i> ${formattedDate}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="far fa-clock me-2"></i> ${formattedTime}
                                    </small>
                                    ${app.reason ? `<small class="text-muted d-block mt-2">
                                        <i class="fas fa-sticky-note me-2"></i> ${escapeHtml(app.reason)}
                                    </small>` : ''}
                                </div>
                            </div>
                            <div class="text-end">
                                ${statusBadge}
                                ${app.status != 'cancelled' && app.status != 'completed' ? `
                                    <button class="btn btn-danger btn-sm mt-2 w-100" onclick="cancelAppointment(${app.id})">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html = `
                <div class="text-center py-5">
                    <i class="far fa-calendar-times fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Aucun rendez-vous trouvé</p>
                    <button class="btn btn-primary mt-3" onclick="document.getElementById('prendre').scrollIntoView({behavior: 'smooth'})">
                        <i class="fas fa-plus me-2"></i>Prendre un rendez-vous
                    </button>
                </div>
            `;
        }
        document.getElementById('myAppointments').innerHTML = html;
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        document.getElementById('myAppointments').innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur: ${error.message}<br>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="loadAppointments()">
                    <i class="fas fa-sync me-1"></i> Réessayer
                </button>
            </div>
        `;
    });
}

// Fonction pour prendre un rendez-vous
function bookAppointment() {
    const doctor_id = document.getElementById('doctor_id').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value || '09:00';
    const reason = document.getElementById('reason').value;
    
    if(!doctor_id || !date) {
        alert('Veuillez sélectionner un médecin et une date');
        return;
    }
    
    // Combiner date et heure
    const dateTime = date + ' ' + time;
    
    const btn = document.getElementById('bookBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi...';
    btn.disabled = true;
    
    fetch(bookUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            doctor_id: doctor_id, 
            date: dateTime,
            reason: reason 
        }),
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if(data.success) {
            // Obtenir le nom du médecin sélectionné
            const doctorSelect = document.getElementById('doctor_id');
            const doctorText = doctorSelect.options[doctorSelect.selectedIndex].text;
            
            // Afficher un message de succès détaillé
            alert(`✓ Rendez-vous pris avec succès!\n\n📅 Date: ${new Date(dateTime).toLocaleDateString('fr-FR')}\n🕐 Heure: ${time}\n👨‍️ Médecin: ${doctorText}\n\n📧 Un email de confirmation vous a été envoyé.`);
            
            // Recharger la liste
            loadAppointments();
            // Réinitialiser le formulaire
            document.getElementById('appointmentForm').reset();
            // Réinitialiser l'heure à 09:00
            document.getElementById('time').value = '09:00';
            // Faire défiler vers la liste
            document.getElementById('myAppointments').scrollIntoView({behavior: 'smooth'});
        } else {
            alert('❌ ' + (data.message || 'Erreur lors de la réservation'));
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error('Erreur:', error);
        alert('❌ ' + (error.message || 'Erreur de connexion. Veuillez réessayer.'));
    });
}

// Fonction pour annuler un rendez-vous
function cancelAppointment(id) {
    if(confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        fetch(`${cancelUrl}/${id}`, {
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
            if(data.success) {
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

// Fonction d'échappement HTML
function escapeHtml(text) {
    if(!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Attacher les événements
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page chargée, chargement des rendez-vous...');
    loadAppointments();
    
    const bookBtn = document.getElementById('bookBtn');
    if(bookBtn) {
        bookBtn.onclick = bookAppointment;
    }
});
</script>

<style>
.welcome-card {
    background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
}
.btn-custom-primary {
    background: #f0b429;
    color: #1a5f7a;
    border: none;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: bold;
}
.btn-custom-outline {
    background: transparent;
    border: 2px solid white;
    color: white;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: bold;
}
.list-group-item {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}
.list-group-item:hover {
    transform: translateX(5px);
    border-left-color: #1a5f7a;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.btn-custom {
    background: #1a5f7a;
    color: white;
    border-radius: 30px;
    padding: 12px;
    font-weight: 600;
}
.btn-custom:hover {
    background: #0d3b4f;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection