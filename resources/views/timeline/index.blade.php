@extends('layouts.app')

@push('styles')
<style>
#particles-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.timeline {
    position: relative;
    padding-left: 40px;
    margin-left: 30px;
    border-left: 2px dashed #0d6efd;
}

.timeline-item {
    position: relative;
    margin-bottom: 40px;
    padding-bottom: 40px;
    opacity: 0;
    animation: fadeInUp 0.8s ease-out forwards;
}

.timeline-item:nth-child(odd) {
    animation-delay: 0.3s;
}

.timeline-item:nth-child(even) {
    animation-delay: 0.5s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.timeline-icon {
    position: absolute;
    left: -42px;
    top: 0;
    width: 24px;
    height: 24px;
    background-color: #0d6efd;
    border-radius: 50%;
    z-index: 1;
}

.timeline-content {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 2px solid #0d6efd;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.timeline-content::before {
    content: "";
    position: absolute;
    top: var(--y, 0);
    left: var(--x, 0);
    width: 0;
    height: 0;
    pointer-events: none;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 60%);
    transform: translate(-50%, -50%);
    transition: all 0.15s ease-out;
}

.timeline-item::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: #ddd;
    opacity: 1;
}

.timeline-item:last-child::after {
    display: none;
}

/* Classes de progression */
.bg-completed {
    background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
}

.bg-boost {
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
}

.bg-growth {
    background: linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%);
}

.bg-start {
    background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
}

/* Responsiveness */
@media (max-width: 768px) {
    .timeline {
        padding-left: 20px;
        margin-left: 10px;
    }

    .timeline-item {
        margin-bottom: 20px;
    }

    .timeline-content {
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .timeline {
        margin-left: 10px;
    }

    .timeline-item {
        margin-bottom: 15px;
    }

    .timeline-content {
        padding: 10px;
    }
}
</style>
@endpush

@section('content')
<div id="particles-bg"></div>
<div class="container py-5">
    <h1 class="mb-4 text-center text-primary fw-bold">Timeline des Objectifs</h1>
    <div class="timeline">
        @foreach ($objectifs as $objectif)
            <div class="timeline-item fade-in">
                <div class="timeline-icon"></div>
                <div class="timeline-content {{ $objectif->bgClass }}">
                    <h3 class="fw-bold">{{ $objectif->titre }}</h3>
                    <p class="text-muted">{{ $objectif->description }}</p>
                    <p><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($objectif->created_at)->format('d/m/Y') }}</p>
                    <p><strong>Deadline :</strong> {{ \Carbon\Carbon::parse($objectif->deadline)->format('d/m/Y') }}</p>
                    <p><strong>Progression :</strong> {{ $objectif->progression }}%</p>
                    <a href="{{ route('objectifs.show', $objectif->id) }}" class="btn btn-light mt-2">Voir les étapes</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const items = document.querySelectorAll(".timeline-item");
        items.forEach((item, i) => {
            item.style.animationDelay = `${i * 0.3}s`;
        });

        document.querySelectorAll('.timeline-content').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                card.style.setProperty('--x', `${e.clientX - rect.left}px`);
                card.style.setProperty('--y', `${e.clientY - rect.top}px`);
            });
        });

        tsParticles.load("particles-bg", {
            fullScreen: false,
            background: { color: "#f8f9fa" },
            particles: {
                number: { value: 30 },
                size: { value: 2 },
                move: { enable: true, speed: 1 },
                links: { enable: true, color: "#0d6efd" }
            }
        });
    });
</script>
@endpush

