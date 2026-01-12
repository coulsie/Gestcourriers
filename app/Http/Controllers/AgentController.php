<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\NotificationTache;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'email_professionnel' => 'nullable|email|unique:agents,email_professionnel',
            'matricule' => 'required|string|max:191|unique:agents,matricule',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'status' => ['required',Rule::in(['Agent', 'Chef de service', 'Sous-directeur', 'Directeur']) // Ajustez selon vos ENUM
            ],
            'sexe' => ['nullable', Rule::in(['Male', 'Female'])],
            'date_of_birth' => 'nullable|date',
            'place_birth' => 'nullable|string|max:191',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation de l'image
            'email' => 'nullable|email|max:191|unique:agents,email',
            'phone_number' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'Emploi' => 'nullable|string|max:191',
            'Grade' => 'nullable|string|max:191',
            'Date_Prise_de_service' => 'nullable|date',
            'Personne_a_prevenir' => 'nullable|string|max:191',
            'Contact_personne_a_prevenir' => 'nullable|string|max:191',
            'service_id' => 'required|exists:services,id', // Assurez-vous que le service existe
            'user_id' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Créer le compte utilisateur
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role, // Récupérer le rôle depuis le formulaire
                'is_active' => true,

            ]);

            // 2. Créer l'agent lié à cet utilisateur
            Agent::create([
                'user_id' => $user->id, // Liaison
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'telephone' => $request->telephone,
                'email_professionnel' => $request->email_professionnel,
                'matricule' => $request->matricule,
                'status' => $request->status,
                'sexe' => $request->sexe,
                'date_of_birth' => $request->date_of_birth,
                'place_birth' => $request->place_birth,
                'photo' => $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg|max:2048','matricule' => 'required|string|unique:agents,matricule']),
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'Emploi' => $request->Emploi,
                'Grade' => $request->Grade,
                'Date_Prise_de_service' => $request->Date_Prise_de_service,
                'Personne_a_prevenir' => $request->Personne_a_prevenir,
                'Contact_personne_a_prevenir' => $request->Contact_personne_a_prevenir,
                'service_id' => $request->service_id, // Clé étrangère vers le service d'affectation
                ]);

            DB::commit();
            return redirect()->route('agents.index')->with('success', 'Agent et compte créés avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

        public function nouveau() {
            $services = \App\Models\Service::all(); // Assurez-vous que le modèle Service existe
            return view('agents.nouveau', compact('services'));
        }
}
