<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Annulation de rendez-vous</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #dc3545, #b02a37); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header span { color: #f0b429; }
        .content { padding: 30px; }
        .info-box { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; border-radius: 8px; }
        .btn { background: #1a5f7a; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .reason-box { background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p>Cabinet médical</p>
        </div>
        <div class="content">
            <h2>❌ Annulation de rendez-vous</h2>
            <p>Bonjour <strong>{{ $patientName }}</strong>,</p>
            <p>Nous vous informons que votre rendez-vous avec <strong>Dr. {{ $doctorName }}</strong> a été <strong>annulé</strong>.</p>
            
            <div class="info-box">
                <p><strong>📅 Date :</strong> {{ $date }}</p>
                <p><strong>🕐 Heure :</strong> {{ $time }}</p>
                <p><strong>👨‍⚕️ Médecin :</strong> Dr. {{ $doctorName }}</p>
            </div>
            
            @if($reason)
            <div class="reason-box">
                <p><strong>📝 Motif de l'annulation :</strong></p>
                <p>{{ $reason }}</p>
            </div>
            @endif
            
            <p>Pour tout besoin, veuillez contacter notre secrétariat pour reprogrammer un nouveau rendez-vous.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/patient/appointments') }}" class="btn">📋 Prendre un nouveau rendez-vous</a>
            </div>
            
            <p style="font-size: 12px; color: #666;">Nous nous excusons pour ce désagrément.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HealthSys</p>
        </div>
    </div>
</body>
</html>