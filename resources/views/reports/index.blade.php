@extends('layouts.app')

@section('page_title', 'Rapports et analyses')
@section('page_subtitle', 'Statistiques et rapports détaillés de votre établissement')

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
    
    .report-card {
        background: white;
        border-radius: 24px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .report-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }
    
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-dark);
    }
    
    .btn-report {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        color: white;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 62, 138, 0.2);
    }
    
    .btn-outline-report {
        background: transparent;
        border: 2px solid var(--primary-light);
        color: var(--primary-blue);
        padding: 10px 22px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-outline-report:hover {
        background: var(--primary-light);
        color: white;
    }
    
    .filter-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .filter-select {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        width: 100%;
        background: white;
        transition: all 0.2s;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-lighter);
        box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
    }
    
    .rapport-item {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .rapport-item:hover {
        border-color: var(--primary-lighter);
        transform: translateX(6px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .rapport-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--primary-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 20px;
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
</style>

<!-- Page Header -->
<div class="page-header animate-fade-up">
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
            <i class="fas fa-chart-line text-cyan-300 text-xs"></i>
            <span class="text-white/80 text-xs font-semibold tracking-wider">STATISTIQUES & ANALYSES</span>
        </div>
        <h1 class="text-white text-2xl lg:text-3xl font-bold mb-2">Rapports</h1>
        <p class="text-white/60 text-sm">Analysez les performances de votre cabinet médical</p>
    </div>
</div>

<!-- Statistiques Générales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex justify-between items-start mb-3">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase">Total patients</div>
                <div class="stat-value mt-1">0</div>
            </div>
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="text-xs text-slate-400">+12 ce mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex justify-between items-start mb-3">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase">Rendez-vous</div>
                <div class="stat-value mt-1">0</div>
            </div>
            <div class="stat-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="text-xs text-slate-400">+8% ce mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex justify-between items-start mb-3">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase">Chiffre d'affaires</div>
                <div class="stat-value mt-1">0 DT</div>
            </div>
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="text-xs text-slate-400">+15% vs mois dernier</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex justify-between items-start mb-3">
            <div>
                <div class="text-slate-400 text-xs font-semibold uppercase">Taux d'occupation</div>
                <div class="stat-value mt-1">0%</div>
            </div>
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-percent"></i>
            </div>
        </div>
        <div class="text-xs text-slate-400">Aujourd'hui</div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-card animate-fade-up" style="animation-delay: 0.25s">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-2">Type de rapport</label>
            <select id="reportType" class="filter-select">
                <option value="monthly">📊 Rapport mensuel</option>
                <option value="yearly">📈 Rapport annuel</option>
                <option value="financial">💰 Rapport financier</option>
                <option value="activity">📋 Rapport d'activité</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-2">Période</label>
            <select id="period" class="filter-select">
                <option value="this_month">Ce mois-ci</option>
                <option value="last_month">Mois dernier</option>
                <option value="this_quarter">Ce trimestre</option>
                <option value="this_year">Cette année</option>
                <option value="custom">Personnalisé</option>
            </select>
        </div>
        <div id="customDateRange" style="display: none;" class="md:col-span-2">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2">Date de début</label>
                    <input type="date" id="startDate" class="filter-select">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2">Date de fin</label>
                    <input type="date" id="endDate" class="filter-select">
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end gap-3 mt-5">
        <button onclick="generateReport()" class="btn-report">
            <i class="fas fa-chart-simple mr-2"></i> Générer le rapport
        </button>
        <button onclick="exportReport()" class="btn-outline-report">
            <i class="fas fa-download mr-2"></i> Exporter
        </button>
    </div>
</div>

<!-- Types de rapports -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Rapport Mensuel -->
    <div class="report-card animate-fade-up" style="animation-delay: 0.3s">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="rapport-icon mb-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Rapport mensuel</h3>
                    <p class="text-sm text-slate-500 mt-1">Statistiques détaillées du mois</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                <div>
                    <div class="text-xs text-slate-400">Rendez-vous</div>
                    <div class="font-bold text-slate-800">--</div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-slate-400">Revenus</div>
                    <div class="font-bold text-slate-800">-- DT</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Taux satisfaction</div>
                    <div class="font-bold text-slate-800">--%</div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.reports.monthly') }}" class="block bg-slate-50 px-6 py-3 text-center text-sm font-semibold text-primary-blue hover:bg-primary-bg transition-colors">
            Voir détails <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <!-- Rapport Annuel -->
    <div class="report-card animate-fade-up" style="animation-delay: 0.35s">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="rapport-icon mb-3">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Rapport annuel</h3>
                    <p class="text-sm text-slate-500 mt-1">Évolution sur l'année</p>
                </div>
                <i class="fas fa-chevron-right text-slate-300"></i>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                <div>
                    <div class="text-xs text-slate-400">Total RDV</div>
                    <div class="font-bold text-slate-800">--</div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-slate-400">CA annuel</div>
                    <div class="font-bold text-slate-800">-- DT</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400">Nouveaux patients</div>
                    <div class="font-bold text-slate-800">--</div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.reports.yearly') }}" class="block bg-slate-50 px-6 py-3 text-center text-sm font-semibold text-primary-blue hover:bg-primary-bg transition-colors">
            Voir détails <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<!-- Graphique d'activité -->
<div class="report-card animate-fade-up" style="animation-delay: 0.4s">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Évolution mensuelle</h3>
                <p class="text-sm text-slate-500">Rendez-vous sur les 12 derniers mois</p>
            </div>
        </div>
        <div style="height: 300px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

<!-- Rapports pré-définis -->
<div class="mt-8">
    <h3 class="text-lg font-bold text-slate-800 mb-4">Rapports pré-définis</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <div class="rapport-item" onclick="window.location='{{ route("admin.export.patients") }}'">
            <div class="flex items-center gap-4">
                <div class="rapport-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">Liste des patients</h4>
                    <p class="text-xs text-slate-500">Exporter la liste complète des patients (Excel)</p>
                </div>
                <i class="fas fa-download text-slate-400"></i>
            </div>
        </div>
        
        <div class="rapport-item" onclick="window.location='{{ route("admin.export.appointments") }}'">
            <div class="flex items-center gap-4">
                <div class="rapport-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">Rendez-vous</h4>
                    <p class="text-xs text-slate-500">Exporter la liste des rendez-vous (Excel)</p>
                </div>
                <i class="fas fa-download text-slate-400"></i>
            </div>
        </div>
        
        <div class="rapport-item" onclick="window.location='{{ route("admin.export.invoices") }}'">
            <div class="flex items-center gap-4">
                <div class="rapport-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">Factures</h4>
                    <p class="text-xs text-slate-500">Exporter la liste des factures (Excel)</p>
                </div>
                <i class="fas fa-download text-slate-400"></i>
            </div>
        </div>
        
        <div class="rapport-item">
            <div class="flex items-center gap-4">
                <div class="rapport-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">Synthèse financière</h4>
                    <p class="text-xs text-slate-500">Rapport complet des revenus et dépenses</p>
                </div>
                <i class="fas fa-chevron-right text-slate-400"></i>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour le graphique
    const monthlyData = [65, 72, 88, 95, 102, 98, 115, 128, 142, 138, 145, 158];
    const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Création du graphique
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rendez-vous',
                data: monthlyData,
                borderColor: '#023E8A',
                backgroundColor: 'rgba(2, 62, 138, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0077B6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    ticks: { color: '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });
    
    // Afficher/masquer la plage de dates personnalisée
    const periodSelect = document.getElementById('period');
    const customDateRange = document.getElementById('customDateRange');
    
    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
    });
    
    function generateReport() {
        const reportType = document.getElementById('reportType').value;
        const period = document.getElementById('period').value;
        
        let url = '';
        if (reportType === 'monthly') {
            url = '{{ route("admin.reports.monthly") }}';
        } else if (reportType === 'yearly') {
            url = '{{ route("admin.reports.yearly") }}';
        }
        
        if (url) {
            window.location.href = url;
        } else {
            alert('Fonctionnalité en développement');
        }
    }
    
    function exportReport() {
        alert('Exportation en cours...');
    }
</script>

@endsection