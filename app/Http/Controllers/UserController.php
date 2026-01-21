<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        // Seuls ceux qui ont la permission 'voir-utilisateurs' accèdent à la liste
        $this->middleware('can:voir-utilisateurs')->only('index');

        // Seuls ceux qui ont la permission 'manage-users' peuvent créer/modifier/supprimer
        $this->middleware('can:manage-users')->except(['index', 'show']);
    }

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
            'role' => 'required|exists:roles,name',
            'bio' => 'nullable|string',
        ]);

        // 2. Création de l'utilisateur dans la base de données
        // C'est ici que l'INSERT INTO se produit via l'ORM Eloquent
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hachage sécurisé du mot de passe
            'must_change_password' => true

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

    public function resetPassword(Request $request, $id)
    {
        // 1. Trouver l'utilisateur et charger sa relation agent
        $user = User::with('agent')->findOrFail($id);

        // 2. Vérification de l'existence de l'agent et du matricule
        if (!$user->agent || !$user->agent->matricule) {
            return back()->with('error', "Échec : Cet utilisateur n'a pas de matricule associé.");
        }

        // 3. Mise à jour manuelle (on ignore le système de token/broker)
        $user->password = Hash::make($user->agent->matricule);

        // On force l'utilisateur à changer ce mot de passe à sa prochaine connexion
        $user->must_change_password = true;

        $user->save();

        // 4. Retour avec un message de succès
        return back()->with('success', "Le mot de passe de {$user->name} a été réinitialisé. Nouveau MDP : {$user->agent->matricule}");
    }




        public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false, // On désactive le flag
        ]);

        return redirect()->route('home')->with('success', "Mot de passe mis à jour avec succès !");
    }

}
