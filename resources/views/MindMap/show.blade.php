@extends('layouts.app')

@section('title', 'Mindmap for ' . $goal->titre)

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css">
<style>
    #mindmap-container {
        height: 600px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin: 2rem 0;
    }
    
    .mindmap-actions {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mindmap for: {{ $goal->titre }}</h1>
        <div>
            <a href="{{ route('goals.steps', $goal) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Steps
            </a>
            <button class="btn btn-outline-primary ms-2" id="download-mindmap">
                <i class="bi bi-download"></i> Download
            </button>
        </div>
    </div>

    <div id="mindmap-container"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create nodes and edges for the mindmap
        const nodes = new vis.DataSet([ 
            // Main goal node
            { 
                id: 0, 
                label: "{{ $goal->titre }}", 
                shape: 'box', 
                color: '#6f42c1',
                font: { color: 'white', size: 16 },
                margin: 10
            },

            // Add steps as child nodes
            @foreach($steps as $step)
            { 
                id: {{ $step->id }}, 
                label: "{{ $step->titre }}", 
                shape: 'box',
                color: '{{ $step->status == 'completed' ? '#28a745' : '#ffc107' }}',
                font: { color: '{{ $step->status == 'completed' ? 'white' : '#212529' }}' },
                margin: 8
            },
            @endforeach
        ]);

        const edges = new vis.DataSet([
            // Connect steps to main goal
            @foreach($steps as $step)
            { from: 0, to: {{ $step->id }} },
            @endforeach
        ]);

        // Create the network
        const container = document.getElementById('mindmap-container');
        const data = {
            nodes: nodes,
            edges: edges
        };
        
        const options = {
            layout: {
                hierarchical: {
                    direction: 'UD', // Up-Down layout
                    sortMethod: 'directed',
                    nodeSpacing: 150,
                    levelSeparation: 100
                }
            },
            physics: {
                hierarchicalRepulsion: {
                    nodeDistance: 200
                }
            },
            nodes: {
                borderWidth: 2,
                shadow: true
            },
            edges: {
                smooth: true,
                width: 2,
                shadow: true
            }
        };

        const network = new vis.Network(container, data, options);

        // Download mindmap as image
        document.getElementById('download-mindmap').addEventListener('click', function() {
            html2canvas(container).then(canvas => {
                const link = document.createElement('a');
                link.download = 'mindmap-{{ Str::slug($goal->titre) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });
    });
</script>
@endsection
