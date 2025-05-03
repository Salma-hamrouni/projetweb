@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/treant-js@1.0.1/Treant.css">
    <style>
        #tree-simple {
            width: 100%;
            height: auto;
        }
        .Treant > .node {
            padding: 4px;
            border-radius: 10px;
        }
        .Treant > .node .node-content {
            border: 1px solid #ccc;
            padding: 6px 12px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary">🧠 Mindmap de vos Objectifs</h1>
    <div id="tree-simple"></div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/raphael@2.3.0/raphael.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/treant-js@1.0.1/Treant.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const objectifs = @json($objectifs);

            const treeData = {
                chart: {
                    container: "#tree-simple",
                    connectors: { type: 'step' },
                    node: {
                        collapsable: true
                    }
                },
                nodeStructure: {
                    text: { name: "🎯 Objectifs" },
                    children: objectifs.map(obj => ({
                        text: { name: obj.titre },
                        children: (obj.etapes || []).map(etape => ({
                            text: { name: etape.titre }
                        }))
                    }))
                }
            };

            new Treant(treeData);
        });
    </script>
@endsection
