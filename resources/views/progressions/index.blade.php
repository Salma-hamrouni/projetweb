@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Progression des Objectifs</h1>

    @if(empty($chartData))
        <p>Aucun objectif trouvé.</p>
    @else
        <div class="chart-container" style="position: relative; height:300px; width:100%">
            <canvas id="progressChart"></canvas>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('progressChart');
                if (!ctx) {
                    console.error("L'élément canvas #progressChart n'a pas été trouvé");
                    return;
                }

                const chartData = @json($chartData);  // Récupère les données PHP en JavaScript

                if (!chartData.length) {
                    ctx.closest('.chart-container').innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
                            <p>Aucune donnée disponible pour le graphique</p>
                        </div>`;
                    return;
                }

                try {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartData.map(item => item.name),
                            datasets: [{
                                label: 'Progression (%)',
                                data: chartData.map(item => item.progress),
                                backgroundColor: '#4caf50',
                                borderColor: '#ffffff',
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `Progression: ${ctx.raw}%`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: value => value + '%',
                                        stepSize: 20
                                    }
                                },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                } catch (error) {
                    console.error("Erreur lors de la création du graphique:", error);
                    ctx.closest('.chart-container').innerHTML = `
                        <div class="alert alert-danger">
                            Une erreur est survenue lors de l'affichage du graphique
                        </div>`;
                }
            });
        </script>
        @endpush
    @endif
</div>
@endsection
