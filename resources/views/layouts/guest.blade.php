<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HealthSys') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
            min-height: 100vh;
        }
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1a5f7a 0%, #0d3b4f 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(132, 157, 167, 0.4);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #f53737;
        }
        .form-control:focus {
            border-color: #1a5f7a;
            box-shadow: 0 0 0 0.2rem rgba(26,95,122,0.25);
        }
        .input-group-text {
            background-color: #afbbc7;
            border-radius: 10px 0 0 10px;
        }
        a {
            color: #1a5f7a;
            text-decoration: none;
        }
        a:hover {
            color: #0d3b4f;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-hospital-user fa-3x text-white"></i>
                        <h2 class="text-white mt-2">HealthSys</h2>
                        <p class="text-white-50">Système de gestion de cabinet médical</p>
                    </div>
                    
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            {{ $slot }}
                        </div>
                    </div>
                    
                    <div class="text-center text-white-50 mt-4 small">
                        &copy; {{ date('Y') }} HealthSys - Tous droits réservés
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>