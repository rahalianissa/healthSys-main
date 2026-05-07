<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #023E8A; padding-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
        .details { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .total-box { margin-top: 30px; text-align: right; }
        .total-row { font-size: 18px; font-weight: bold; color: #023E8A; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HealthSys</h1>
        <p>Facture Professionnelle</p>
    </div>

    <div class="details">
        <div style="float: left;">
            <h3>De:</h3>
            <p><strong>Clinique HealthSys</strong></p>
            <p>123 Avenue de la Santé</p>
            <p>75000 Paris, France</p>
        </div>
        <div style="float: right; text-align: right;">
            <h3>À:</h3>
            <p><strong>{{ $invoice->patient->user->name }}</strong></p>
            <p>Facture #: {{ $invoice->invoice_number }}</p>
            <p>Date: {{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->description ?? 'Prestation médicale' }}</td>
                <td style="text-align: right;">{{ number_format($invoice->amount, 2) }} DT</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <p>Montant Payé: {{ number_format($invoice->paid_amount, 2) }} DT</p>
        <p class="total-row">Reste à payer: {{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</p>
    </div>

    <div class="footer">
        <p>Document généré par HealthSys le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
