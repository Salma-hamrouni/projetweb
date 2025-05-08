<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Models\Etape;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ObjectifController extends Controller
{
    public function index()
    {
        $objectifs = Objectif::with('etapes')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = (object) [
            'total' => $objectifs->total(),
            'completed' => $objectifs->getCollection()->filter(fn($o) => $o->status === 'termine')->count()
        ];

        return view('objectifs.index', compact('objectifs', 'stats'));
    }

    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('objectifs.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Validation des données envoyées
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deadline' => 'nullable|date|after_or_equal:today',
            'shared_with_user_id' => 'nullable|exists:users,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048', // Nouvelle règle pour le fichier
            'etapes' => 'required|array|min:1',
            'etapes.*.titre' => 'required|string|max:255',
            'etapes.*.description' => 'nullable|string',
            'etapes.*.status' => 'nullable|in:en_cours,termine,abandonne',
        ]);

        // Démarrer la transaction pour garantir la cohérence des données
        DB::transaction(function () use ($validated, $request) {  // Ajoutez $request ici
            // Gérer le téléchargement du fichier si nécessaire
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('uploads', 'public');
            }

            // Création de l'objectif
            $objectif = Objectif::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'lieu' => $validated['lieu'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'deadline' => $validated['deadline'],
                'shared_with_user_id' => $validated['shared_with_user_id'],
                'file_path' => $filePath, // Sauvegarde du chemin du fichier
                'completed_at' => $validated['status'] === 'termine' ? now() : null,
            ]);

            // Si l'objectif est abandonné, le supprimer immédiatement
            if ($validated['status'] === 'abandonne') {
                $this->logJournal('Abandon', "Objectif abandonné dès la création : {$validated['title']}", $objectif->id);
                $objectif->delete();
                return;
            }

            $this->logJournal('Création', "Nouvel objectif : {$validated['title']}", $objectif->id);

            // Création des étapes associées à l'objectif
            foreach ($validated['etapes'] as $etape) {
                $objectif->etapes()->create([
                    'titre' => $etape['titre'],
                    'description' => $etape['description'] ?? null,
                    'status' => $etape['status'] ?? 'en_cours',
                    'user_id' => auth()->id(),
                ]);
            }
        });

        // Redirection vers la liste des objectifs avec un message de succès
        return redirect()->route('objectifs.index')->with('success', 'Objectif créé avec succès !');
    }

    public function edit($id)
    {
        $objectif = Objectif::with('etapes')->findOrFail($id);
        $this->authorizeUser($objectif);
        $users = User::where('id', '!=', auth()->id())->get();
        return view('objectifs.edit', compact('objectif', 'users'));
    }

    public function update(Request $request, $id)
    {
        $objectif = Objectif::with('etapes')->findOrFail($id);
        $this->authorizeUser($objectif);
        $objectif->shared_with_user_id = $request->shared_with_user_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deadline' => 'nullable|date|after_or_equal:today',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048', // Validation du fichier
            'etapes' => 'required|array|min:1',
            'etapes.*.id' => 'nullable|exists:etapes,id',
            'etapes.*.titre' => 'required|string|max:255',
            'etapes.*.description' => 'nullable|string',
            'etapes.*.status' => 'nullable|in:en_cours,termine,abandonne',
        ]);

        DB::transaction(function () use ($objectif, $validated, $request) {
            // Gérer le téléchargement du fichier si nécessaire
            $filePath = $objectif->file_path; // Par défaut, conserver le chemin actuel
            if ($request->hasFile('file')) {
                // Si un nouveau fichier est téléchargé, remplacer le précédent
                $filePath = $request->file('file')->store('uploads', 'public');
            }

            $validated['completed_at'] = $validated['status'] === 'termine' ? now() : null;
            $validated['file_path'] = $filePath; // Mettre à jour le chemin du fichier

            // Mise à jour de l'objectif
            $objectif->update($validated);

            // Gérer les étapes soumises
            $submittedEtapes = collect($validated['etapes']);
            $submittedIds = $submittedEtapes->pluck('id')->filter()->toArray();

            // Supprimer les étapes non soumises
            $objectif->etapes()->whereNotIn('id', $submittedIds)->delete();

            // Mettre à jour ou créer les étapes
            foreach ($submittedEtapes as $etapeData) {
                if (!empty($etapeData['id'])) {
                    $etape = Etape::find($etapeData['id']);
                    if ($etape && $etape->objectif_id === $objectif->id) {
                        $etape->update([
                            'titre' => $etapeData['titre'],
                            'description' => $etapeData['description'] ?? null,
                            'status' => $etapeData['status'] ?? 'en_cours',
                        ]);
                    }
                } else {
                    $objectif->etapes()->create([
                        'titre' => $etapeData['titre'],
                        'description' => $etapeData['description'] ?? null,
                        'status' => $etapeData['status'] ?? 'en_cours',
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            $this->logJournal('Mise à jour', "Objectif mis à jour : {$objectif->title}", $objectif->id);
        });

        return redirect()->route('objectifs.index')->with('success', 'Objectif mis à jour avec succès.');
    }

    public function destroy(Objectif $objectif)
    {
        $this->authorizeUser($objectif);

        DB::transaction(function () use ($objectif) {
            $this->logJournal('Suppression', "Objectif supprimé : {$objectif->title}", $objectif->id);
            $objectif->delete();
        });

        return redirect()->route('objectifs.index')->with('success', 'Objectif supprimé.');
    }

    public function show($id)
    {
        $objectif = Objectif::with('etapes')->findOrFail($id);
        $this->authorizeUser($objectif);

        return view('objectifs.show', compact('objectif'));
    }

    public function progressions($id)
    {
        $objectif = Objectif::with('etapes')->findOrFail($id);
        $this->authorizeUser($objectif);

        $progression = $this->calculateObjectifProgression($objectif);

        return view('progressions.index', compact('objectif', 'progression'));
    }

    public function apiIndex()
    {
        return Objectif::where('user_id', auth()->id())
            ->select(['id', 'title', 'status', 'created_at'])
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->get();
    }

    private function calculateObjectifProgression(Objectif $objectif): float
    {
        $total = $objectif->etapes->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $objectif->etapes->where('status', 'termine')->count();

        return round(($completed / $total) * 100, 2);
    }

    protected function logJournal(string $action, string $description, ?int $objectifId = null): void
    {
        Journal::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'objectif_id' => $objectifId,
        ]);
    }

    protected function authorizeUser(Objectif $objectif): void
    {
        if ($objectif->user_id !== auth()->id()) {
            abort(403, 'Accès refusé.');
        }
    }

    public function objectifsPartages()
    {
        $sharedObjectifs = auth()->user()->objectifsPartagesAvecMoi;
        return view('share.objectifs_partages', compact('sharedObjectifs'));
    }
}
