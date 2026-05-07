<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HealthSys') }}</title>

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                        }
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    
    <style>
        .auth-glow { background: radial-gradient(circle at 50% -20%, rgba(79, 70, 229, 0.15) 0%, transparent 50%), radial-gradient(circle at 50% 120%, rgba(16, 185, 129, 0.1) 0%, transparent 50%); }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05); }
        .btn-gradient { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); transition: all 0.3s ease; }
        .btn-gradient:hover { transform: scale(1.02); box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4); }
        input:focus { border-color: #4f46e5 !important; ring-color: rgba(79, 70, 229, 0.2) !important; }
    </style>
</head>
<body class="font-sans text-slate-900 bg-[#f8fafc] auth-glow antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-10" data-aos="fade-down">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-house-pulse text-white text-2xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold tracking-tighter text-indigo-900 leading-none">Health<span class="text-emerald-500">Sys</span></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-1">Medical Ecosystem</span>
                </div>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-10 py-12 glass-card rounded-[3rem] shadow-2xl relative z-10 mx-4">
            {{ $slot }}
        </div>
        
        <div class="mt-12 text-center">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">&copy; {{ date('Y') }} HealthSys Global • Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
