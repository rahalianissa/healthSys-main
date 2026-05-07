<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HealthSys') }} - Réinitialisation du mot de passe</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            dark: '#03045E',
                            primary: '#023E8A',
                            medium: '#0077B6',
                            light: '#0096C7',
                            lighter: '#00B4D8',
                            soft: '#48CAE4',
                            extralight: '#90E0EF',
                            bg: '#CAF0F8',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #03045E 0%, #023E8A 50%, #0077B6 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }
        
        .auth-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(72, 202, 228, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #0077B6 0%, #0096C7 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -12px rgba(0, 119, 182, 0.4);
        }
        
        .input-field {
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            border-color: #00B4D8;
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
            outline: none;
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
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body class="font-sans antialiased">

<div class="auth-bg"></div>

<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full animate-fade-up">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-medical-medium to-medical-lighter flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-heart-pulse text-white text-2xl"></i>
                </div>
                <div class="text-left">
                    <span class="text-2xl font-extrabold text-white tracking-tight">Health<span class="text-medical-lighter">Sys</span></span>
                    <p class="text-[10px] font-semibold text-medical-extralight uppercase tracking-wider -mt-1">Medical Platform</p>
                </div>
            </a>
        </div>
        
        <!-- Card -->
        <div class="glass-card rounded-3xl shadow-2xl p-8 md:p-10">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-medical-bg rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-medical-primary text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-medical-dark">Nouveau mot de passe</h2>
                <p class="text-slate-500 text-sm mt-2">
                    Créez un nouveau mot de passe pour votre compte.
                </p>
            </div>
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-600 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Form -->
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                
                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-medical-light"></i>
                        Adresse email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                           class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-medical-lighter transition-all"
                           placeholder="exemple@email.com">
                </div>
                
                <!-- Password Field -->
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-medical-light"></i>
                        Nouveau mot de passe
                    </label>
                    <input type="password" name="password" required autocomplete="new-password"
                           class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-medical-lighter transition-all"
                           placeholder="••••••••">
                </div>
                
                <!-- Confirm Password Field -->
                <div class="mb-6">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-medical-light"></i>
                        Confirmer le mot de passe
                    </label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                           class="input-field w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:border-medical-lighter transition-all"
                           placeholder="••••••••">
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-gradient w-full py-3 text-white rounded-xl font-semibold text-center shadow-lg inline-flex items-center justify-center gap-2 transition-all">
                    <i class="fas fa-save"></i>
                    <span>Réinitialiser le mot de passe</span>
                </button>
                
                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-medical-primary hover:text-medical-dark text-sm font-medium transition-colors inline-flex items-center gap-1">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span>Retour à la connexion</span>
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/40 text-xs">
                &copy; {{ date('Y') }} HealthSys. Tous droits réservés.
            </p>
        </div>
    </div>
</div>

</body>
</html>