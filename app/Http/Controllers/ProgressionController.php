<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;

class ProgressionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Charger les objectifs de l'utilisateur avec leurs étapes
        $objectifs = $user->objectifs()->with('etapes')->get();

        // Préparer la liste des progressions
        $progressions = $objectifs->map(function ($objectif) {
            return [
                'objectif' => $objectif,
                'progression' => $objectif->progression, // propriété dynamique dans le modèle
            ];
        });

        return view('progressions.index', compact('progressions'));
    }
    public function calculateProgression(Objectif $objectif)
    {
        // Calcul de la progression de l'objectif
        $totalEtapes = $objectif->etapes->count();
        $etapesTerminees = $objectif->etapes->where('termine', true)->count();

        // Calcul du pourcentage
        $progression = $totalEtapes > 0 ? round(($etapesTerminees / $totalEtapes) * 100) : 0;

        // Définir la classe de fond en fonction de la progression
        $bgClass = $progression == 100 ? 'bg-completed' :
                  ($progression >= 80 ? 'bg-boost' :
                  ($progression >= 50 ? 'bg-growth' : 'bg-start'));

        // Retourne les données de progression et de classe de fond
        return compact('progression', 'bgClass');
    }
}
