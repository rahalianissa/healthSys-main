<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte-rendu Médical</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 30px; color: #444; }
        .header { border-bottom: 2px solid #eee; margin-bottom: 30px; padding-bottom: 10px; }
        .section { margin-bottom: 25px; }
        .section-title { font-weight: bold; color: #023E8A; text-transform: uppercase; font-size: 14px; margin-bottom: 10px; border-left: 4px solid #023E8A; padding-left: 10px; }
        .footer { margin-top: 50px; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; color: #03045E;">HealthSys</h1>
        <p style="margin: 5px 0;">Compte-rendu de consultation</p>
    </div>

    <div style="margin-bottom: 30px;">
        <p><strong>Patient :</strong> {{ $patient->user->name }}</p>
        <p><strong>Médecin :</strong> Dr. {{ $doctor->user->name ?? 'Anissa Rahali' }}</p>
        <p><strong>Date :</strong> {{ $date ? $date->format('d/m/Y') : now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Diagnostic</div>
        <p>{{ $diagnosis ?? 'Non spécifié' }}</p>
    </div>

    <div class="section">
        <div class="section-title">Traitement préconisé</div>
        <p>{{ $treatment ?? 'Non spécifié' }}</p>
    </div>

    <div class="section">
        <div class="section-title">Recommandations</div>
        <p>{{ $recommendations ?? 'Non spécifié' }}</p>
    </div>

    <div class="footer">
        <p>Ce document est strictement confidentiel. Document généré automatiquement par le système HealthSys.</p>
    </div>
</body>
</html>
