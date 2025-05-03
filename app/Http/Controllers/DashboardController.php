<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;
use App\Models\Etape;

class DashboardController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur est authentifié
        $user = auth()->user();
    
        // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter pour accéder à votre tableau de bord.');
        }
    
        // Récupérer les comptages des objectifs, progressions et étapes pour l'utilisateur authentifié
        $objectifsCount = $user->objectifs()->count();
        $etapesCount = $user->etapes()->count();
    
        // Calculer la moyenne de progression des objectifs
        $objectifs = $user->objectifs; // Récupérer les objectifs de l'utilisateur
        if ($objectifs->isEmpty()) {
            $averageProgression = 0; // Si aucun objectif, la moyenne est 0
        } else {
            $totalProgression = 0;

            // Calculer la progression de chaque objectif en fonction de son status
            foreach ($objectifs as $objectif) {
                switch ($objectif->status) {
                    case 'terminee':
                        $totalProgression += 100; // Objectif terminé
                        break;
                    case 'en_cours':
                        $totalProgression += 50; // Objectif en cours
                        break;
                    case 'abandonee':
                        $totalProgression += 0; // Objectif abandonné
                        break;
                    default:
                        $totalProgression += 0; // Si status inconnu, on prend 0%
                        break;
                }
            }

            // Moyenne de la progression
            $averageProgression = $totalProgression / $objectifsCount;
        }
    
        // Passer les données à la vue
        return view('dashboard', compact('objectifsCount', 'averageProgression', 'etapesCount'));
    }
}
