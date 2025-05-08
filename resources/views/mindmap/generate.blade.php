@extends('layouts.app')

@section('title', 'Mindmap pour ' . $objectif->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Mindmap: {{ $objectif->title }}</h2>
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger m-3">{{ session('error') }}</div>
                @endif

                <div class="card-body p-0">
                    <div id="mindmap-container" style="height: 600px; width: 100%;"></div>
                </div>
                
                <div class="card-footer bg-light">
                    <a href="{{ route('mindmap.search') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<style>
    #mindmap-container {
        border-radius: 0 0 0.25rem 0.25rem;
    }
    .vis-tooltip {
        border-radius: 4px;
        padding: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nodes = new vis.DataSet([
            { 
                id: 0, 
                label: @json($objectif->title), 
                shape: 'box', 
                color: '#6f42c1', 
                font: { color: 'white', size: 16 },
                margin: 10,
                shadow: true
            },
            @foreach($etapes as $etape)
                {
                    id: {{ $etape->id }},
                    label: @json($etape->titre),
                    shape: 'box',
                    color: '{{ $etape->completed ? '#28a745' : '#ffc107' }}',
                    font: { color: '{{ $etape->completed ? 'white' : 'black' }}' },
                    margin: 8,
                    shadow: true
                },
            @endforeach
        ]);

        const edges = new vis.DataSet([
            @foreach($etapes as $etape)
                { 
                    from: 0, 
                    to: {{ $etape->id }},
                    color: { color: '#6c757d', highlight: '#6f42c1' },
                    width: 2
                },
            @endforeach
        ]);

        const container = document.getElementById('mindmap-container');
        const data = { nodes: nodes, edges: edges };
        const options = {
            layout: { 
                hierarchical: { 
                    direction: 'UD',
                    nodeSpacing: 150,
                    levelSeparation: 100
                } 
            },
            physics: { 
                hierarchicalRepulsion: { 
                    nodeDistance: 200,
                    springLength: 150
                } 
            },
            nodes: {
                borderWidth: 1,
                borderWidthSelected: 2,
                shadow: true
            },
            edges: {
                smooth: {
                    type: 'cubicBezier',
                    forceDirection: 'vertical'
                }
            }
        };

        new vis.Network(container, data, options);
    });
</script>
@endsection
@endsection