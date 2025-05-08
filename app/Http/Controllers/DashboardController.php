<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;
use App\Models\Etape;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter pour accéder à votre tableau de bord.');
        }
    
        $objectifsCount = $user->objectifs()->count();
        $etapesCount = $user->etapes()->count();
    
        $objectifs = $user->objectifs; 
        if ($objectifs->isEmpty()) {
            $averageProgression = 0; 
        } else {
            $totalProgression = 0;

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

        $objectifsPartagesAvecMoi = $user->objectifsPartagesAvecMoi; // Objectifs partagés avec l'utilisateur

        return view('dashboard', compact('objectifsCount', 'averageProgression', 'etapesCount', 'objectifsPartagesAvecMoi'));
    }
    public function showSharedObjectifs()
    {
        $user = auth()->user();
    
        $objectifsPartagesAvecMoi = $user->objectifsPartagesAvecMoi;
    
        if (!$objectifsPartagesAvecMoi || $objectifsPartagesAvecMoi->isEmpty()) {
            return view('share.objectifs_partages', ['objectifsPartagesAvecMoi' => collect()]);
        }
    
        return view('share.objectifs_partages', compact('objectifsPartagesAvecMoi'));
    }
    
}
