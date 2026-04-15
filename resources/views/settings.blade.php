@extends('layouts.app')

@section('title', __('messages.settings'))
@section('page-title', __('messages.settings'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        
        <!-- Paramètres de langue -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-language text-primary me-2"></i>
                    Langue / اللغة / Language
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="mb-1 fw-semibold">Langue de l'interface</p>
                        <p class="text-muted small mb-0">Changer la langue de l'application</p>
                    </div>
                    <select id="languageSelect" class="form-select" style="width: auto; min-width: 180px;">
                        <option value="fr" {{ session('locale') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                        <option value="ar" {{ session('locale') == 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                        <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Paramètres de notifications -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-bell text-warning me-2"></i>
                    Notifications
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="mb-1 fw-semibold">Notifications par email</p>
                        <p class="text-muted small mb-0">Recevoir des notifications par email</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="notificationsSwitch" 
                               style="width: 50px; height: 25px; cursor: pointer;"
                               {{ auth()->user()->notification_preference ?? true ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres du mode sombre -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-moon text-secondary me-2"></i>
                    Mode sombre
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="mb-1 fw-semibold">Thème sombre</p>
                        <p class="text-muted small mb-0">Activer le mode sombre pour l'interface</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch" 
                               style="width: 50px; height: 25px; cursor: pointer;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres de confidentialité -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-lock text-danger me-2"></i>
                    Confidentialité
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="mb-1 fw-semibold">Protection des données</p>
                        <p class="text-muted small mb-0">Gérer la confidentialité de vos données</p>
                    </div>
                    <button class="btn btn-outline-danger btn-sm" onclick="alert('Fonctionnalité en développement')">
                        <i class="fas fa-shield-alt me-1"></i> Gérer
                    </button>
                </div>
            </div>
        </div>

        <!-- Bouton de sauvegarde -->
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <button class="btn btn-primary px-5 py-2" onclick="saveSettings()">
                    <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                </button>
            </div>
        </div>
        
        <!-- Information sur la langue actuelle -->
        <div class="card shadow-sm border-0 mt-4 bg-light">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-2x text-info"></i>
                    <div>
                        <strong>Langue actuelle :</strong>
                        @if(session('locale') == 'fr')
                            🇫🇷 Français
                        @elseif(session('locale') == 'ar')
                            🇸🇦 العربية
                        @else
                            🇬🇧 English
                        @endif
                        <br>
                        <small class="text-muted">La langue est sauvegardée dans votre session</small>
                    </div>
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
        const savedDarkMode = localStorage.getItem('darkMode');
        darkModeSwitch.checked = savedDarkMode === 'true';
        
        // Appliquer le mode sombre au chargement
        if (savedDarkMode === 'true') {
            document.body.classList.add('dark-mode');
        }
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
        const selectedLang = e.target.value;
        // Rediriger vers la route de changement de langue
        window.location.href = '/lang/' + selectedLang;
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
        const isChecked = e.target.checked;
        localStorage.setItem('notifications', isChecked);
        
        // Envoyer au serveur
        fetch('/settings/notifications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ enabled: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Préférence de notification sauvegardée');
            }
        })
        .catch(err => console.error('Erreur:', err));
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
    
    showToast('success', '✅ Paramètres enregistrés avec succès !');
}

// Fonction pour afficher un toast
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.style.animation = 'slideUp 0.3s ease';
    toast.style.minWidth = '250px';
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Animation CSS pour les toasts
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
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

.dark-mode .bg-light {
    background-color: #0f3460 !important;
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

.dark-mode .btn-outline-secondary:hover {
    background-color: #1a5f7a;
    color: white;
}

.dark-mode .btn-outline-danger {
    color: #ff9999;
    border-color: #dc3545;
}

.dark-mode .btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}
</style>
@endsection