<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Demande de rendez-vous en attente</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1a5f7a, #0d3b4f); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header span { color: #f0b429; }
        .content { padding: 30px; }
        .info-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 8px; }
        .btn { background: #1a5f7a; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p>Cabinet médical</p>
        </div>
        <div class="content">
            <h2>⏳ Demande de rendez-vous enregistrée</h2>
            <p>Bonjour <strong>{{ $patientName }}</strong>,</p>
            <p>Nous avons bien reçu votre demande de rendez-vous avec <strong>Dr. {{ $doctorName }}</strong>.</p>
            
            <div class="info-box">
                <p><strong>📅 Date :</strong> {{ $date }}</p>
                <p><strong>🕐 Heure :</strong> {{ $time }}</p>
                <p><strong>👨‍⚕️ Médecin :</strong> Dr. {{ $doctorName }}</p>
                @if($reason)
                <p><strong>📝 Motif :</strong> {{ $reason }}</p>
                @endif
            </div>
            
            <p>⚠️ <strong>Statut : En attente de confirmation</strong></p>
            <p>Notre secrétariat va vous contacter sous peu pour confirmer ce rendez-vous.</p>
            <p>Vous recevrez un email de confirmation une fois le rendez-vous validé.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/patient/appointments') }}" class="btn">📋 Voir mes rendez-vous</a>
            </div>
            
            <p style="font-size: 12px; color: #666;">Si vous avez des questions, contactez notre secrétariat.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HealthSys - Prenez soin de votre santé</p>
        </div>
    </div>
</body>
</html>