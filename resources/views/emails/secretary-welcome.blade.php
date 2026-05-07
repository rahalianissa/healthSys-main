<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur HealthSys</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1a5f7a, #0d3b4f); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header span { color: #f0b429; }
        .content { padding: 30px; }
        .credentials { background: #e8f4f8; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .btn { background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p>Bienvenue sur la plateforme</p>
        </div>
        <div class="content">
            <h2>Bienvenue {{ $name }} !</h2>
            <p>Votre compte secrétaire a été créé avec succès.</p>
            
            <div class="credentials">
                <p><strong>📧 Email :</strong> {{ $email }}</p>
                <p><strong>🔑 Mot de passe :</strong> <code>{{ $password }}</code></p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="btn">🔐 Se connecter</a>
            </div>
            
            <p style="font-size: 12px; color: #666;">Pour des raisons de sécurité, changez votre mot de passe après la première connexion.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HealthSys</p>
        </div>
    </div>
</body>
</html>