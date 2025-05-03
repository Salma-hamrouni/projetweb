<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;
use Illuminate\Support\Facades\Auth;

class ProgressionController extends Controller
{
    public function index()
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');  // Redirige si l'utilisateur n'est pas connecté
        }

        // Récupère l'utilisateur authentifié
        $user = Auth::user();
        
        // Récupère les objectifs de l'utilisateur authentifié
        $objectifs = Objectif::where('user_id', $user->id)->get();

        // Si aucun objectif n'est trouvé
        if ($objectifs->isEmpty()) {
            $chartData = []; // Tableau vide si pas d'objectifs
        } else {
            // Prépare les données pour le graphique
            $chartData = $objectifs->map(function ($objectif) {
                // Calcul de la progression en fonction du status
                $progress = 0;

                switch ($objectif->status) {
                    case 'termine':
                        $progress = 100;
                        break;
                    case 'en_cours':
                        $progress = 50;  // Exemple: tu peux ajuster cela selon la logique que tu souhaites
                        break;
                        
                }

                return [
                    'name' => $objectif->title,  // Le titre de l'objectif
                    'progress' => $progress,     // La progression calculée
                ];
            })->toArray(); // Convertit la Collection en tableau
        }

        // Retourne la vue avec les données de progression
        return view('progressions.index', compact('chartData'));
    }
}
