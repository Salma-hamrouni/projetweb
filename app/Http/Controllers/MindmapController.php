<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use Illuminate\Http\Request;

class MindmapController extends Controller
{
    // Affiche le formulaire de recherche
    public function searchForm()
    {
        return view('mindmap.search');
    }

    // Génère la mindmap
    public function generate(Request $request)
{
    // Validation de l'entrée
    $validated = $request->validate([
        'objectif_name' => 'required|string|max:255',
    ]);

    $objectifName = $validated['objectif_name'];

    // Recherche de l'objectif avec ses étapes
    $objectif = Objectif::with('etapes')
        ->where('title', 'like', "%{$objectifName}%")
        ->first();

    // Vérifie si aucun objectif ou aucune étape trouvée
    if (!$objectif || $objectif->etapes->isEmpty()) {
        return back()->with('error', 'Aucun objectif ou étape trouvé pour ce nom.');
    }

    // Pas besoin de refaire un get ici, déjà chargé avec with()
    $etapes = $objectif->etapes;

    // Retourne la vue avec les données
    return view('mindmap.generate', compact('objectif', 'etapes', 'request'));

}

}
