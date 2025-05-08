<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use App\Models\Objectif;
use Illuminate\Http\Request;

class EtapeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $objectifs = auth()->user()->objectifs ?? collect();
        $etapes = Etape::whereIn('objectif_id', $objectifs->pluck('id'))->paginate(10);

        return view('etapes.index', [
            'etapes' => $etapes,
            'header' => 'Liste des étapes',
        ]);
    }

    public function create()
    {
        $objectifs = Objectif::where('user_id', auth()->id())->pluck('title', 'id');
        return view('etapes.create', compact('objectifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:en_cours,termine,abandonne',
            'objectif_id' => 'required|exists:objectifs,id',
        ]);

        $objectif = Objectif::findOrFail($request->objectif_id);
        $this->authorize('update', $objectif);

        Etape::create([
            'user_id' => auth()->id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'status' => $request->status,
            'objectif_id' => $request->objectif_id,
        ]);

        return redirect()->route('etapes.index')->with('success', 'Étape ajoutée avec succès.');
    }

    public function show(Etape $etape)
    {
        $this->authorize('view', $etape->objectif);
        return view('etapes.show', compact('etape'));
    }

    public function edit(Etape $etape)
    {
        $this->authorize('update', $etape->objectif);
        $objectifs = Objectif::where('user_id', auth()->id())->pluck('title', 'id');

        return view('etapes.edit', compact('etape', 'objectifs'));
    }

    public function update(Request $request, Etape $etape)
    {
        $this->authorize('update', $etape->objectif);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:en_cours,termine,abandonne',
        ]);

        if ($request->status === 'abandonne') {
            $etape->delete();
            return redirect()->route('etapes.index')->with('success', 'Étape abandonnée et supprimée.');
        }

        $etape->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('etapes.index')->with('success', 'Étape mise à jour avec succès.');
    }

    public function destroy(Etape $etape)
    {
        $this->authorize('delete', $etape->objectif);
        $etape->delete();

        return redirect()->route('etapes.index')->with('success', 'Étape supprimée avec succès.');
    }
}
