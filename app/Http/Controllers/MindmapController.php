<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MindmapController extends Controller
{
    public function index()
    {
        // Cette méthode rend la vue de la mindmap (si elle est configurée)
        return view('mindmap.index');
    }

    public function ask(Request $request): JsonResponse
    {
        try {
            // On récupère le nom de l’objectif (et non plus l’ID)
            $objectifNom = $request->input('objectif_nom');

            if (!$objectifNom) {
                // Si le nom de l'objectif est manquant, renvoyer une erreur 400
                return response()->json(['error' => 'Nom de l\'objectif manquant'], 400);
            }

            // On recherche l’objectif par son titre
            $objectif = Objectif::with('etapes')->where('titre', $objectifNom)->first();

            if (!$objectif) {
                // Si l'objectif n'est pas trouvé, renvoyer une erreur 404
                return response()->json(['error' => 'Objectif non trouvé'], 404);
            }

            // On génère la mindmap à partir de l'objectif trouvé
            $mindmap = $this->genererMindmap($objectif);

            // On renvoie la mindmap au format JSON
            return response()->json(['mindmap' => $mindmap]);
        } catch (\Exception $e) {
            // En cas d'exception, on logge l'erreur et on renvoie une erreur générique
            Log::error('Erreur lors de la génération de la mindmap : ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite'], 500);
        }
    }

    private function rechercherObjectif(string $prompt): ?Objectif
    {
        // Recherche de l'objectif en fonction d'un terme (titre ou description)
        $objectif = Objectif::where('titre', 'like', "%$prompt%")
            ->orWhere('description', 'like', "%$prompt%")
            ->first();

        // Si l'objectif n'est pas trouvé, on cherche par chaque mot du prompt
        if (!$objectif) {
            foreach (preg_split('/\s+/', $prompt) as $term) {
                if (strlen($term) >= 3) {
                    $objectif = Objectif::where('titre', 'like', "%$term%")
                        ->orWhere('description', 'like', "%$term%")
                        ->first();
                    if ($objectif) return $objectif;
                }
            }
        }

        // Si aucune correspondance n'a été trouvée, on retourne le dernier objectif créé
        return $objectif ?? Objectif::latest()->first();
    }

    public function show($id)
    {
        // On récupère l'objectif avec ses étapes
        $goal = Objectif::with('etapes')->findOrFail($id);
        $steps = $goal->etapes;
    
        // Retourner la vue avec les données
        return view('mindmap.show', compact('goal', 'steps'));
    }
    
}
