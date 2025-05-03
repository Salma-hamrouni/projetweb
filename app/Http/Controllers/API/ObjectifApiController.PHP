<?php

namespace App\Http\Controllers\API;

use App\Models\Objectif;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObjectifApiController extends Controller
{
    public function index()
    {
        // Récupérer tous les objectifs
        return response()->json(Objectif::all(), 200);
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Création de l'objectif
        $objectif = Objectif::create($request->all());

        // Création du journal
        $this->logJournal(auth()->id(), 'Création', 'Création d\'un nouvel objectif', 'Objectif', $objectif->id);

        return response()->json($objectif, 201);
    }

    public function show($id)
    {
        // Récupérer un objectif spécifique
        $objectif = Objectif::findOrFail($id);
        return response()->json($objectif, 200);
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Mise à jour de l'objectif
        $objectif = Objectif::findOrFail($id);
        $objectif->update($request->all());

        // Création du journal
        $this->logJournal(auth()->id(), 'Modification', 'Modification d\'un objectif', 'Objectif', $objectif->id);

        return response()->json($objectif, 200);
    }

    public function destroy($id)
    {
        // Suppression de l'objectif
        $objectif = Objectif::findOrFail($id);
        $objectif->delete();

        // Création du journal
        $this->logJournal(auth()->id(), 'Suppression', 'Suppression d\'un objectif', 'Objectif', $objectif->id);

        return response()->json(['message' => 'Objectif supprimé avec succès!'], 200);
    }

    // Fonction de log du journal
    protected function logJournal($userId, $action, $description, $objectType, $objectId)
    {
        Journal::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'object_type' => $objectType,
            'object_id' => $objectId,
        ]);
    }
}
