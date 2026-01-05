<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    /**
     * Affiche une liste de tous les utilisateurs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupère tous les utilisateurs de la base de données
       $users = User::paginate(15);

        // Retourne la vue index.blade.php en lui passant les utilisateurs
        return view('Users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retourne la vue create.blade.php (le formulaire vide)
        return view('Users.create');
    }

    /**
     * Stocke un nouvel utilisateur dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email unique dans la table 'users'
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',  // 'confirmed' role'
            'bio' => 'nullable|string',
        ]);

        // 2. Création de l'utilisateur dans la base de données
        // C'est ici que l'INSERT INTO se produit via l'ORM Eloquent
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hachage sécurisé du mot de passe
            'role'=> $validatedData['role'],
        ]);

        // 3. Redirection vers la liste des utilisateurs avec un message de succès
        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Affiche les détails d'un utilisateur spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id); // Trouve l'utilisateur ou génère une erreur 404
        return view('Users.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition pour un utilisateur spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('Users.edit', compact('user'));
    }

    /**
     * Met à jour l'utilisateur spécifié dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Règle d'unicité qui ignore l'ID de l'utilisateur actuel lors de la vérification
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'=> 'required|string',
        ]);

        // Mise à jour de l'instance du modèle
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * Supprime l'utilisateur spécifié de la base de données.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }
}
