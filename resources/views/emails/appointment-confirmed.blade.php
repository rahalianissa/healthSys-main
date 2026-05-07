<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rendez-vous confirmé</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header span { color: #f0b429; }
        .content { padding: 30px; }
        .info-box { background: #e8f4f8; border-left: 4px solid #1a5f7a; padding: 15px; margin: 20px 0; border-radius: 8px; }
        .btn { background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .checklist { list-style: none; padding: 0; }
        .checklist li { margin: 10px 0; padding-left: 25px; position: relative; }
        .checklist li:before { content: "✓"; color: #28a745; font-weight: bold; position: absolute; left: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Health<span>Sys</span></h1>
            <p>Cabinet médical</p>
        </div>
        <div class="content">
            <h2>✅ Rendez-vous confirmé !</h2>
            <p>Bonjour <strong>{{ $patientName }}</strong>,</p>
            <p>Votre rendez-vous avec <strong>Dr. {{ $doctorName }}</strong> a été <strong>confirmé</strong> par notre secrétariat.</p>
            
            <div class="info-box">
                <p><strong>📅 Date :</strong> {{ $date }}</p>
                <p><strong>🕐 Heure :</strong> {{ $time }}</p>
                <p><strong>👨‍⚕️ Médecin :</strong> Dr. {{ $doctorName }}</p>
                <p><strong>🏥 Spécialité :</strong> {{ $doctorSpecialty }}</p>
            </div>
            
            <p><strong>📌 À préparer :</strong></p>
            <ul class="checklist">
                <li>Votre carte d'identité</li>
                <li>Votre carte de mutuelle / CNAM</li>
                <li>Vos examens médicaux récents</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/patient/appointments') }}" class="btn">📋 Voir mes rendez-vous</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HealthSys</p>
        </div>
    </div>
</body>
</html>