<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Journal;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx',  // Exemple pour un fichier
            // Ajouter les autres validations nécessaires
        ]);

        // Enregistrement du fichier
        $filePath = $request->file('file')->store('data');

        // Création de l'entrée Data
        $data = Data::create(['file_path' => $filePath]);

        // Création du journal
        $this->logJournal(auth()->id(), 'Ajout de fichier', 'Ajout d\'un fichier', 'Data', $data->id);

        return redirect()->route('data.index')->with('success', 'Fichier ajouté avec succès!');
    }

    public function destroy($id)
    {
        // Suppression de la donnée
        $data = Data::findOrFail($id);
        \Storage::delete($data->file_path);
        $data->delete();

        // Création du journal
        $this->logJournal(auth()->id(), 'Suppression', 'Suppression d\'un fichier', 'Data', $data->id);

        return redirect()->route('data.index')->with('success', 'Fichier supprimé avec succès!');
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
