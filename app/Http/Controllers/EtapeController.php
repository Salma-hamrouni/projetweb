<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Etape;
use App\Models\Objectif;
use Illuminate\Http\Request;

class EtapeController extends Controller
{
    public function index()
    {
        // Récupérer les objectifs de l'utilisateur connecté
        $objectifs = auth()->user()->objectifs ?? collect();
        
        // Récupérer les étapes associées à ces objectifs
        $etapes = Etape::whereIn('objectif_id', $objectifs->pluck('id'))->paginate(10);

        return view('etapes.index', [
            'etapes' => $etapes,
            'header' => 'Liste des étapes',
        ]);
    }

    public function create()
    {
        // Récupérer les objectifs de l'utilisateur authentifié
        $objectifs = Objectif::where('user_id', auth()->id())->get();

        // Retourner la vue avec les objectifs
        return view('etapes.create', compact('objectifs'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'titre' => 'required|string|max:255',
            'objectif_id' => 'required|exists:objectifs,id',
        ]);

        // Créer une nouvelle étape
        Etape::create([
            'user_id' => auth()->id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'objectif_id' => $request->objectif_id,
        ]);

        // Rediriger vers la liste des étapes
        return redirect()->route('etapes.index')->with('success', 'Étape ajoutée avec succès.');
    }

    public function show(Etape $etape)
    {
        return view('etapes.show', compact('etape'));
    }

    public function edit(Etape $etape)
    {
        // Vérification si l'utilisateur authentifié est le propriétaire de l'objectif
        if ($etape->objectif->user_id !== auth()->id()) {
            abort(403); // Si non, erreur 403
        }

        // Récupérer les objectifs de l'utilisateur
        $objectifs = Objectif::where('user_id', auth()->id())->get();

        // Retourner la vue d'édition
        return view('etapes.edit', compact('etape', 'objectifs'));
    }

    public function update(Request $request, Etape $etape)
    {
        // Validation des données de l'étape
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:en_cours,terminee,abandonnee',  // Correction du statut 'abandonnée' en 'abandonnee'
        ]);
    
        // Logique pour supprimer l'étape si le statut est "abandonnee" ou "terminee"
        if ($request->status === 'abandonnee') {
            $etape->delete();
            return redirect()->route('etapes.index')->with('success', 'L\'étape a été abandonnée et supprimée.');
        }
    
        // Sinon, mettre à jour l'étape
        $etape->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'status' => $request->status,
        ]);
    
        return redirect()->route('etapes.index')->with('success', 'L\'étape a été mise à jour.');
    }
    
    public function destroy(Etape $etape)
    {
        // Supprimer l'étape
        $etape->delete();
        return redirect()->route('etapes.index')->with('success', 'Étape supprimée avec succès.');
    }
}
