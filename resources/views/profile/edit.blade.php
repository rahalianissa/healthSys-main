@extends('layouts.app')

@section('page_title', 'Modifier mon profil')
@section('page_subtitle', 'Gérez vos informations personnelles de manière permanente')

@section('content')

<style>
    :root {
        --primary-indigo: #4f46e5;
        --dark-indigo: #312e81;
    }

    .profile-card {
        background: var(--card-bg, white);
        border-radius: 32px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color, #f1f5f9);
        overflow: hidden;
    }

    .form-label-custom {
        display: block;
        font-size: 10px;
        font-weight: 900;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .form-input-custom {
        width: 100%;
        padding: 14px 20px;
        background: var(--content-bg, #f8fafc);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        font-weight: 600;
        color: var(--text-main);
        transition: all 0.3s;
    }

    .form-input-custom:focus {
        outline: none;
        background: var(--card-bg, white);
        border-color: var(--primary-indigo);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .avatar-preview-container {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
    }

    .avatar-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-save-real {
        background: linear-gradient(135deg, var(--primary-indigo) 0%, var(--dark-indigo) 100%);
        color: white;
        padding: 16px 40px;
        border-radius: 20px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-save-real:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
    }

    .success-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #10b981;
        color: white;
        padding: 16px 24px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(16,185,129,0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div class="max-w-6xl mx-auto">
    <!-- Feedback Messages -->
    @if(session('success'))
        <div class="success-toast" id="temporary-toast">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <p class="font-black text-sm uppercase tracking-wider">Succès</p>
                <p class="text-xs opacity-90">{{ session('success') }}</p>
            </div>
        </div>
        <script>setTimeout(() => { document.getElementById('temporary-toast').style.display = 'none'; }, 4000);</script>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Sidebar Gauche : Photo de profil (Réelle) -->
            <div class="space-y-6">
                <div class="profile-card p-10 text-center">
                    <div class="avatar-preview-container mb-6">
                        <!-- // Avatar updates when profile photo is uploaded -->
                        <img id="main-preview" src="{{ auth()->user()->avatar_url }}" alt="Profile" onerror="this.onerror=null;this.src='{{ asset('assets/img/avatars/user.png') }}';">
                    </div>
                    
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">{{ auth()->user()->name }}</h3>
                    <p class="text-slate-400 text-xs font-bold mb-8 uppercase tracking-[0.2em]">{{ auth()->user()->role }}</p>

                    <div class="space-y-4">
                        <label class="block w-full py-3.5 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-sm cursor-pointer hover:bg-indigo-600 hover:text-white transition-all">
                            <i class="fas fa-cloud-upload-alt mr-2"></i> Choisir une photo
                            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
                        </label>
                        <button type="button" id="reset-avatar-btn" class="block w-full py-3.5 border border-slate-100 text-slate-400 rounded-2xl font-black text-sm hover:bg-rose-50 hover:text-rose-500 transition-all uppercase tracking-wider">
                            Supprimer la photo
                        </button>
                    </div>
                </div>

                <div class="profile-card p-6 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Statut du compte</p>
                        <p class="text-lg font-black text-slate-700">Vérifié</p>
                    </div>
                </div>
            </div>

            <!-- Droite : Formulaire (Réel) -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="profile-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Informations personnelles</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="form-label-custom">Nom complet</label>
                            <input type="text" name="name" class="form-input-custom" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="form-label-custom">Adresse Email</label>
                            <input type="email" name="email" class="form-input-custom" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="form-label-custom">Téléphone</label>
                            <input type="text" name="phone" class="form-input-custom" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="space-y-2">
                            <label class="form-label-custom">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-input-custom" value="{{ auth()->user()->birth_date }}">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="form-label-custom">Adresse physique</label>
                            <input type="text" name="address" class="form-input-custom" value="{{ auth()->user()->address }}">
                        </div>
                    </div>
                </div>

                <div class="profile-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-2 h-8 bg-amber-400 rounded-full"></div>
                        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Sécurité</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="form-label-custom">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-input-custom" placeholder="Laisser vide si inchangé">
                        </div>
                        <div class="space-y-2">
                            <label class="form-label-custom">Confirmer</label>
                            <input type="password" name="password_confirmation" class="form-input-custom" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn-save-real">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar-input');
        const mainPreview = document.getElementById('main-preview');
        const resetBtn = document.getElementById('reset-avatar-btn');
        
        // Target global avatars
        const sidebarAvatar = document.getElementById('sidebar-avatar-container');
        const navbarAvatar = document.getElementById('navbar-avatar-container');

        // // FileReader for image preview
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUrl = e.target.result;
                    mainPreview.src = imageUrl;
                    
                    // Update global UI for immediate feedback
                    const imgHtml = `<img src="${imageUrl}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">`;
                    if (sidebarAvatar) sidebarAvatar.innerHTML = imgHtml;
                    if (navbarAvatar) navbarAvatar.innerHTML = imgHtml;
                };
                reader.readAsDataURL(file);
            }
        });

        // Delete Avatar Action
        resetBtn.addEventListener('click', function() {
            if(confirm('Supprimer définitivement votre photo de profil ?')) {
                fetch('{{ route('profile.remove-avatar') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if(data.success) {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>

@endsection
