<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;

class MapController extends Controller
{
    public function index()
    {
        // Récupérer tous les objectifs de l'utilisateur avec leurs coordonnées
        $objectifs = Objectif::all();

        // Passe les objectifs à la vue
        return view('map.index', compact('objectifs')); 
    }
}

