<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        .header {
            background: linear-gradient(135deg, #03045E 0%, #023E8A 50%, #0077B6 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            font-size: 28px;
            margin: 0;
            font-weight: 800;
        }
        .header span {
            color: #00B4D8;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #03045E;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .content p {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0077B6 0%, #0096C7 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 600;
            margin: 16px 0;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.3);
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }
        .text-muted {
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p style="color: #90E0EF; margin: 8px 0 0; font-size: 14px;">Sécurité du compte</p>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $user->name ?? 'Cher utilisateur' }},</h2>
            
            <p>Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte HealthSys.</p>
            
            <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">
                    <i class="fas fa-key" style="margin-right: 8px;"></i>
                    Réinitialiser mon mot de passe
                </a>
            </div>
            
            <p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email. Votre mot de passe restera inchangé.</p>
            
            <div class="divider"></div>
            
            <p class="text-muted">
                <strong>⚠️ Ce lien est valable pendant 60 minutes.</strong><br>
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
            </p>
            <p class="text-muted" style="word-break: break-all; background: #f1f5f9; padding: 12px; border-radius: 12px;">
                {{ $resetUrl }}
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} HealthSys - Tous droits réservés</p>
            <p style="margin-top: 8px;">Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>