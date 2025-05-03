<?php

namespace App\Http\Controllers\API;

use App\Models\Data;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataApiController extends Controller
{
    public function index()
    {
        // Récupérer toutes les données
        return response()->json(Data::all(), 200);
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx',  // Exemple pour un fichier
        ]);

        // Enregistrement du fichier
        $filePath = $request->file('file')->store('data');

        // Création de l'entrée Data
        $data = Data::create(['file_path' => $filePath]);

        // Création du journal
        $this->logJournal(auth()->id(), 'Ajout de fichier', 'Ajout d\'un fichier', 'Data', $data->id);

        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        // Suppression de la donnée
        $data = Data::findOrFail($id);
        \Storage::delete($data->file_path);
        $data->delete();

        // Création du journal
        $this->logJournal(auth()->id(), 'Suppression', 'Suppression d\'un fichier', 'Data', $data->id);

        return response()->json(['message' => 'Fichier supprimé avec succès!'], 200);
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
