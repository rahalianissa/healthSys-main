@extends('layouts.app')

@section('title', 'Facture ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-0">Facture #{{ $invoice->invoice_number }}</h5>
                <small>{{ $invoice->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <div>
                @if($invoice->status == 'paid')
                    <span class="badge bg-success">Payée</span>
                @elseif($invoice->status == 'partially_paid')
                    <span class="badge bg-warning">Partielle</span>
                @elseif($invoice->status == 'cancelled')
                    <span class="badge bg-danger">Annulée</span>
                @else
                    <span class="badge bg-secondary">En attente</span>
                @endif
            </div>
        </div>

        <div class="card-body">
            <h6><i class="fas fa-user-circle me-2"></i>Patient</h6>
            <p>{{ $invoice->patient->user->name }}<br>{{ $invoice->patient->user->email }}<br>{{ $invoice->patient->user->phone ?? 'Non renseigné' }}</p>

            <h6><i class="fas fa-calendar-alt me-2"></i>Dates</h6>
            <p>Émission: {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}<br>Échéance: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</p>

            @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->status != 'paid')
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Facture en retard</p>
            @endif

            <hr>
            <p><strong>Description:</strong> {{ $invoice->description ?? 'Consultation médicale' }}</p>
            <p><strong>Total:</strong> {{ number_format($invoice->amount, 2) }} DT</p>
            <p class="text-success"><strong>Payé:</strong> {{ number_format($invoice->paid_amount, 2) }} DT</p>
            <p class="text-danger"><strong>Reste:</strong> {{ number_format($invoice->amount - $invoice->paid_amount, 2) }} DT</p>

            <hr>
            <h6><i class="fas fa-credit-card me-2"></i>Historique des paiements</h6>
            @if($invoice->payments && $invoice->payments->count() > 0)
                @foreach($invoice->payments as $payment)
                    <div class="border rounded p-2 mb-2 bg-light">
                        <div class="d-flex justify-content-between">
                            <span><strong>{{ number_format($payment->amount, 2) }} DT</strong></span>
                            <span>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                            <span>
                                @if($payment->payment_method == 'cash') 💰 Espèces
                                @elseif($payment->payment_method == 'card') 💳 Carte
                                @elseif($payment->payment_method == 'check') 📝 Chèque
                                @elseif($payment->payment_method == 'transfer') 🏦 Virement
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Aucun paiement enregistré</p>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-end gap-2 flex-wrap">
            @php $remaining = $invoice->amount - $invoice->paid_amount; @endphp

            @if($remaining > 0 && $invoice->status != 'cancelled')
                <a href="{{ route('invoices.pay', $invoice) }}" class="btn btn-success"><i class="fas fa-money-bill me-1"></i> Payer {{ number_format($remaining, 2) }} DT</a>
            @endif

            @if(auth()->user()->role == 'chef_medecine' || auth()->user()->role == 'secretaire')
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Modifier</a>
            @endif

            <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print me-1"></i> Imprimer</button>

            @if(auth()->user()->role == 'patient')
                <a href="{{ route('patient.invoices') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Retour</a>
            @else
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Retour</a>
            @endif
        </div>
    </div>
</div>
@endsection