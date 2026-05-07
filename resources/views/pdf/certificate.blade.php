<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat Médical</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; line-height: 1.6; padding: 40px; }
        .header { text-align: center; margin-bottom: 50px; }
        .content { margin-bottom: 50px; }
        .footer { margin-top: 100px; text-align: right; }
        .stamp { border: 2px solid #023E8A; display: inline-block; padding: 10px; color: #023E8A; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CERTIFICAT MÉDICAL</h1>
        <p>Dr. {{ $doctor->user->name ?? 'Anissa Rahali' }}</p>
        <p>{{ $doctor->specialty ?? 'Médecine Générale' }}</p>
    </div>

    <div class="content">
        <p>Je soussigné, Dr. {{ $doctor->user->name ?? 'Anissa Rahali' }}, certifie après examen de :</p>
        <p><strong>M/Mme/Mlle :</strong> {{ $patient->user->name }}</p>
        <p>Né(e) le : {{ $patient->user->birth_date ? $patient->user->birth_date->format('d/m/Y') : 'N/A' }}</p>
        
        <p style="margin-top: 30px;">
            Que son état de santé {{ $type ?? 'nécessite un repos' }} de <strong>{{ $duration ?? '3' }} jours</strong> 
            à compter du {{ $date ? $date->format('d/m/Y') : now()->format('d/m/Y') }}.
        </p>

        @if(isset($reason))
            <p>Motif : {{ $reason }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Fait à Paris, le {{ now()->format('d/m/Y') }}</p>
        <br><br>
        <div class="stamp">SIGNATURE ET CACHET</div>
    </div>
</body>
</html>
