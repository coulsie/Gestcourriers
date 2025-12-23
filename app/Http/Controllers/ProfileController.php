<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Met à jour les informations du profil dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // 1. Récupérer l'utilisateur actuellement authentifié
        $user = Auth::user();

        // 2. Valider les données de la requête
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // La règle 'unique:users' doit ignorer l'ID de l'utilisateur actuel
            'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($user->id),],
            // Ajoutez ici d'autres champs personnalisés si nécessaire :
            // 'phone_number' => 'nullable|string|max:20',
        ]);

        // 3. Mettre à jour les attributs de l'utilisateur
        $user = auth()->user();

        if ($user) {
            $user->update($validatedData);
        }
        
       
        // 4. Rediriger l'utilisateur vers une autre page (généralement la page d'affichage du profil)
        // avec un message de succès flashé dans la session.
        return redirect()->route('profile.show')->with('status', 'Votre profil a été mis à jour avec succès !');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

     public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|in:user,admin',
        ]);

        // Logique de stockage de la photo si présente
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('agents_photos', 'public');
            $validated['document'] = $path;
        }

        // Création de l'entrée en base de données
        // User::create($validated);

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Le profil a été créé avec succès.');
    }


     public function create()
    {
        // On retourne la vue située dans resources/views/profil/create.blade.php
        return view('profile.create');
    }

}

