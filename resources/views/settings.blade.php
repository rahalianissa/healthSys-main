@extends('layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres du compte')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Paramètres</h5>
            </div>
            <div class="card-body">
                <!-- Paramètres de langue -->
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-globe fa-lg text-primary me-2"></i>
                            <strong>Langue</strong>
                            <p class="text-muted small mb-0">Changer la langue de l'application</p>
                        </div>
                        <select id="languageSelect" class="form-select w-auto" style="min-width: 120px;">
                            <option value="fr" {{ session('locale') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                            <option value="ar" {{ session('locale') == 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                            <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        </select>
                    </div>
                </div>

                <!-- Paramètres de notifications -->
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-bell fa-lg text-warning me-2"></i>
                            <strong>Notifications</strong>
                            <p class="text-muted small mb-0">Recevoir des notifications par email</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notificationsSwitch" 
                                   {{ auth()->user()->notification_preference ?? true ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Paramètres du mode sombre -->
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-moon fa-lg text-secondary me-2"></i>
                            <strong>Mode sombre</strong>
                            <p class="text-muted small mb-0">Activer le mode sombre pour l'interface</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        </div>
                    </div>
                </div>

                <!-- Paramètres de confidentialité -->
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-lock fa-lg text-danger me-2"></i>
                            <strong>Confidentialité</strong>
                            <p class="text-muted small mb-0">Gérer la confidentialité de vos données</p>
                        </div>
                        <button class="btn btn-outline-danger btn-sm" onclick="alert('Fonctionnalité en développement')">
                            <i class="fas fa-shield-alt me-1"></i> Gérer
                        </button>
                    </div>
                </div>

                <!-- Sauvegarde -->
                <div class="mt-4">
                    <button class="btn btn-primary" onclick="saveSettings()">
                        <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mode sombre - charger l'état sauvegardé
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    if (darkModeSwitch) {
        darkModeSwitch.checked = localStorage.getItem('darkMode') === 'true';
    }
    
    // Notifications - charger l'état sauvegardé
    const notifSwitch = document.getElementById('notificationsSwitch');
    if (notifSwitch) {
        const savedNotif = localStorage.getItem('notifications');
        if (savedNotif !== null) {
            notifSwitch.checked = savedNotif === 'true';
        }
    }
});

// Changement de langue
const langSelect = document.getElementById('languageSelect');
if (langSelect) {
    langSelect.addEventListener('change', function(e) {
        window.location.href = '/lang/' + e.target.value;
    });
}

// Mode sombre - événement
const darkModeSwitch = document.getElementById('darkModeSwitch');
if (darkModeSwitch) {
    darkModeSwitch.addEventListener('change', function(e) {
        if (e.target.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
            // Appeler la fonction globale du layout
            if (typeof window.toggleDarkMode === 'function') {
                window.toggleDarkMode(true);
            }
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
            if (typeof window.toggleDarkMode === 'function') {
                window.toggleDarkMode(false);
            }
        }
    });
}

// Notifications - événement
const notifSwitch = document.getElementById('notificationsSwitch');
if (notifSwitch) {
    notifSwitch.addEventListener('change', function(e) {
        localStorage.setItem('notifications', e.target.checked);
        
        // Envoyer au serveur
        fetch('/settings/notifications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ enabled: e.target.checked })
        }).catch(err => console.error('Erreur:', err));
    });
}

// Sauvegarder tous les paramètres
function saveSettings() {
    const darkMode = document.getElementById('darkModeSwitch')?.checked || false;
    const notifications = document.getElementById('notificationsSwitch')?.checked || false;
    
    localStorage.setItem('darkMode', darkMode);
    localStorage.setItem('notifications', notifications);
    
    // Appliquer le mode sombre
    if (darkMode) {
        document.body.classList.add('dark-mode');
        if (typeof window.toggleDarkMode === 'function') {
            window.toggleDarkMode(true);
        }
    } else {
        document.body.classList.remove('dark-mode');
        if (typeof window.toggleDarkMode === 'function') {
            window.toggleDarkMode(false);
        }
    }
    
    alert('✅ Paramètres enregistrés avec succès !');
}
</script>

<style>
/* Mode sombre local */
.dark-mode {
    background-color: #1a1a2e;
    color: #eee;
}
.dark-mode .card,
.dark-mode .card-header,
.dark-mode .list-group-item,
.dark-mode .modal-content,
.dark-mode .dropdown-menu {
    background-color: #16213e;
    color: #eee;
    border-color: #0f3460;
}
.dark-mode .text-muted {
    color: #aaa !important;
}
.dark-mode .border-bottom {
    border-color: #0f3460 !important;
}
.dark-mode .form-control,
.dark-mode .form-select {
    background-color: #0f3460;
    color: #eee;
    border-color: #1a5f7a;
}
.dark-mode .btn-outline-secondary {
    color: #aaa;
    border-color: #1a5f7a;
}
</style>
@endsection