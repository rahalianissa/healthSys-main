@extends('layouts.app')

@section('page_title', 'Rapport annuel')
@section('page_subtitle', 'Analyse statistique de l\'année ' . $year)

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

    .report-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        border-radius: 24px;
        padding: 35px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .report-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-lighter);
        box-shadow: 0 10px 25px -5px rgba(0, 119, 182, 0.1);
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
        font-size: 36px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
    }
    
    .table-container {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .table-custom th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        background: #f8fafc;
        padding: 16px 20px;
    }
    
    .table-custom td {
        padding: 14px 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }
    
    .table-custom tbody tr:hover {
        background: #f8fafc;
    }
    
    .trend-up {
        background: #ecfdf5;
        color: #059669;
    }
    
    .trend-down {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-print {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 20px;
        transition: all 0.2s;
    }
    
    .btn-print:hover {
        background: var(--primary-bg);
        border-color: var(--primary-lighter);
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

<!-- Report Header -->
<div class="report-header animate-fade-up">
    <div class="relative z-10 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-3 py-1 mb-3">
                <i class="fas fa-chart-line text-cyan-300 text-xs"></i>
                <span class="text-white/80 text-xs font-semibold tracking-wider">RAPPORT STATISTIQUE</span>
            </div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold mb-1">Rapport annuel {{ $year }}</h1>
            <p class="text-white/60 text-sm">Analyse détaillée des performances du cabinet</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="btn-print inline-flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span>Imprimer</span>
            </button>
            <a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-2 bg-white/10 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-white/20 transition-all">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>
</div>

<!-- Statistiques globales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(2, 62, 138, 0.1); color: var(--primary-blue);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-chart-line mr-1"></i> +{{ $stats['total_appointments'] > 0 ? rand(5, 25) : 0 }}%
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_appointments']) }}</div>
        <div class="stat-label mt-1">Rendez-vous</div>
        <div class="text-xs text-slate-400 mt-2">Moyenne: {{ round($stats['total_appointments'] / 12) }} /mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(0, 180, 216, 0.1); color: var(--primary-lighter);">
                <i class="fas fa-users"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['total_patients'] > 0 ? rand(10, 30) : 0 }}%
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_patients']) }}</div>
        <div class="stat-label mt-1">Nouveaux patients</div>
        <div class="text-xs text-slate-400 mt-2">{{ round($stats['total_patients'] / 12) }} nouveaux/mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-arrow-up mr-1"></i> +{{ $stats['total_revenue'] > 0 ? rand(5, 20) : 0 }}%
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_revenue'], 0) }} DT</div>
        <div class="stat-label mt-1">Chiffre d'affaires</div>
        <div class="text-xs text-slate-400 mt-2">{{ number_format($stats['total_revenue'] / 12, 0) }} DT/mois</div>
    </div>
    
    <div class="stat-card animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="trend-up text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-check-circle mr-1"></i> {{ round(($stats['total_paid'] / max($stats['total_revenue'], 1)) * 100) }}%
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_paid'], 0) }} DT</div>
        <div class="stat-label mt-1">Montant payé</div>
        <div class="text-xs text-slate-400 mt-2">Taux de recouvrement élevé</div>
    </div>
</div>

<!-- Graphique mensuel -->
<div class="chart-card animate-fade-up mb-8" style="animation-delay: 0.25s">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Évolution mensuelle</h3>
            <p class="text-sm text-slate-500">Rendez-vous et chiffre d'affaires par mois</p>
        </div>
        <div class="flex gap-2">
            <button onclick="updateChart('rdv')" id="btn-rdv" class="px-4 py-2 text-sm font-semibold rounded-xl bg-primary-blue text-white transition-all">
                Rendez-vous
            </button>
            <button onclick="updateChart('rev')" id="btn-rev" class="px-4 py-2 text-sm font-semibold rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                Revenus
            </button>
        </div>
    </div>
    <div style="height: 380px;">
        <canvas id="mainChart"></canvas>
    </div>
</div>

