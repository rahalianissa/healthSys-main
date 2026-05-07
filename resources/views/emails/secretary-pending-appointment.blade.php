<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouveau rendez-vous à confirmer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ffc107, #e0a800); color: #1a5f7a; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .info-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 8px; }
        .btn-confirm { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p>Nouvelle demande de rendez-vous</p>
        </div>
        <div class="content">
            <h2>📅 Nouveau rendez-vous à confirmer</h2>
            <p>Bonjour <strong>{{ $secretaryName }}</strong>,</p>
            <p>Un patient a demandé un rendez-vous qui nécessite votre confirmation.</p>
            
            <div class="info-box">
                <p><strong>👤 Patient :</strong> {{ $patientName }}</p>
                <p><strong>👨‍⚕️ Médecin :</strong> Dr. {{ $doctorName }}</p>
                <p><strong>📅 Date :</strong> {{ $date }}</p>
                <p><strong>🕐 Heure :</strong> {{ $time }}</p>
                @if($reason)
                <p><strong>📝 Motif :</strong> {{ $reason }}</p>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/secretaire/appointments/' . $appointmentId . '/confirm') }}" class="btn-confirm">
                    ✅ Confirmer le rendez-vous
                </a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HealthSys</p>
        </div>
    </div>
</body>
</html>