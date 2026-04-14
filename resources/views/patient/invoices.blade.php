@extends('layouts.app')

@section('title', 'Mes factures')
@section('page-title', 'Mes factures')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i> Mes factures</h5>
        <span class="badge bg-primary rounded-pill">Total: {{ $invoices->count() }}</span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>N° Facture</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        @php $remaining = $invoice->amount - $invoice->paid_amount; @endphp
                        <tr>
                            <td class="fw-bold">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                            <td>{{ number_format($invoice->amount, 2) }} DT</td>
                            <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} DT</td>
                            <td class="text-danger">{{ number_format($remaining, 2) }} DT</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge bg-success">Payée</span>
                                @elseif($invoice->status == 'partially_paid')
                                    <span class="badge bg-warning">Partielle</span>
                                @else
                                    <span class="badge bg-secondary">En attente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                @if($remaining > 0 && $invoice->status != 'cancelled')
                                    <a href="{{ route('invoices.pay', $invoice) }}" class="btn btn-success btn-sm"><i class="fas fa-money-bill"></i> Payer</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mt-4">
                <div class="col-md-4"><div class="card bg-light border-0"><div class="card-body text-center"><i class="fas fa-chart-line fa-2x text-primary mb-2"></i><h5 class="mb-0">{{ number_format($invoices->sum('amount'), 2) }} DT</h5><small class="text-muted">Total facturé</small></div></div></div>
                <div class="col-md-4"><div class="card bg-light border-0"><div class="card-body text-center"><i class="fas fa-check-circle fa-2x text-success mb-2"></i><h5 class="mb-0">{{ number_format($invoices->sum('paid_amount'), 2) }} DT</h5><small class="text-muted">Total payé</small></div></div></div>
                <div class="col-md-4"><div class="card bg-light border-0"><div class="card-body text-center"><i class="fas fa-clock fa-2x text-warning mb-2"></i><h5 class="mb-0">{{ number_format($invoices->sum('amount') - $invoices->sum('paid_amount'), 2) }} DT</h5><small class="text-muted">Reste à payer</small></div></div></div>
            </div>
        @else
            <div class="text-center py-5"><i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3 opacity-25"></i><p class="text-muted mb-0">Aucune facture trouvée</p><a href="{{ route('patient.appointments') }}" class="btn btn-primary mt-3"><i class="fas fa-calendar-plus me-2"></i> Prendre rendez-vous</a></div>
        @endif
    </div>
</div>
@endsection