<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1a5f7a;
        }
        .header h1 { color: #1a5f7a; font-size: 24px; }
        .info-section { margin-bottom: 30px; overflow: hidden; }
        .info-box {
            width: 48%;
            float: left;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .info-box.right { float: right; }
        .info-box h3 {
            color: #1a5f7a;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th { background: #f5f5f5; }
        .total-section { text-align: right; margin-bottom: 30px; }
        .total-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .grand-total { font-weight: bold; font-size: 16px; color: #1a5f7a; }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>HealthSys</h1>
            <p>Cabinet médical</p>
        </div>
        
        <div class="info-section">
            <div class="info-box">
                <h3>Facturé à :</h3>
                <p><strong>{{ $invoice->patient->user->name }}</strong></p>
                <p>{{ $invoice->patient->user->address ?? 'Adresse non renseignée' }}</p>
                <p>Tél: {{ $invoice->patient->user->phone ?? '-' }}</p>
            </div>
            <div class="info-box right">
                <h3>Détails facture</h3>
                <p><strong>N°:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->issue_date->format('d/m/Y') }}</p>
                <p><strong>Échéance:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr><th>Description</th><th class="text-end">Montant</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->description ?? 'Consultation médicale' }}</td>
                    <td class="text-end">{{ number_format($invoice->amount, 2) }} DT</td>
                </tr>
            </tbody>
        </table>
        
        <div class="total-section">
            <table class="total-table">
                <tr><td>Total</td><td>{{ number_format($invoice->amount, 2) }} DT</td></tr>
                @if($invoice->paid_amount > 0)
                <tr><td style="color: green;">Déjà payé</td><td style="color: green;">- {{ number_format($invoice->paid_amount, 2) }} DT</td></tr>
                @endif
                <tr class="grand-total"><td>Reste à payer</td><td>{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</td></tr>
            </table>
        </div>
        
        <div class="footer">
            <p>Merci de votre confiance !</p>
            <p>© {{ date('Y') }} HealthSys</p>
        </div>
    </div>
</body>
</html>