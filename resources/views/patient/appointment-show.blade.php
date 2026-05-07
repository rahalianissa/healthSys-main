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
        
        let appointments = Array.isArray(data) ? data : (data.data || []);
        
        if(appointments.length > 0) {
            appointments.forEach(app => {
                let doctorName = app.doctor?.user?.name || 'Médecin';
                let doctorSpecialty = app.doctor?.specialty || 'Généraliste';
                
                let appointmentDate = new Date(app.date_time);
                let formattedDate = appointmentDate.toLocaleDateString('fr-FR');
                let formattedTime = appointmentDate.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                
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
            const doctorSelect = document.getElementById('doctor_id');
            const doctorText = doctorSelect.options[doctorSelect.selectedIndex].text;
            
            alert(`✓ Rendez-vous pris avec succès!\n\n📅 Date: ${new Date(dateTime).toLocaleDateString('fr-FR')}\n🕐 Heure: ${time}\n👨‍⚕️ Médecin: ${doctorText}\n\n📧 Un email de confirmation vous a été envoyé.`);
            
            loadAppointments();
            document.getElementById('appointmentForm').reset();
            document.getElementById('time').value = '09:00';
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

// ✅ FONCTION CORRIGÉE POUR ANNULER
function cancelAppointment(id) {
    if(confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // ✅ URL CORRIGÉE
        fetch(`/patient/appointments/cancel/${id}`, {
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

// ✅ NOUVELLE FONCTION POUR VOIR LES DÉTAILS
function showAppointmentDetails(id) {
    fetch(`/patient/appointments/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(`📅 Rendez-vous avec Dr. ${data.doctor_name}\n📆 Date: ${data.date}\n🕐 Heure: ${data.time}\n📝 Statut: ${data.status}`);
        } else {
            alert('Erreur lors du chargement des détails');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur de connexion');
    });
}

function escapeHtml(text) {
    if(!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Page chargée, chargement des rendez-vous...');
    loadAppointments();
    
    const bookBtn = document.getElementById('bookBtn');
    if(bookBtn) {
        bookBtn.onclick = bookAppointment;
    }
});
</script>