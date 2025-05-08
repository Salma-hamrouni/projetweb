@extends('layouts.app')

@section('styles')
<style>
.chart-container {
    width: 200px;
    height: 200px;
    margin: auto;
}
</style>
@endsection

@section('content')
<div class="container">
    <h1>📈 Suivi des progressions</h1>

    @forelse($progressions as $index => $item)
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">{{ $item['objectif']->title }}</h5>
                <p class="card-text">{{ $item['objectif']->description }}</p>
                <p class="card-text">Progression : {{ $item['progression'] }}%</p>

                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: {{ $item['progression'] }}%;" aria-valuenow="{{ $item['progression'] }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $item['progression'] }}%
                    </div>
                </div>

                <div class="chart-container text-center mb-3">
                    <canvas id="progressChart-{{ $index }}"></canvas>
                </div>
            </div>
        </div>
    @empty
        <p>Aucun objectif trouvé.</p>
    @endforelse
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = @json($progressions);

    data.forEach((item, index) => {
        const ctx = document.getElementById('progressChart-' + index).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Terminé', 'Restant'],
                datasets: [{
                    data: [item.progression, 100 - item.progression],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection
