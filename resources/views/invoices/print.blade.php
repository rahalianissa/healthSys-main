@extends('layouts.print')

@section('title', 'Facture #' . $invoice->invoice_number)

@section('print-content')
<div class="header">
    <h1>HealthSys</h1>
    <p>Cabinet médical - {{ $invoice->issue_date->format('d/m/Y') }}</p>
</div>

<div class="info-box">
    <h3>Facturé à :</h3>
    <p><strong>{{ $invoice->patient->user->name }}</strong><br>
    {{ $invoice->patient->user->address ?? 'Adresse non renseignée' }}<br>
    Tél: {{ $invoice->patient->user->phone ?? '-' }}</p>
</div>

<div class="info-box">
    <h3>Détails facture</h3>
    <p><strong>N° Facture:</strong> {{ $invoice->invoice_number }}<br>
    <strong>Date d'émission:</strong> {{ $invoice->issue_date->format('d/m/Y') }}<br>
    <strong>Date d'échéance:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
</div>

<table>
    <thead>
        <tr><th>Description</th><th class="text-end">Montant</th></tr>
    </thead>
    <tbody>
        <tr><td>{{ $invoice->description ?? 'Consultation médicale' }}</td>
            <td class="text-end">{{ number_format($invoice->amount, 2) }} DT</td>
        </tr>
    </tbody>
    <tfoot>
        <tr style="background: #f5f5f5;"><td><strong>Total</strong></td>
            <td class="text-end"><strong>{{ number_format($invoice->amount, 2) }} DT</strong></td>
        </tr>
        @if($invoice->paid_amount > 0)
        <tr><td>Déjà payé</td><td class="text-end">- {{ number_format($invoice->paid_amount, 2) }} DT</td></tr>
        @endif
        <tr style="background: #e8f4f8;"><td><strong>Reste à payer</strong></td>
            <td class="text-end"><strong>{{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</strong></td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 30px;">
    <p><strong>Détail des prises en charge :</strong></p>
    <ul>
        <li>CNAM : {{ number_format($invoice->cnam_amount, 2) }} DT {{ $invoice->cnam_paid ? '(Payé)' : '(En attente)' }}</li>
        <li>Mutuelle : {{ number_format($invoice->mutuelle_amount, 2) }} DT {{ $invoice->mutuelle_paid ? '(Payé)' : '(En attente)' }}</li>
        <li>Patient : {{ number_format($invoice->patient_amount, 2) }} DT {{ $invoice->patient_paid ? '(Payé)' : '(En attente)' }}</li>
    </ul>
</div>

<div style="margin-top: 40px; text-align: right;">
    <p>Merci de votre confiance !</p>
</div>
@endsection