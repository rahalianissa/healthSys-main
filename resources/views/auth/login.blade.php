<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HealthSys - Connexion & Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #023E8A;
            --primary-light: #0077B6;
            --primary-lighter: #00B4D8;
            --secondary: #90E0EF;
            --accent: #10B981;
            --dark: #03045E;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-soft: #f8fafc;
            --white: #ffffff;
            --error: #ef4444;
            --success: #10B981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f9ff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(2, 62, 138, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 180, 216, 0.05) 0px, transparent 50%);
            padding: 1.5rem;
            overflow-x: hidden;
        }

        /* Decorative Elements */
        .blob {
            position: fixed;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(2, 62, 138, 0.05) 0%, rgba(0, 180, 216, 0.05) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
        .blob-1 { top: -100px; right: -100px; }
        .blob-2 { bottom: -100px; left: -100px; }

        /* Main Container */
        .auth-container {
            position: relative;
            width: 100%;
            max-width: 1000px;
            min-height: 650px;
            background: var(--white);
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(2, 62, 138, 0.15);
            overflow: hidden;
            display: flex;
        }

        /* Form Sections */
        .forms-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .form-box {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 4rem;
            transition: all 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
            z-index: 1;
        }

        .signin-box { left: 0; opacity: 1; }
        .signup-box { left: 0; opacity: 0; pointer-events: none; }

        .auth-container.register-mode .signin-box {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
        }

        .auth-container.register-mode .signup-box {
            transform: translateX(100%);
            opacity: 1;
            pointer-events: all;
            z-index: 2;
        }

        /* Overlay Section */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
            z-index: 10;
        }

        .auth-container.register-mode .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            color: var(--white);
            transform: translateX(0);
            transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .auth-container.register-mode .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 3rem;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .overlay-left { transform: translateX(-20%); }
        .auth-container.register-mode .overlay-left { transform: translateX(0); }

        .overlay-right { right: 0; transform: translateX(0); }
        .auth-container.register-mode .overlay-right { transform: translateX(20%); }

        /* UI Elements */
        .logo-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-lighter), var(--primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 10px 15px -3px rgba(2, 62, 138, 0.3);
        }
        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -1px;
        }
        .logo-text span { color: var(--primary-lighter); }

        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }
        p.subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        /* Form Styling */
        .input-group {
            margin-bottom: 1.25rem;
            width: 100%;
        }
        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            margin-left: 0.25rem;
        }
        .input-field {
            position: relative;
        }
        .input-field i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s;
        }
        .input-field input {
            width: 100%;
            padding: 1rem 1.25rem 1rem 3.25rem;
            background: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.3s;
            font-family: inherit;
        }
        .input-field input:focus {
            background: var(--white);
            border-color: var(--primary-lighter);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.1);
            outline: none;
        }
        .input-field input:focus + i {
            color: var(--primary);
        }

        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 1rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 10px 15px -3px rgba(2, 62, 138, 0.25);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(2, 62, 138, 0.3);
        }
        .btn-submit:active { transform: translateY(0); }

        .btn-ghost {
            background: transparent;
            border: 2px solid var(--white);
            color: var(--white);
            padding: 0.9rem 2.5rem;
            border-radius: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .btn-ghost:hover {
            background: var(--white);
            color: var(--primary);
        }

        /* Extras */
        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -0.5rem;
            margin-bottom: 1.5rem;
        }
        .remember-forgot label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-muted);
            cursor: pointer;
        }
        .remember-forgot input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }
        .forgot-link {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .form-scroll {
            max-height: 100%;
            overflow-y: auto;
            padding-right: 10px;
        }
        .form-scroll::-webkit-scrollbar { width: 5px; }
        .form-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        .alert {
            padding: 1rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

        /* Socials */
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 1.5rem;
        }
        .social-btn {
            width: 50px;
            height: 50px;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            transition: all 0.3s;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .social-btn:hover {
            border-color: var(--primary-lighter);
            color: var(--primary);
            background: #f0f9ff;
        }

        .mobile-toggle {
            display: none;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        .mobile-toggle button {
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        /* Animation Background Shapes */
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }
        .shape-1 { width: 150px; height: 150px; top: -50px; right: -50px; }
        .shape-2 { width: 100px; height: 100px; bottom: 20px; left: -30px; }

        @media (max-width: 850px) {
            .auth-container {
                flex-direction: column;
                min-height: auto;
                max-width: 500px;
                border-radius: 2rem;
            }
            .form-box {
                position: static;
                width: 100%;
                padding: 2.5rem 2rem;
                transform: none !important;
                opacity: 1 !important;
                pointer-events: all !important;
            }
            .overlay-container { display: none; }
            .signin-box, .signup-box { display: none; }
            .auth-container:not(.register-mode) .signin-box { display: flex; }
            .auth-container.register-mode .signup-box { display: flex; }
            .mobile-toggle { display: block; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="auth-container" id="auth-container">
        
        <div class="forms-container">
            
            <!-- ── Sign In ── -->
            <div class="form-box signin-box">
                <div class="logo-box">
                    <div class="logo-icon"><i class="fas fa-heart-pulse"></i></div>
                    <div class="logo-text">Health<span>Sys</span></div>
                </div>
                
                <h1>Bon retour</h1>
                <p class="subtitle">Heureux de vous revoir ! Connectez-vous à votre compte.</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> Vos identifiants sont incorrects.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <label>Adresse Email</label>
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@exemple.com">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Mot de passe</label>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <label>
                            <input type="checkbox" name="remember"> Rester connecté
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">SE CONNECTER</button>
                </form>

                <div class="social-login">
                    <div class="social-btn"><i class="fab fa-google"></i></div>
                    <div class="social-btn"><i class="fab fa-facebook-f"></i></div>
                    <div class="social-btn"><i class="fab fa-apple"></i></div>
                </div>

                <div class="mobile-toggle">
                    Pas encore de compte ? <button onclick="toggleMode()">Créer un compte</button>
                </div>
            </div>

            <!-- ── Sign Up ── -->
            <div class="form-box signup-box">
                <div class="form-scroll">
                    <div class="logo-box">
                        <div class="logo-icon"><i class="fas fa-heart-pulse"></i></div>
                        <div class="logo-text">Health<span>Sys</span></div>
                    </div>

                    <h1>Inscription</h1>
                    <p class="subtitle">Rejoignez notre plateforme de santé intelligente dès aujourd'hui.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="input-group">
                            <label>Nom complet</label>
                            <div class="input-field">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean Dupont">
                            </div>
                            @error('name') <p style="color:red;font-size:0.7rem;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>

                        <div class="input-group">
                            <label>Adresse Email</label>
                            <div class="input-field">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="jean@exemple.com">
                            </div>
                            @error('email') <p style="color:red;font-size:0.7rem;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label>Téléphone</label>
                                <div class="input-field">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="06...">
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Date de naissance</label>
                                <div class="input-field">
                                    <i class="fas fa-calendar"></i>
                                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Adresse</label>
                            <div class="input-field">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="address" value="{{ old('address') }}" placeholder="Ville, Quartier...">
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label>Mot de passe</label>
                                <div class="input-field">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="password" required placeholder="••••••••">
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Confirmation</label>
                                <div class="input-field">
                                    <i class="fas fa-shield-check"></i>
                                    <input type="password" name="password_confirmation" required placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">S'INSCRIRE</button>
                    </form>

                    <div class="mobile-toggle">
                        Déjà un compte ? <button onclick="toggleMode()">Se connecter</button>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── Overlay ── -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <h1>Ravi de vous voir !</h1>
                    <p>Pour rester connecté avec votre santé, veuillez vous connecter avec vos identifiants.</p>
                    <button class="btn-ghost" onclick="toggleMode()">SE CONNECTER</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <h1>Bienvenue !</h1>
                    <p>Commencez votre voyage avec nous pour une meilleure gestion de votre santé.</p>
                    <button class="btn-ghost" onclick="toggleMode()">S'INSCRIRE</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const container = document.getElementById('auth-container');

        function toggleMode() {
            container.classList.toggle('register-mode');
            
            const isRegister = container.classList.contains('register-mode');
            const newTitle = isRegister ? 'Inscription | HealthSys' : 'Connexion | HealthSys';
            const newUrl = isRegister ? '{{ route('register') }}' : '{{ route('login') }}';
            
            document.title = newTitle;
            window.history.pushState({}, '', newUrl);
        }

        @if (request()->routeIs('register'))
            container.classList.add('register-mode');
            document.title = 'Inscription | HealthSys';
        @endif
    </script>
</body>
</html>
