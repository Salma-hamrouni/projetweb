<?php
namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Models\Etape;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ObjectifController extends Controller
{
    /**
     * Afficher la liste des objectifs de l'utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer tous les objectifs de l'utilisateur connecté
        $objectifs = Objectif::with('etapes')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        // Statistiques sur les objectifs
        $stats = (object) [
            'total' => $objectifs->total(),
            'completed' => $objectifs->getCollection()->filter(fn($o) => $o->status === 'termine')->count()
        ];

        // Retourner la vue avec la liste des objectifs et les statistiques
        return view('objectifs.index', compact('objectifs', 'stats'));
    }

    /**
     * Afficher le formulaire de création d'un objectif.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Récupérer les utilisateurs à qui l'objectif peut être partagé
        $users = User::where('id', '!=', auth()->id())->get();

        // Retourner la vue avec la liste des utilisateurs
        return view('objectifs.create', compact('users'));
    }

    /**
     * Enregistrer un nouvel objectif dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données envoyées par le formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90'?? null,
            'longitude' => 'nullable|numeric|between:-180,180'?? null,
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
            'shared_with_user_id' => 'nullable|exists:users,id',
            'etapes' => 'required|array|min:1',
            'etapes.*.titre' => 'required|string|max:255',
            'etapes.*.status' => 'required|in:en_cours,termine,abandonne',
            'etapes.*.description' => 'nullable|string',
        ]);

        // Début de la transaction pour assurer la consistance des données
        DB::beginTransaction();
        try {
            // Créer l'objectif
            $objectif = Objectif::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'lieu' => $validated['lieu'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'deadline' => $validated['deadline'],
                'user_id' => auth()->id(),
                'shared_with_user_id' => $validated['shared_with_user_id'],
                // Sauvegarder le fichier si présent
                'file_path' => $request->file('file') ? $request->file('file')->store('uploads') : null
            ]);

            // Ajouter les étapes à l'objectif
            foreach ($validated['etapes'] as $etapeData) {
                Etape::create([
                    'objectif_id' => $objectif->id,
                    'titre' => $etapeData['titre'],
                    'description' => $etapeData['description'] ?? null,
                    'status' => $etapeData['status']
                ]);
            }

            // Commit de la transaction si tout est réussi
            DB::commit();

            // Rediriger vers la liste des objectifs avec un message de succès
            return redirect()->route('objectifs.index')->with('success', 'Objectif créé avec succès.');
        } catch (\Exception $e) {
            // Rollback en cas d'erreur
            DB::rollBack();

            // Retourner à la page précédente avec un message d'erreur
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'objectif.']);
        }
    }

    /**
     * Afficher les détails d'un objectif spécifique.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Récupérer l'objectif avec ses étapes
        $objectif = Objectif::with('etapes')->findOrFail($id);

        // Vérifier si l'utilisateur est autorisé à voir cet objectif
        if ($objectif->user_id !== auth()->id()) {
            return redirect()->route('objectifs.index')->withErrors(['error' => 'Accès non autorisé.']);
        }

        // Retourner la vue avec les détails de l'objectif
        return view('objectifs.show', compact('objectif'));
    }

    /**
     * Afficher le formulaire d'édition d'un objectif.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Récupérer l'objectif à éditer
        $objectif = Objectif::with('etapes')->findOrFail($id);

        // Vérifier si l'utilisateur est autorisé à éditer cet objectif
        if ($objectif->user_id !== auth()->id()) {
            return redirect()->route('objectifs.index')->withErrors(['error' => 'Accès non autorisé.']);
        }

        // Récupérer les utilisateurs à qui l'objectif peut être partagé
        $users = User::where('id', '!=', auth()->id())->get();

        // Retourner la vue avec l'objectif à éditer et la liste des utilisateurs
        return view('objectifs.edit', compact('objectif', 'users'));
    }

    /**
     * Mettre à jour un objectif dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation des données envoyées par le formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_cours,termine,abandonne',
           
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
            'shared_with_user_id' => 'nullable|exists:users,id',
            'etapes' => 'required|array|min:1',
            'etapes.*.titre' => 'required|string|max:255',
            'etapes.*.status' => 'required|in:en_cours,termine,abandonne',
            'etapes.*.description' => 'nullable|string',
        ]);

        // Récupérer l'objectif à mettre à jour
        $objectif = Objectif::findOrFail($id);

        // Vérifier si l'utilisateur est autorisé à éditer cet objectif
        if ($objectif->user_id !== auth()->id()) {
            return redirect()->route('objectifs.index')->withErrors(['error' => 'Accès non autorisé.']);
        }

        // Début de la transaction pour assurer la consistance des données
        DB::beginTransaction();
        try {
            // Mettre à jour les informations de l'objectif
            $objectif->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'lieu' => $validated['lieu'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'deadline' => $validated['deadline'] ?? null,
                'shared_with_user_id' => $validated['shared_with_user_id'] ?? null,
                'file_path' => $request->file('file') ? $request->file('file')->store('uploads') : $objectif->file_path
            ]);
            
            // Supprimer les anciennes étapes
            $objectif->etapes()->delete();

            // Ajouter les nouvelles étapes
            foreach ($validated['etapes'] as $etapeData) {
                Etape::create([
                    'objectif_id' => $objectif->id,
                    'titre' => $etapeData['titre'],
                    'description' => $etapeData['description'] ?? null,
                    'status' => $etapeData['status']
                ]);
            }

            // Commit de la transaction si tout est réussi
            DB::commit();

            // Rediriger vers la liste des objectifs avec un message de succès
            return redirect()->route('objectifs.index')->with('success', 'Objectif mis à jour avec succès.');
        } catch (\Exception $e) {
            // Rollback en cas d'erreur
            DB::rollBack();

            // Retourner à la page précédente avec un message d'erreur
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de l\'objectif.']);
        }
    }

    /**
     * Supprimer un objectif de la base de données.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Récupérer l'objectif à supprimer
        $objectif = Objectif::findOrFail($id);

        // Vérifier si l'utilisateur est autorisé à supprimer cet objectif
        if ($objectif->user_id !== auth()->id()) {
            return redirect()->route('objectifs.index')->withErrors(['error' => 'Accès non autorisé.']);
        }

        // Supprimer l'objectif et ses étapes associées
        $objectif->delete();

        // Retourner à la liste des objectifs avec un message de succès
        return redirect()->route('objectifs.index')->with('success', 'Objectif supprimé avec succès.');
    }
}