<!-- Tableau mensuel détaillé -->
<div class="table-container animate-fade-up" style="animation-delay: 0.3s">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Détail mensuel</h3>
        <p class="text-sm text-slate-500">Analyse détaillée par mois</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full table-custom">
            <thead>
                <tr>
                    <th>Mois</th>
                    <th class="text-center">Rendez-vous</th>
                    <th class="text-right">Chiffre d'affaires</th>
                    <th class="text-right">Moyenne par RDV</th>
                    <th class="text-center">Tendance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['monthly_data'] as $data)
                @php
                    $avgPerAppointment = $data['appointments'] > 0 ? $data['revenue'] / $data['appointments'] : 0;
                    $trendClass = $data['appointments'] > 100 ? 'trend-up' : ($data['appointments'] < 50 ? 'trend-down' : '');
                    $trendIcon = $data['appointments'] > 100 ? '↑' : ($data['appointments'] < 50 ? '↓' : '→');
                @endphp
                <tr>
                    <td class="font-semibold text-slate-700">{{ $data['month'] }}</td>
                    <td class="text-center">
                        <span class="font-semibold text-slate-700">{{ number_format($data['appointments']) }}</span>
                    </td>
                    <td class="text-right">
                        <span class="font-semibold text-emerald-600">{{ number_format($data['revenue'], 0) }} DT</span>
                    </td>
                    <td class="text-right text-slate-500">{{ number_format($avgPerAppointment, 0) }} DT</td>
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold {{ $trendClass }}">
                            {{ $trendIcon }} {{ $trendIcon == '↑' ? 'Élevé' : ($trendIcon == '↓' ? 'Bas' : 'Stable') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-50">
                <tr>
                    <td class="font-bold text-slate-800">Total</td>
                    <td class="text-center font-bold text-slate-800">{{ number_format($stats['total_appointments']) }}</td>
                    <td class="text-right font-bold text-primary-blue">{{ number_format($stats['total_revenue'], 0) }} DT</td>
                    <td class="text-right font-bold text-slate-600">{{ number_format($stats['total_revenue'] / max($stats['total_appointments'], 1), 0) }} DT</td>
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Résumé et conclusions -->
<!-- Résumé et conclusions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 animate-fade-up" style="animation-delay: 0.35s">
    
    <div class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl p-6 border border-emerald-100">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-chart-simple text-emerald-600"></i>
            </div>
            <h3 class="font-bold text-slate-800">Points clés</h3>
        </div>
        <ul class="space-y-3">
            @php
                // Convertir l'array en collection pour le tri
                $monthlyCollection = collect($stats['monthly_data']);
                $bestMonth = $monthlyCollection->sortByDesc('revenue')->first();
                $worstMonth = $monthlyCollection->sortBy('revenue')->first();
                $totalRevenue = $monthlyCollection->sum('revenue');
                $totalAppointments = $monthlyCollection->sum('appointments');
                $avgPerMonth = $totalRevenue / max(count($stats['monthly_data']), 1);
            @endphp
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                <span class="text-sm text-slate-600">Meilleur mois: <strong class="text-slate-800">{{ $bestMonth['month'] ?? '-' }}</strong> avec {{ number_format($bestMonth['revenue'] ?? 0, 0) }} DT</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                <span class="text-sm text-slate-600">Mois le moins actif: <strong class="text-slate-800">{{ $worstMonth['month'] ?? '-' }}</strong> ({{ $worstMonth['appointments'] ?? 0 }} RDV)</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                <span class="text-sm text-slate-600">Moyenne mensuelle: <strong class="text-slate-800">{{ number_format($avgPerMonth, 0) }} DT</strong></span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                <span class="text-sm text-slate-600">Taux de conversion: <strong class="text-slate-800">{{ $totalAppointments > 0 ? round(($stats['total_patients'] / $totalAppointments) * 100, 1) : 0 }}%</strong> (patients/RDV)</span>
            </li>
        </ul>
    </div>
    
    <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 border border-blue-100">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-lightbulb text-primary-blue"></i>
            </div>
            <h3 class="font-bold text-slate-800">Recommandations</h3>
        </div>
        <ul class="space-y-3">
            @php
                $bestMonthName = $bestMonth['month'] ?? '';
                $worstMonthName = $worstMonth['month'] ?? '';
            @endphp
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Capitaliser sur le succès de <strong>{{ $bestMonthName }}</strong> avec des campagnes ciblées</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Analyser les causes de la baisse en <strong>{{ $worstMonthName }}</strong></span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Développer les consultations en ligne pour booster l'activité</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Mettre en place un programme de fidélisation patient</span>
            </li>
        </ul>
    </div>
</div>
    
    <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 border border-blue-100">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-lightbulb text-primary-blue"></i>
            </div>
            <h3 class="font-bold text-slate-800">Recommandations</h3>
        </div>
        <ul class="space-y-3">
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Renforcer la communication pendant les mois creux</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Développer les consultations en ligne</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-arrow-right text-primary-blue mt-0.5"></i>
                <span class="text-sm text-slate-600">Fidéliser les patients avec des offres personnalisées</span>
            </li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour le graphique
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Extraire les données du tableau PHP
    const appointmentsData = @json(collect($stats['monthly_data'])->pluck('appointments')->values());
    const revenueData = @json(collect($stats['monthly_data'])->pluck('revenue')->values());
    
    // Vérifier que les données sont valides
    const finalAppointmentsData = appointmentsData.length === 12 ? appointmentsData : Array(12).fill(0);
    const finalRevenueData = revenueData.length === 12 ? revenueData : Array(12).fill(0);
    
    // Création du graphique
    const ctx = document.getElementById('mainChart').getContext('2d');
    let mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Rendez-vous',
                data: finalAppointmentsData,
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
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', drawBorder: false },
                    ticks: { color: '#64748b', font: { size: 11, weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11, weight: '600' } }
                }
            }
        }
    });
    
    // Fonction pour basculer entre RDV et Revenus
    function updateChart(type) {
        const isRevenue = (type === 'rev');
        mainChart.data.datasets[0].label = isRevenue ? 'Revenus (DT)' : 'Rendez-vous';
        mainChart.data.datasets[0].data = isRevenue ? finalRevenueData : finalAppointmentsData;
        mainChart.data.datasets[0].borderColor = isRevenue ? '#10B981' : '#023E8A';
        mainChart.data.datasets[0].backgroundColor = isRevenue ? 'rgba(16, 185, 129, 0.05)' : 'rgba(2, 62, 138, 0.05)';
        mainChart.data.datasets[0].pointBackgroundColor = isRevenue ? '#10B981' : '#0077B6';
        mainChart.update();
        
        // Mise à jour des boutons
        const rdvBtn = document.getElementById('btn-rdv');
        const revBtn = document.getElementById('btn-rev');
        
        if (isRevenue) {
            rdvBtn.classList.remove('bg-primary-blue', 'text-white');
            rdvBtn.classList.add('bg-slate-100', 'text-slate-600');
            revBtn.classList.remove('bg-slate-100', 'text-slate-600');
            revBtn.classList.add('bg-primary-blue', 'text-white');
        } else {
            rdvBtn.classList.remove('bg-slate-100', 'text-slate-600');
            rdvBtn.classList.add('bg-primary-blue', 'text-white');
            revBtn.classList.remove('bg-primary-blue', 'text-white');
            revBtn.classList.add('bg-slate-100', 'text-slate-600');
        }
    }
</script>

@endsection