<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Affiche le profil de l'utilisateur
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }
    
    // Affiche les informations du profil
public function show()
{
    $user = Auth::user();
    return view('profile.index', compact('user')); // Vous pouvez utiliser la vue existante pour afficher le profil
}


    // Affiche le formulaire d'édition du profil
    public function edit()
    {
        return view('profile.edit');
    }

    // Met à jour les informations du profil (y compris la photo)
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation de la photo
        ]);

        // Mise à jour des informations utilisateur (nom, email, mot de passe)
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Gestion de la photo de profil
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo && file_exists(storage_path('app/public/' . $user->photo))) {
                unlink(storage_path('app/public/' . $user->photo));
            }

            // Enregistrer la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        // Sauvegarder les changements dans la base de données
        $user->save();

        // Rediriger vers la page de profil avec un message de succès
        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès!');
    }
    public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|max:2048',
    ]);

    $image = $request->file('photo');
    $imageName = time() . '.' . $image->getClientOriginalExtension();

    // Redimensionner l'image avant de la sauvegarder
    $imagePath = public_path('storage/photos/' . $imageName);
    Image::make($image)->resize(150, 150)->save($imagePath); // Taille plus petite, par exemple 150x150px

    // Enregistrer le chemin dans la base de données
    auth()->user()->update(['photo' => 'photos/' . $imageName]);

    return redirect()->back()->with('success', 'Photo mise à jour avec succès.');
}
}
