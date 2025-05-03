<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObjectifController extends Controller
{
    public function index()
    {
        $objectifs = Objectif::with(['user', 'progressions'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = (object) [
            'total' => $objectifs->total(),
            'completed' => $objectifs->where('status', 'termine')->count()
        ];

        return view('objectifs.index', compact('objectifs', 'stats'));
    }

    public function create()
    {
        return view('objectifs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['completed_at'] = $validated['status'] === 'termine' ? now() : null;

        DB::transaction(function () use ($validated) {
            $objectif = Objectif::create($validated);

            if ($validated['status'] === 'abandonne') {
                $this->logJournal('Suppression', "Objectif abandonné: {$objectif->title}", $objectif->id);
                $objectif->delete();
            } else {
                $this->logJournal('Création', "Nouvel objectif: {$objectif->title}", $objectif->id);
            }
        });

        return redirect()->route('objectifs.index')->with('success', 'Objectif créé avec succès!');
    }

    public function update(Request $request, Objectif $objectif)
    {
        if ($objectif->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:objectifs,title,' . $objectif->id . ',id,user_id,' . auth()->id(),
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Si le statut devient "abandonne", on supprime l'objectif
        if ($validated['status'] === 'abandonne') {
            $this->logJournal('Suppression', "Objectif abandonné: {$objectif->title}", $objectif->id);
            $objectif->delete();
            return redirect()->route('objectifs.index')->with('success', 'Objectif abandonné et supprimé.');
        }

        DB::transaction(function () use ($objectif, $validated) {
            $original = $objectif->getOriginal();

            if ($validated['status'] === 'termine' && !$objectif->completed_at) {
                $validated['completed_at'] = now();
            } elseif ($validated['status'] !== 'termine') {
                $validated['completed_at'] = null;
            }

            $objectif->update($validated);

            $this->logChanges($original, $objectif);
        });

        return redirect()->route('objectifs.index')->with('success', 'Objectif mis à jour!');
    }

    public function destroy(Objectif $objectif)
    {
        if ($objectif->user_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($objectif) {
            if ($objectif->progressions()->exists()) {
                abort(400, 'Impossible de supprimer: des progressions sont associées!');
            }

            $this->logJournal('Suppression', "Objectif supprimé: {$objectif->title}", $objectif->id);
            $objectif->delete();
        });

        return redirect()->route('objectifs.index')->with('success', 'Objectif supprimé!');
    }

    public function show($id)
    {
        $objectif = Objectif::with(['etapes', 'progressions'])->findOrFail($id);

        if ($objectif->user_id !== auth()->id()) {
            abort(403);
        }

        return view('objectifs.show', compact('objectif'));
    }

    public function edit($id)
    {
        $objectif = Objectif::findOrFail($id);

        if ($objectif->user_id !== auth()->id()) {
            abort(403);
        }

        return view('objectifs.edit', compact('objectif'));
    }

    public function apiIndex()
    {
        return Objectif::where('user_id', auth()->id())
            ->select(['id', 'title', 'status', 'created_at'])
            ->withCount('progressions')
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->get()
            ->makeHidden(['description']);
    }

    protected function logChanges(array $original, Objectif $objectif): void
    {
        $changes = [];
        foreach ($original as $key => $value) {
            if ($objectif->wasChanged($key)) {
                $changes[] = "$key: $value → {$objectif->$key}";
            }
        }

        if (!empty($changes)) {
            $this->logJournal(
                'Modification',
                "Modification de l'objectif {$objectif->title}: " . implode(', ', $changes),
                $objectif->id
            );
        }
    }

    protected function logJournal(string $action, string $description, int $objectId): void
    {
        Journal::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'object_type' => 'Objectif',
            'object_id' => $objectId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
