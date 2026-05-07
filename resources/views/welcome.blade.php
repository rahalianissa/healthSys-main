<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HealthSys — Solution Médicale Professionnelle</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'medical-dark': '#03045E',
                        'medical-primary': '#023E8A',
                        'medical-medium': '#0077B6',
                        'medical-light': '#0096C7',
                        'medical-lighter': '#00B4D8',
                        'medical-soft': '#48CAE4',
                        'medical-extra-soft': '#90E0EF',
                        'medical-bg': '#CAF0F8',
                        'medical-deep': '#011025',
                        'medical-mid': '#052659',
                        'accent-green': '#10B981',
                        'accent-green-light': '#34D399',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #03045E 0%, #023E8A 50%, #0077B6 100%);
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0077B6 0%, #0096C7 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -12px rgba(0, 119, 182, 0.4);
        }
        
        .btn-outline-light {
            border: 2px solid #CAF0F8;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background: rgba(202, 240, 248, 0.2);
            transform: translateY(-2px);
        }
        
        .feature-card {
            background: white;
            border-radius: 28px;
            padding: 32px;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .feature-card:hover {
            border-color: #90E0EF;
            box-shadow: 0 20px 25px -12px rgba(0, 148, 199, 0.1);
        }
        
        .service-icon {
            background: linear-gradient(135deg, #CAF0F8 0%, #90E0EF 100%);
            transition: all 0.3s ease;
        }
        
        .service-card:hover .service-icon {
            background: linear-gradient(135deg, #00B4D8 0%, #48CAE4 100%);
        }
        
        .service-card:hover .service-icon i {
            color: white !important;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 28px;
            padding: 28px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            border-color: #0096C7;
            transform: translateY(-4px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #011025 0%, #052659 100%);
            border-radius: 20px;
            padding: 28px 24px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -12px rgba(3, 16, 37, 0.3);
        }
        
        .section-badge {
            background: linear-gradient(135deg, #90E0EF20, #CAF0F840);
            color: #0077B6;
            border-radius: 100px;
            padding: 6px 16px;
            display: inline-block;
            font-size: 13px;
            font-weight: 600;
        }
        
        .hero-wave {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #CAF0F8;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #0077B6;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #00B4D8;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element-delayed {
            animation: float 6s ease-in-out infinite 2s;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">

    <!-- ==================== NAVIGATION ==================== -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-medical-extra-soft/30">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center shadow-lg shadow-medical-primary/25 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-heart-pulse text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold tracking-tight text-medical-dark">Health<span class="text-medical-light">Sys</span></span>
                        <p class="text-[10px] font-semibold text-medical-medium uppercase tracking-wider -mt-1">Medical Platform</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="text-slate-600 hover:text-medical-primary font-medium transition-colors">Accueil</a>
                    <a href="#services" class="text-slate-600 hover:text-medical-primary font-medium transition-colors">Services</a>
                    <a href="#about" class="text-slate-600 hover:text-medical-primary font-medium transition-colors">À propos</a>
                    <a href="#contact" class="text-slate-600 hover:text-medical-primary font-medium transition-colors">Contact</a>
                </div>
                
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-medical-primary text-white rounded-xl font-semibold text-sm hover:bg-medical-medium transition-all shadow-lg shadow-medical-primary/25">
                            <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-slate-600 hover:text-medical-primary font-semibold text-sm transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-medical-primary text-white rounded-xl font-semibold text-sm hover:bg-medical-medium transition-all shadow-lg shadow-medical-primary/25">
                            <i class="fas fa-user-plus mr-2"></i>S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section id="home" class="relative min-h-screen flex items-center pt-32 pb-24 overflow-hidden">
        <div class="absolute inset-0 gradient-bg opacity-5"></div>
        
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-medical-soft/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-medical-lighter/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <div class="section-badge mb-6">
                        <i class="fas fa-certificate text-xs mr-2"></i>
                        <span>Certifié & Sécurisé</span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight mb-6">
                        <span class="text-medical-dark">Soins médicaux</span>
                        <br>
                        <span class="bg-gradient-to-r from-medical-primary to-medical-lighter bg-clip-text text-transparent">modernes</span>
                        <br>
                        <span class="text-medical-dark">et accessibles</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 leading-relaxed mb-10 max-w-lg">
                        HealthSys vous offre une plateforme complète pour gérer vos consultations, 
                        vos dossiers médicaux et prendre rendez-vous en toute simplicité.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-white rounded-xl font-semibold text-center shadow-xl inline-flex items-center justify-center gap-2 group">
                            <span>Commencer maintenant</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#services" class="px-8 py-4 border-2 border-medical-extra-soft rounded-xl font-semibold text-medical-primary hover:bg-medical-extra-soft/20 transition-all text-center inline-flex items-center justify-center gap-2">
                            <i class="fas fa-play-circle"></i>
                            <span>Découvrir nos services</span>
                        </a>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-6 mt-12 pt-6 border-t border-medical-extra-soft/30">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-medical-light"></i>
                            <span class="text-sm text-slate-600">Données sécurisées</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-medical-light"></i>
                            <span class="text-sm text-slate-600">Support 24/7</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-medal text-medical-light"></i>
                            <span class="text-sm text-slate-600">Certification médicale</span>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left" data-aos-duration="1000" class="relative">
                    <div class="floating-element absolute -top-10 -left-10 w-32 h-32 bg-gradient-to-br from-medical-soft to-medical-lighter rounded-2xl rotate-12 opacity-70"></div>
                    <div class="floating-element-delayed absolute -bottom-10 -right-10 w-40 h-40 bg-gradient-to-br from-medical-light to-medical-lighter rounded-full opacity-70"></div>
                    
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&h=600&fit=crop" 
                             alt="Docteur consultant un patient" 
                             class="w-full h-auto object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-medical-dark/20 to-transparent"></div>
                    </div>
                    
                    <div class="absolute -bottom-6 left-6 right-6 bg-white rounded-2xl p-4 shadow-xl flex items-center justify-between gap-4 border-l-4 border-medical-light">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-medical-bg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-medical-primary text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-medical-dark">Rendez-vous en ligne</p>
                                <p class="text-sm text-slate-500">Disponible 24h/24</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-medical-light">+2.500</p>
                            <p class="text-xs text-slate-500">Patients satisfaits</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#CAF0F8" fill-opacity="0.3" d="M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,176C672,171,768,181,864,197.3C960,213,1056,235,1152,234.7C1248,235,1344,213,1392,202.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- ==================== STATS SECTION ==================== -->
    <section class="py-20 bg-medical-bg/30">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
                    <i class="fas fa-smile text-4xl text-medical-extra-soft mb-3"></i>
                    <div class="text-3xl font-bold text-white purecounter" data-purecounter-start="0" data-purecounter-end="5000" data-purecounter-duration="2">0</div>
                    <p class="text-medical-extra-soft font-medium mt-1">Patients satisfaits</p>
                    <p class="text-xs text-medical-soft mt-2">+45% cette année</p>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-user-md text-4xl text-medical-extra-soft mb-3"></i>
                    <div class="text-3xl font-bold text-white purecounter" data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="2">0</div>
                    <p class="text-medical-extra-soft font-medium mt-1">Médecins experts</p>
                    <p class="text-xs text-medical-soft mt-2">+12 nouveaux</p>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-calendar-alt text-4xl text-medical-extra-soft mb-3"></i>
                    <div class="text-3xl font-bold text-white purecounter" data-purecounter-start="0" data-purecounter-end="15000" data-purecounter-duration="2">0</div>
                    <p class="text-medical-extra-soft font-medium mt-1">Rendez-vous traités</p>
                    <p class="text-xs text-medical-soft mt-2">+28% ce trimestre</p>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-clock text-4xl text-medical-extra-soft mb-3"></i>
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <p class="text-medical-extra-soft font-medium mt-1">Support disponible</p>
                    <p class="text-xs text-medical-soft mt-2">Assistance réactive</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SERVICES SECTION ==================== -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="section-badge mx-auto w-fit mb-4">
                    <span>Nos services</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-medical-dark mt-3 mb-5">Une solution complète<br>pour votre cabinet médical</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Découvrez comment HealthSys transforme la gestion des cabinets médicaux avec des outils innovants et intuitifs.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="feature-card service-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="service-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-check text-2xl text-medical-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-medical-dark mb-3">Gestion des rendez-vous</h3>
                    <p class="text-slate-500 leading-relaxed">Planification intelligente et rappels automatiques.</p>
                </div>
                
                <div class="feature-card service-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-folder-medical text-2xl text-medical-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-medical-dark mb-3">Dossiers patients</h3>
                    <p class="text-slate-500 leading-relaxed">Accès sécurisé à l'historique médical complet.</p>
                </div>
                
                <div class="feature-card service-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-invoice-dollar text-2xl text-medical-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-medical-dark mb-3">Facturation intégrée</h3>
                    <p class="text-slate-500 leading-relaxed">Générez des factures et suivez vos paiements.</p>
                </div>
                
                <div class="feature-card service-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-2xl text-medical-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-medical-dark mb-3">Statistiques avancées</h3>
                    <p class="text-slate-500 leading-relaxed">Analysez les performances de votre cabinet.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ABOUT SECTION ==================== -->
    <section id="about" class="py-24 bg-gradient-to-br from-medical-bg/50 to-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&h=500&fit=crop" 
                             alt="Équipe médicale" 
                             class="w-full h-auto object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-5 shadow-xl border-l-4 border-medical-light">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center">
                                <i class="fas fa-award text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-medical-light">+10 ans</p>
                                <p class="text-sm text-slate-500">d'expertise médicale</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <div class="section-badge mb-4">
                        <span>À propos de nous</span>
                    </div>
                    <h2 class="text-4xl font-bold text-medical-dark mt-3 mb-6">Nous réinventons la santé numérique</h2>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        HealthSys est né de la volonté de simplifier la gestion des cabinets médicaux tout en offrant 
                        une expérience optimale aux patients. Notre plateforme allie innovation technologique et expertise médicale.
                    </p>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-medical-light mt-1"></i>
                            <span class="text-slate-600">Plateforme sécurisée certifiée ISO 27001</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-medical-light mt-1"></i>
                            <span class="text-slate-600">Équipe dédiée de techniciens et experts médicaux</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-medical-light mt-1"></i>
                            <span class="text-slate-600">Support client multilingue disponible 24/7</span>
                        </div>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-medical-primary font-semibold hover:gap-3 transition-all">
                        En savoir plus <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TESTIMONIALS SECTION ==================== -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="section-badge mx-auto w-fit mb-4">
                    <span>Témoignages</span>
                </div>
                <h2 class="text-4xl font-bold text-medical-dark mt-3">Ce que disent nos clients</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="0">
                    <i class="fas fa-quote-left text-3xl text-medical-light mb-4"></i>
                    <p class="text-slate-600 leading-relaxed mb-6">"HealthSys a complètement transformé la gestion de mon cabinet. Les patients adorent la facilité de prise de rendez-vous en ligne."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center text-white font-bold">D</div>
                        <div>
                            <h4 class="font-bold text-medical-dark">Dr. Sarah Martin</h4>
                            <p class="text-sm text-slate-500">Cardiologue</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-quote-left text-3xl text-medical-light mb-4"></i>
                    <p class="text-slate-600 leading-relaxed mb-6">"Une interface intuitive et un support réactif. Je recommande HealthSys à tous mes confrères médecins."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center text-white font-bold">K</div>
                        <div>
                            <h4 class="font-bold text-medical-dark">Dr. Karim Ben Ali</h4>
                            <p class="text-sm text-slate-500">Pédiatre</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-quote-left text-3xl text-medical-light mb-4"></i>
                    <p class="text-slate-600 leading-relaxed mb-6">"Le suivi des paiements et des dossiers médicaux n'a jamais été aussi simple. Un gain de temps considérable!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center text-white font-bold">L</div>
                        <div>
                            <h4 class="font-bold text-medical-dark">Leila Mansour</h4>
                            <p class="text-sm text-slate-500">Secrétaire médicale</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- ==================== MAP SECTION (SIDI BOUZID) - VERSION QUI MARCHE ==================== -->
    <section class="py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-12" data-aos="fade-up">
                <div class="section-badge mx-auto w-fit mb-4">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>📍 Notre emplacement</span>
                </div>
                <h2 class="text-4xl font-bold text-medical-dark mt-3 mb-5">Nous trouver</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Visitez notre cabinet médical à Sidi Bouzid</p>
            </div>
            
            <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white" data-aos="fade-up" data-aos-delay="100">
                <!-- OpenStreetMap - Fonctionne sans API Key ! -->
                <iframe 
                    width="100%" 
                    height="450" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=9.438514%2C34.9880676%2C9.588514%2C35.0880676&layer=mapnik&marker=35.0380676%2C9.488514"
                    style="border: 0; min-height: 400px;">
                </iframe>
                
                <div class="absolute inset-0 bg-gradient-to-t from-medical-dark/40 via-transparent to-transparent pointer-events-none"></div>
                
                <div class="absolute bottom-6 left-6 right-6 md:left-auto md:right-6 md:bottom-6 md:w-80 bg-white/95 backdrop-blur-md rounded-xl p-4 shadow-2xl border-l-4 border-medical-primary z-10">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-medical-bg flex items-center justify-center">
                            <i class="fas fa-location-dot text-medical-primary text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-medical-dark">HealthSys - Sidi Bouzid</h4>
                            <p class="text-sm text-slate-500 leading-relaxed mt-1">
                                Avenue Habib Bourguiba, <br>
                                Centre-ville, Sidi Bouzid 9100, Tunisie
                            </p>
                            <a href="https://www.openstreetmap.org/?mlat=35.03807&mlon=9.48851#map=15/35.03807/9.48851" 
                               target="_blank" 
                               class="inline-flex items-center gap-1 text-xs font-semibold text-medical-primary hover:gap-2 transition-all mt-2">
                                Voir sur OpenStreetMap <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="200">
                <div class="inline-flex items-center gap-2 text-slate-500">
                    <i class="fas fa-clock text-medical-light"></i>
                    <span>Lun-Ven: 08:00 - 18:00 | Sam: 09:00 - 13:00</span>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== CONTACT SECTION ==================== -->
    <section id="contact" class="py-24 bg-gradient-to-br from-medical-dark via-medical-primary to-medical-medium relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-80 h-80 bg-medical-light/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-medical-soft/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center text-white mb-12" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 mb-4">
                    <i class="fas fa-headset text-sm text-medical-extra-soft"></i>
                    <span class="text-sm font-semibold">Contactez-nous</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold mt-3 mb-5">Prêt à rejoindre l'aventure ?</h2>
                <p class="text-medical-extra-soft max-w-xl mx-auto">Notre équipe est à votre disposition pour répondre à toutes vos questions.</p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 max-w-4xl mx-auto">
                <div class="space-y-6" data-aos="fade-right">
                    <div class="glass-card rounded-2xl p-6 bg-white/10 backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-medical-extra-soft text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Notre adresse</h4>
                                <p class="text-medical-extra-soft">Avenue Habib Bourguiba, Sidi Bouzid, Tunisie</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card rounded-2xl p-6 bg-white/10 backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <i class="fas fa-phone-alt text-medical-extra-soft text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Téléphone</h4>
                                <p class="text-medical-extra-soft">+216 76 123 456</p>
                                <p class="text-medical-extra-soft">+216 20 123 456</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card rounded-2xl p-6 bg-white/10 backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <i class="fas fa-envelope text-medical-extra-soft text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-1">Email</h4>
                                <p class="text-medical-extra-soft">healthsys.contact@gmail.com</p>
                                <p class="text-medical-extra-soft">healthsys.support@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <form action="https://formspree.io/f/your-form-id" method="POST" class="glass-card rounded-2xl p-8 space-y-5 bg-white/10 backdrop-blur-sm">
                        <div class="grid md:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="Votre nom" required class="w-full px-5 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-medical-extra-soft transition-all">
                            <input type="email" name="email" placeholder="Votre email" required class="w-full px-5 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-medical-extra-soft transition-all">
                        </div>
                        <input type="text" name="subject" placeholder="Sujet" class="w-full px-5 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-medical-extra-soft transition-all">
                        <textarea name="message" rows="5" placeholder="Votre message..." required class="w-full px-5 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-medical-extra-soft transition-all resize-none"></textarea>
                        <button type="submit" class="w-full py-4 bg-white text-medical-primary rounded-xl font-bold hover:bg-medical-bg transition-all shadow-xl inline-flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Envoyer le message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

        <!-- ==================== FOOTER ==================== -->
    <footer class="bg-medical-deep text-white py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid md:grid-cols-4 gap-12">
                <!-- Logo & Description -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-medical-primary to-medical-light flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-heart-pulse text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-extrabold tracking-tight text-white">Health<span class="text-medical-extra-soft">Sys</span></span>
                        </div>
                    </div>
                    <p class="text-medical-extra-soft text-sm leading-relaxed mt-4">
                        La plateforme médicale nouvelle génération pour la gestion optimale de votre cabinet.
                    </p>
                </div>
                
                <!-- Liens rapides -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 border-b border-medical-gray/30 pb-2 inline-block">Liens rapides</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> Accueil</a></li>
                        <li><a href="#services" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> Services</a></li>
                        <li><a href="#about" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> À propos</a></li>
                        <li><a href="#contact" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> Contact</a></li>
                    </ul>
                </div>
                
                <!-- Légal -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 border-b border-medical-gray/30 pb-2 inline-block">Légal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> Mentions légales</a></li>
                        <li><a href="#" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> Politique de confidentialité</a></li>
                        <li><a href="#" class="text-medical-extra-soft hover:text-medical-light transition-colors duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-medical-light"></i> CGU</a></li>
                    </ul>
                </div>
                
                <!-- Suivez-nous & Contact -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-5 border-b border-medical-gray/30 pb-2 inline-block">Suivez-nous</h4>
                    <div class="flex gap-4 mb-6">
                        <a href="#" class="w-11 h-11 rounded-full bg-medical-mid flex items-center justify-center text-medical-extra-soft hover:bg-medical-primary hover:text-white transition-all duration-300 text-lg">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-full bg-medical-mid flex items-center justify-center text-medical-extra-soft hover:bg-medical-primary hover:text-white transition-all duration-300 text-lg">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-full bg-medical-mid flex items-center justify-center text-medical-extra-soft hover:bg-medical-primary hover:text-white transition-all duration-300 text-lg">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-full bg-medical-mid flex items-center justify-center text-medical-extra-soft hover:bg-medical-primary hover:text-white transition-all duration-300 text-lg">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    
                    <!-- 📧 Contact info dans le footer -->
                    <div class="mt-6 pt-4 border-t border-medical-gray/30">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-envelope text-medical-light text-sm"></i>
                            <a href="mailto:healthsys.official@gmail.com" class="text-medical-extra-soft hover:text-medical-light transition-colors text-sm">
                                healthsys.official@gmail.com
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-medical-light text-sm"></i>
                            <span class="text-medical-extra-soft text-sm">Sidi Bouzid, Tunisie</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-medical-gray/30 mt-12 pt-8 text-center">
                <p class="text-medical-extra-soft text-sm">
                    &copy; {{ date('Y') }} HealthSys. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
    
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        
        new PureCounter({
            selector: '.purecounter',
            start: 0,
            end: 0,
            duration: 2,
            delay: 10,
            once: true,
            repeat: false
        });
        
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });
    </script>
</body>
</html>