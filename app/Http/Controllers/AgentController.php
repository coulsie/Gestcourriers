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
    public function index(): View
    {
        // Récupère tous les agents avec leurs relations (service, user) pour optimiser les requêtes
        $agents = Agent::with(['service', 'user'])->get();

        return view('agents.index', compact('agents'));
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
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:191|unique:users,email',
        'password' => 'required|string|min:3|confirmed',
        'role' => 'required',
        'matricule' => 'required|string|unique:agents,matricule',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'service_id' => 'required|exists:services,id',
        'status' => 'required',
        'email_professionnel' => 'nullable',
        'sexe' => 'nullable',
        'date_of_birth' => 'nullable',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation stricte de l'image
        'phone_number' => 'nullable',
        'Emploi' => 'nullable',
        'Grade' => 'nullable',
        'Date_Prise_de_service' => 'nullable',
        'Personne_a_prevenir' => 'nullable',
        'Contact_personne_a_prevenir' => 'nullable',
        'address' => 'nullable',
        'place_birth' => 'nullable',
        'adresse' => 'nullable',
    ]);

    try {
        \Illuminate\Support\Facades\DB::beginTransaction();

        // 2. GESTION DE LA PHOTO
        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Stockage dans storage/app/public/photos_agents
            $photoPath = $request->file('photo')->store('photos_agents', 'public');
        }

        // 3. CRÉATION DU COMPTE UTILISATEUR
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'must_change_password' => $request->has('must_change_password'),
            'profile_picture' => $photoPath, // Enregistre aussi le chemin ici si la colonne existe
        ]);

        // 4. CRÉATION DE L'AGENT AVEC LA PHOTO
        $agent = \App\Models\Agent::create([
            'user_id' => $user->id,
            'email' => $request->email_personnel,
            'email_professionnel' => $request->email_professionnel,
            'matricule' => $request->matricule,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'status' => $request->status,
            'sexe' => $request->sexe,
            'date_of_birth' => $request->date_of_birth,
            'photo' => $photoPath, // <--- C'est cette ligne qui insère le chemin en base
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

        return redirect()->route('agents.index')->with('success', 'Agent et compte utilisateur créés avec succès !');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();

        // En cas d'échec, on supprime la photo si elle a été physiquement enregistrée
        if ($photoPath) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
        }

        // Débogage : affiche l'erreur exacte (ex: colonne 'photo' manquante dans $fillable)
        dd("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}


        public function nouveau() {
            $services = \App\Models\Service::all(); // Assurez-vous que le modèle Service existe
            return view('agents.nouveau', compact('services'));
        }
}
