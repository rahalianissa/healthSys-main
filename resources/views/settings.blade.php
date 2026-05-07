@extends('layouts.app')

@section('page_title', 'Paramètres')
@section('page_subtitle', 'Gérez vos préférences et configurations')

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

    .settings-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .settings-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .settings-card {
        background: var(--card-bg, white);
        border-radius: 24px;
        border: 1px solid var(--border-color, #e2e8f0);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 24px;
    }
    
    .settings-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .card-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color, #f1f5f9);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .card-header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
    }
    
    .card-header-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .card-body-custom {
        padding: 24px;
    }
    
    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-color, #f1f5f9);
    }
    
    .setting-item:last-child {
        border-bottom: none;
    }
    
    .setting-info h4 {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
    }
    
    .setting-info p {
        font-size: 12px;
        color: var(--text-muted);
    }
    
    /* Switch Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 28px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: var(--primary-blue);
    }
    
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    
    /* Select Language */
    .lang-select {
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-main);
        background: var(--card-bg, white);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-save {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        color: white;
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(2, 62, 138, 0.3);
    }
    
    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-color, #e2e8f0);
        color: var(--text-muted);
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--success);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="max-w-4xl mx-auto">
    <!-- Settings Header -->
    <div class="settings-header animate-fade">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-sliders-h text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">CONFIGURATION</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Paramètres</h1>
            <p class="text-white/60 text-sm">Gérez vos préférences personnelles et les configurations de l'application</p>
        </div>
    </div>

    <!-- Language Settings -->
    <div class="settings-card animate-fade" style="animation-delay: 0.05s">
        <div class="card-header-custom">
            <div class="card-header-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-language"></i>
            </div>
            <div>
                <div class="card-header-title">Langue</div>
                <div class="card-header-desc">Changer la langue de l'interface</div>
            </div>
        </div>
        <div class="card-body-custom">
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Langue de l'application</h4>
                    <p>Sélectionnez votre langue préférée</p>
                </div>
                <select id="languageSelect" class="lang-select">
                    <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                    <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Appearance Settings -->
    <div class="settings-card animate-fade" style="animation-delay: 0.1s">
        <div class="card-header-custom">
            <div class="card-header-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-palette"></i>
            </div>
            <div>
                <div class="card-header-title">Apparence</div>
                <div class="card-header-desc">Personnalisez l'affichage de l'application</div>
            </div>
        </div>
        <div class="card-body-custom">
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Mode sombre</h4>
                    <p>Activer le thème sombre pour l'interface</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="darkModeSwitch">
                    <span class="slider"></span>
                </label>
            </div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Animations</h4>
                    <p>Activer les animations de l'interface</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="animationsSwitch" checked>
                    <span class="slider"></span>
                </label>
            </div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Police compacte</h4>
                    <p>Réduire l'espacement pour afficher plus d'informations</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="compactSwitch">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="settings-card animate-fade" style="animation-delay: 0.15s">
        <div class="card-header-custom">
            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <div class="card-header-title">Notifications</div>
                <div class="card-header-desc">Gérez vos préférences de notifications</div>
            </div>
        </div>
        <div class="card-body-custom">
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Notifications par email</h4>
                    <p>Recevoir des notifications par email</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="emailNotificationsSwitch" checked>
                    <span class="slider"></span>
                </label>
            </div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Rappels de rendez-vous</h4>
                    <p>Recevoir un rappel avant chaque rendez-vous</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="reminderSwitch" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="settings-card animate-fade" style="animation-delay: 0.2s">
        <div class="card-header-custom">
            <div class="card-header-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <div class="card-header-title">Sécurité</div>
                <div class="card-header-desc">Gérez la sécurité de votre compte</div>
            </div>
        </div>
        <div class="card-body-custom">
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Double authentification (2FA)</h4>
                    <p>Sécuriser votre compte avec la double authentification</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="twoFactorSwitch">
                    <span class="slider"></span>
                </label>
            </div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Session actives</h4>
                    <p>Voir et gérer les sessions actives</p>
                </div>
                <button class="btn-outline" style="padding: 8px 20px;" onclick="showToast('info', 'Chargement des sessions...')">
                    <i class="fas fa-desktop mr-1"></i> Gérer
                </button>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end gap-3 mt-6 mb-12 animate-fade" style="animation-delay: 0.3s">
        <button class="btn-outline" onclick="resetSettings()">
            <i class="fas fa-undo-alt mr-2"></i> Réinitialiser
        </button>
        <button class="btn-save" onclick="saveSettings()">
            <i class="fas fa-save mr-2"></i> Enregistrer les paramètres
        </button>
    </div>
</div>

<script>
    // Configuration key
    const STORAGE_KEY = 'healthsys_settings';

    // Apply settings to DOM
    function applySettingsToDOM(settings) {
        if (settings.darkMode) {
            document.documentElement.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
        }
        
        if (settings.compact) {
            document.documentElement.classList.add('compact-mode');
        } else {
            document.documentElement.classList.remove('compact-mode');
        }
    }

    // Sauvegarder les paramètres
    function saveSettings() {
        const settings = {
            language: document.getElementById('languageSelect').value,
            darkMode: document.getElementById('darkModeSwitch').checked,
            animations: document.getElementById('animationsSwitch').checked,
            compact: document.getElementById('compactSwitch').checked,
            emailNotifications: document.getElementById('emailNotificationsSwitch').checked,
            reminder: document.getElementById('reminderSwitch').checked,
            twoFactor: document.getElementById('twoFactorSwitch').checked
        };
        
        // Save to localStorage
        localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
        
        // Apply immediately
        applySettingsToDOM(settings);
        
        // Change language if necessary
        if (settings.language !== '{{ app()->getLocale() }}') {
            window.location.href = '/lang/' + settings.language;
            return; // Redirect will happen
        }
        
        // Send to backend
        fetch('{{ route('settings.save') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(settings)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Paramètres enregistrés avec succès !');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Erreur lors de la sauvegarde');
        });
    }
    
    // Réinitialiser les paramètres
    function resetSettings() {
        if (confirm('Voulez-vous vraiment réinitialiser tous les paramètres ?')) {
            localStorage.removeItem(STORAGE_KEY);
            window.location.reload();
        }
    }
    
    // Charger les paramètres sauvegardés dans le formulaire
    function initForm() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            const settings = JSON.parse(saved);
            document.getElementById('darkModeSwitch').checked = settings.darkMode || false;
            document.getElementById('animationsSwitch').checked = settings.animations !== false;
            document.getElementById('compactSwitch').checked = settings.compact || false;
            document.getElementById('emailNotificationsSwitch').checked = settings.emailNotifications !== false;
            document.getElementById('reminderSwitch').checked = settings.reminder !== false;
            document.getElementById('twoFactorSwitch').checked = settings.twoFactor || false;
            
            applySettingsToDOM(settings);
        }
    }
    
    // Afficher un toast
    function showToast(type, message) {
        const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
        const color = type === 'success' ? '#10B981' : (type === 'error' ? '#EF4444' : '#3B82F6');
        
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.style.backgroundColor = color;
        toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            toast.style.transition = 'all 0.5s ease';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', initForm);
</script>

@endsection
