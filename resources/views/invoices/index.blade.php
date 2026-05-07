@extends('layouts.app')

@section('page_title', 'Gestion des factures')
@section('page_subtitle', 'Liste et suivi des factures')

@section('content')

<style>
    :root {
        --primary-blue: #023E8A;
        --primary-dark: #03045E;
        --primary-light: #0077B6;
        --primary-lighter: #00B4D8;
        --primary-soft: #48CAE4;
        --primary-bg: #CAF0F8;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .filter-bar {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
    }
    
    .search-input, .filter-select {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s;
        width: 100%;
    }
    
    .search-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .invoice-table th {
        text-align: left;
        padding: 16px 20px;
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .invoice-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .invoice-row {
        transition: all 0.2s;
    }
    
    .invoice-row:hover {
        background: #f8fafc;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-paid {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-partial {
        background: #fffbeb;
        color: #d97706;
    }
    
    .status-pending {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: scale(1.05);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-state-icon i {
        font-size: 36px;
        color: var(--primary-blue);
    }
    
    .amount-paid {
        color: var(--success);
        font-weight: 600;
    }
    
    .amount-remaining {
        color: var(--danger);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .invoice-table th, .invoice-table td {
            padding: 12px 16px;
        }
        .stats-card {
            padding: 16px;
        }
        .stats-value {
            font-size: 22px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-file-invoice-dollar text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">GESTION FINANCIÈRE</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Factures</h1>
            <p class="text-white/60 text-sm">Liste et suivi des factures émises</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-blue px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-lg">
            <i class="fas fa-plus-circle"></i>
            <span>Nouvelle facture</span>
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Total factures</div>
                <div class="stats-value">{{ $invoices->count() }}</div>
            </div>
            <div class="stats-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-file-invoice"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Montant total</div>
                <div class="stats-value">{{ number_format($invoices->sum('amount'), 0) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Montant payé</div>
                <div class="stats-value text-emerald-600">{{ number_format($invoices->sum('paid_amount'), 0) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase mb-1">Reste à payer</div>
                <div class="stats-value text-rose-600">{{ number_format($invoices->sum('amount') - $invoices->sum('paid_amount'), 0) }} DT</div>
            </div>
            <div class="stats-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-bar animate-fade-up" style="animation-delay: 0.25s">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher..." class="search-input pl-9">
        </div>
        <div>
            <select id="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                <option value="paid">Payée</option>
                <option value="partially_paid">Partiellement payée</option>
                <option value="pending">En attente</option>
            </select>
        </div>
        <div>
            <input type="date" id="dateFrom" class="filter-select" placeholder="Date de début">
        </div>
        <div>
            <input type="date" id="dateTo" class="filter-select" placeholder="Date de fin">
        </div>
    </div>
    <div class="flex justify-end mt-4">
        <button onclick="resetFilters()" class="px-4 py-2 text-slate-500 hover:text-primary-blue transition-colors text-sm">
            <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
        </button>
    </div>
</div>

<!-- Tableau des factures -->
@if($invoices->count() > 0)
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden animate-fade-up" style="animation-delay: 0.3s">
    <div class="overflow-x-auto">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Patient</th>
                    <th>Date d'émission</th>
                    <th>Date d'échéance</th>
                    <th>Montant</th>
                    <th>Payé</th>
                    <th>Reste</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                @php
                    $remaining = $invoice->amount - $invoice->paid_amount;
                    $statusClass = match($invoice->status) {
                        'paid' => 'status-paid',
                        'partially_paid' => 'status-partial',
                        default => 'status-pending'
                    };
                    $statusLabel = match($invoice->status) {
                        'paid' => 'Payée',
                        'partially_paid' => 'Partielle',
                        default => 'En attente'
                    };
                    $isOverdue = ($invoice->due_date < now() && $invoice->status != 'paid');
                @endphp
                <tr class="invoice-row" data-status="{{ $invoice->status }}" data-date="{{ $invoice->created_at->format('Y-m-d') }}">
                    <td class="font-mono text-sm font-semibold text-slate-700">
                        {{ $invoice->invoice_number }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-bg flex items-center justify-center text-primary-blue font-bold text-sm">
                                {{ substr($invoice->patient->user->name ?? 'P', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-slate-700">{{ $invoice->patient->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400">{{ $invoice->patient->user->phone ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-slate-600 text-sm">{{ $invoice->issue_date->format('d/m/Y') }}</td>
                    <td class="text-slate-600 text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                        {{ $invoice->due_date->format('d/m/Y') }}
                        @if($isOverdue)
                            <i class="fas fa-exclamation-triangle ml-1 text-red-500 text-xs" title="Facture en retard"></i>
                        @endif
                    </td>
                    <td class="font-semibold text-slate-700">{{ number_format($invoice->amount, 2) }} DT</td>
                    <td class="amount-paid">{{ number_format($invoice->paid_amount, 2) }} DT</td>
                    <td class="amount-remaining">{{ number_format($remaining, 2) }} DT</td>
                    <td>
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn-action bg-slate-100 text-slate-600 hover:bg-primary-bg hover:text-primary-blue" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn-action bg-amber-50 text-amber-600 hover:bg-amber-100" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($remaining > 0 && $invoice->status != 'cancelled')
                                <a href="{{ route('invoices.pay', $invoice) }}" class="btn-action bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="Payer">
                                    <i class="fas fa-money-bill"></i>
                                </a>
                            @endif
                            @if($invoice->payments->count() > 0)
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-action bg-red-50 text-red-600 hover:bg-red-100" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(method_exists($invoices, 'links'))
<div class="mt-8">
    {{ $invoices->links() }}
</div>
@endif

@else
<div class="empty-state bg-white rounded-2xl border border-slate-100 animate-fade-up" style="animation-delay: 0.3s">
    <div class="empty-state-icon">
        <i class="fas fa-file-invoice-dollar"></i>
    </div>
    <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune facture</h3>
    <p class="text-slate-500 mb-6">Commencez par créer votre première facture</p>
    <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 bg-primary-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
        <i class="fas fa-plus-circle"></i>
        <span>Créer une facture</span>
    </a>
</div>
@endif

<script>
    // Filtrage
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    
    function filterInvoices() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const statusTerm = statusFilter?.value || '';
        const dateFromVal = dateFrom?.value || '';
        const dateToVal = dateTo?.value || '';
        
        const rows = document.querySelectorAll('.invoice-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            let show = true;
            
            // Recherche (cherche dans le texte de la ligne)
            if (searchTerm) {
                const rowText = row.innerText.toLowerCase();
                if (!rowText.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Filtre statut
            if (show && statusTerm) {
                const rowStatus = row.dataset.status;
                if (rowStatus !== statusTerm) {
                    show = false;
                }
            }
            
            // Filtre date
            if (show && (dateFromVal || dateToVal)) {
                const rowDate = row.dataset.date;
                if (dateFromVal && rowDate < dateFromVal) show = false;
                if (dateToVal && rowDate > dateToVal) show = false;
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Afficher message si aucun résultat
        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsMsg) {
                const tableContainer = document.querySelector('.overflow-x-auto');
                if (tableContainer) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMsg';
                    noResultsMsg.className = 'text-center py-12';
                    noResultsMsg.innerHTML = `
                        <div class="empty-state-icon mx-auto">
                            <i class="fas fa-search"></i>
                        </div>
                        <p class="text-slate-500">Aucune facture ne correspond à votre recherche</p>
                    `;
                    tableContainer.parentElement.appendChild(noResultsMsg);
                }
            }
            if (noResultsMsg) noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (dateFrom) dateFrom.value = '';
        if (dateTo) dateTo.value = '';
        filterInvoices();
    }
    
    if (searchInput) searchInput.addEventListener('keyup', filterInvoices);
    if (statusFilter) statusFilter.addEventListener('change', filterInvoices);
    if (dateFrom) dateFrom.addEventListener('change', filterInvoices);
    if (dateTo) dateTo.addEventListener('change', filterInvoices);
</script>

<style>
    /* Styles pour la pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .pagination .page-link:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
        color: var(--primary-blue);
    }
    
    .pagination .active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
    }
    
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@endsection