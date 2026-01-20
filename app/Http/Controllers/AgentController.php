<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\NotificationTache;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;   // <-- Ajout crucial pour DB
use Illuminate\Support\Facades\Log;  // <-- Ajout crucial pour Log
use Illuminate\Support\Facades\Hash; // <-- Pour Hash::make
use Illuminate\Validation\Rule;      // <-- Pour Rule::in
use PhpParser\Node\Expr\AssignOp\Plus;

class AgentController extends Controller
{
    /**
     * Affiche la liste de tous les agents.
     */
public function index(Request $request)
{
    // 1. Initialiser la requête avec les relations nécessaires pour éviter le problème N+1
    $query = Agent::with(['service', 'user']);

    // 2. Filtre Recherche (Nom, Prénom ou Matricule)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('last_name', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('matricule', 'LIKE', "%{$search}%");
        });
    }

    // 3. Filtre par Service
    if ($request->filled('service')) {
        $query->where('service_id', $request->service);
    }

    // 4. Filtre par État du Compte (Accès Système)
    if ($request->filled('account')) {
        if ($request->account === 'active') {
            // A un compte utilisateur lié
            $query->has('user');
        } elseif ($request->account === 'none') {
            // N'a pas de compte utilisateur
            $query->doesntHave('user');
        }
    }

    // 5. Pagination avec conservation des filtres (withQueryString)
    // C'est ici que l'erreur est corrigée en utilisant paginate() au lieu de get()
    $agents = $query->orderBy('last_name', 'asc')->paginate(15)->withQueryString();

    // 6. Récupérer les services pour le menu déroulant du filtre
    $services = \App\Models\Service::orderBy('name')->get();

    return view('agents.index', compact('agents', 'services'));
}





    /**
     * Affiche le formulaire de création d'un nouvel agent.
     */
    public function create(): View
    {
        // Nous avons besoin de tous les services et utilisateurs disponibles pour les listes déroulantes
        $services = Service::all(['id', 'name', 'code']);
        // Vous pouvez filtrer les utilisateurs qui ne sont pas déjà liés à un agent
        $users = User::doesntHave('agent')->get(['id', 'name', 'email']);

        return view('agents.create', compact('services', 'users'));
    }

    /**
     * Stocke un nouvel agent dans la base de données.
     */
public function store(Request $request)
{
    // 1. Validation
    $validated = $request->validate([
        'matricule' => 'required|string|max:191|unique:agents,matricule',
        'first_name' => 'required|string|max:191',
        'last_name' => 'required|string|max:191',
        'status' => 'required',
        'sexe' => 'nullable',
        'date_of_birth' => 'nullable|date',
        'place_birth' => 'nullable|string|max:191',
        'email_professionnel' => 'nullable|email|unique:agents,email_professionnel',
        'email' => 'nullable|email|unique:agents,email',
        'phone_number' => 'nullable|string|max:191',
        'address' => 'nullable|string|max:191',
        'Emploi' => 'nullable|string|max:191',
        'Grade' => 'nullable|string|max:191',
        'Date_Prise_de_service' => 'nullable|date',
        'Personne_a_prevenir' => 'nullable|string|max:191',
        'Contact_personne_a_prevenir' => 'nullable|string|max:191',
        'service_id' => 'required|exists:services,id',
        'user_id' => 'nullable|exists:users,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Gestion de la photo
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // On déplace le fichier dans public/agents_photos
        $file->move(public_path('agents_photos'), $fileName);

        // Crucial : On écrase l'objet UploadedFile par la chaîne de caractères du nom
        $validated['photo'] = $fileName;
    } else {
        // Si pas de photo, on s'assure que la valeur est nulle
        $validated['photo'] = null;
    }

    // 3. Création
    \App\Models\Agent::create($validated);

    return redirect()->route('agents.index')->with('success', 'Agent créé avec succès.');
}



/**
     * Affiche les détails d'un agent spécifique.
     */
    public function show(Agent $agent): View
    {
        // Charge les relations pour la vue détaillée (service, user)
        $agent->load(['service.direction', 'user']);

        return view('agents.show', compact('agent'));
    }

    /**
     * Affiche le formulaire d'édition d'un agent.
     */
    public function edit(Agent $agent): View
    {
        $services = Service::all(['id', 'name', 'code']);
        // Lors de l'édition, on inclut l'utilisateur courant de l'agent dans la liste des options
        $users = User::doesntHave('agent')->get(['id', 'name', 'email']);
        if ($agent->user) {
            $users->push($agent->user);
        }

        return view('agents.edit', compact('agent', 'services', 'users'));
    }

    /**
     * Met à jour l'agent spécifié dans la base de données.
     */
    public function update(Request $request, Agent $agent)
{
    // 1. Validation (on retire l'unique sur le matricule de l'agent actuel)
    $validated = $request->validate([
        'matricule' => 'required|string|max:191|unique:agents,matricule,' . $agent->id,
        'first_name' => 'required|string|max:191',
        'last_name' => 'required|string|max:191',
        'status' => 'required',
        'service_id' => 'required|exists:services,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'sexe' => 'nullable',
        'date_of_birth' => 'nullable|date',
        'place_birth' => 'nullable|string|max:191',
        'email_professionnel' => ['nullable','email',Rule::unique('agents','email_professionnel')->ignore($agent->id)],
        'email' => ['nullable','email',Rule::unique('agents','email')->ignore($agent->id)],
        'phone_number' => 'nullable|string|max:191',
        'address' => 'nullable|string|max:191',
        'Emploi' => 'nullable|string|max:191',
        'Grade' => 'nullable|string|max:191',
        'Date_Prise_de_service' => 'nullable|date',
        'Personne_a_prevenir' => 'nullable|string|max:191',
        'Contact_personne_a_prevenir' => 'nullable|string|max:191',
        'autres_champs' => 'nullable',
        'user_id' => 'nullable|exists:users,id',

        // Ajoutez vos autres champs ici...
    ]);

    // 2. Gestion de la photo
    if ($request->hasFile('photo')) {
        // --- ÉTAPE A : Supprimer l'ancienne photo du dossier public si elle existe ---
        if ($agent->photo && file_exists(public_path('agents_photos/' . $agent->photo))) {
            unlink(public_path('agents_photos/' . $agent->photo));
        }

        // --- ÉTAPE B : Stocker la nouvelle photo ---
        $file = $request->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('agents_photos'), $fileName);

        // --- ÉTAPE C : Mettre à jour la valeur dans le tableau validé ---
        $validated['photo'] = $fileName;
    } else {
        // Si aucune nouvelle photo n'est téléchargée, on garde l'ancienne
        // On retire 'photo' du tableau validé pour ne pas écraser avec du vide
        unset($validated['photo']);
    }

    // 3. Mise à jour de l'agent
    $agent->update($validated);

    return redirect()->route('agents.index')->with('success', 'Les informations de l\'agent ' . $agent->last_name . ' ' . $agent->first_name . ' ont été mises à jour avec succès.');
}

    /**
     * Supprime l'agent spécifié de la base de données.
     */
    public function destroy(Agent $agent): RedirectResponse
    {
        $agent->delete();

        // Si l'agent était responsable d'une direction ou d'un service (head_id),
        // ces champs seront mis à NULL grâce à onDelete('set null') dans les migrations.
        return redirect()->route('agents.index')->with('success', 'L\'agent a été supprimé.');
    }

        public function agent()
    {
        return $this->belongsTo(Agent::class); // Ou toute autre relation appropriée
    }

        public function dashb() {
            $notifications = NotificationTache::where('agent_id', auth::id())
                ->where('is_archived', false) // On filtre les archivées
                ->orderBy('date_creation', 'desc')
                ->take(10)
                ->get();

            return view('dashboard', compact('notifications'));
        }

 public function Enr(Request $request)
{
    // 1. VALIDATION
    $validatedData = $request->validate([
        // 'name' et 'password' retirés car générés automatiquement
        'email' => 'required|email|max:191|unique:users,email',
        'matricule' => 'required|string|unique:agents,matricule',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'service_id' => 'required|exists:services,id',
        'status' => 'required',
        'email_professionnel' => 'nullable|email',
        'sexe' => 'nullable',
        'date_of_birth' => 'nullable|date',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'phone_number' => 'nullable',
        'Emploi' => 'nullable',
        'Grade' => 'nullable',
        'Date_Prise_de_service' => 'nullable',
        'Personne_a_prevenir' => 'nullable',
        'Contact_personne_a_prevenir' => 'nullable',
        'address' => 'nullable',
        'place_birth' => 'nullable',
        'adresse' => 'nullable',
        'role' => 'required|exists:roles,name',
    ]);

    try {
        \Illuminate\Support\Facades\DB::beginTransaction();

        // 2. FORMATAGE DU NOM ET PRÉNOM
        // Nom en MAJUSCULES, Prénom avec 1ère lettre en Majuscule
        $lastNameUpper = strtoupper($request->last_name);
        $firstNameCap = ucwords(strtolower($request->first_name));
        $fullName = $firstNameCap . ' ' . $lastNameUpper;

        // 3. GESTION DE LA PHOTO


            // 2. Gestion de la photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // On déplace le fichier dans public/agents_photos
            $file->move(public_path('agents_photos'), $fileName);

            // Crucial : On écrase l'objet UploadedFile par la chaîne de caractères du nom
            $validated['photo'] = $fileName;
        } else {
            // Si pas de photo, on s'assure que la valeur est nulle
            $validated['photo'] = null;
        }

        // 4. CRÉATION DU COMPTE UTILISATEUR
        $user = \App\Models\User::create([
            'name' => $fullName, // Fusion Prénom + NOM
            'email' => $request->email, // Email professionnel comme Login
            'password' => \Illuminate\Support\Facades\Hash::make($request->matricule), // Matricule comme mot de passe
            'must_change_password' => true, // Recommandé pour forcer le changement

        ]);

        // Attribution du rôle Spatie
        $user->assignRole($request->role);

        // 5. CRÉATION DE L'AGENT
        $agent = \App\Models\Agent::create([
            'user_id' => $user->id,
            'email' => $request->email_personnel,
            'email_professionnel' => $request->email, // Utilise l'email de connexion
            'matricule' => $request->matricule,
            'first_name' => $firstNameCap,
            'last_name' => $lastNameUpper,
            'status' => $request->status,
            'sexe' => $request->sexe,
            'date_of_birth' => $request->date_of_birth,
            'photo' => $fileName,
            'phone_number' => $request->phone_number,
            'Emploi' => $request->Emploi,
            'Grade' => $request->Grade,
            'Date_Prise_de_service' => $request->Date_Prise_de_service,
            'service_id' => $request->service_id,
            'Personne_a_prevenir' => $request->Personne_a_prevenir,
            'Contact_personne_a_prevenir' => $request->Contact_personne_a_prevenir,
            'address' => $request->address,
            'place_birth' => $request->place_birth,
            'adresse' => $request->adresse,
        ]);

        \Illuminate\Support\Facades\DB::commit();

        return redirect()->route('agents.index')->with('success', "Compte créé pour $fullName. Login: {$request->email} | MDP: {$request->matricule}");

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();

        if ($fileName) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($fileName);
        }

        return back()->withInput()->with('error', "Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}


        public function nouveau() {
            $services = \App\Models\Service::all(); // Assurez-vous que le modèle Service existe
            return view('agents.nouveau', compact('services'));
        }
}
