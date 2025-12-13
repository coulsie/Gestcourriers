<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
        // 1. Validation des données
        $validatedData = $request->validate([
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

        // 2. Gestion du téléchargement de la photo (si présente)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/agents_photos');
            // Stocke uniquement le chemin relatif pour la DB
            $validatedData['photo'] = Storage::url($path);
        }

        // 3. Création de l'agent dans la base de données
        $agent = Agent::create($validatedData);

        // 4. Redirection avec un message de succès
        return redirect()->route('agents.index')->with('success', 'L\'agent ' . $agent->first_name . ' ' . $agent->last_name . ' a été enregistré avec succès.');
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
     public function update(Request $request, Agent $agent): RedirectResponse
    {
        // 1. Validation des données
        $validatedData = $request->validate([
            // Use Rule::unique()->ignore($agent->id) to allow the current agent's own email/matricule
            'email_professionnel' => 'nullable|email|max:191|unique:agents,email_professionnel,' . $agent->id,
            'matricule' => 'required|string|max:191|unique:agents,matricule,' . $agent->id,
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'status' => ['required',Rule::in(['Agent', 'Chef de service', 'Sous-directeur', 'Directeur']) // Ajustez selon vos ENUM
            ],
            'sexe' => ['nullable', Rule::in(['Male', 'Female'])],
            'date_of_birth' => 'nullable|date',
            'place_birth' => 'nullable|string|max:191',
            // Photo is not required for update, but if provided, it must be an image
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:191|unique:agents,email,' . $agent->id,
            'phone_number' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'Emploi' => 'nullable|string|max:191',
            'Grade' => 'nullable|string|max:191',
            'Date_Prise_de_service' => 'nullable|date',
            'Personne_a_prevenir' => 'nullable|string|max:191',
            'Contact_personne_a_prevenir' => 'nullable|string|max:191',
            'service_id' => 'required|exists:services,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // 2. Gestion du téléchargement et suppression de l'ancienne photo (si une nouvelle est fournie)
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($agent->photo) {
                Storage::delete(str_replace('/storage', 'public', $agent->photo));
            }

            // Store the new photo
            $path = $request->file('photo')->store('public/storage/agents_photos');
            $validatedData['photo'] = Storage::url($path);
        }

        // 3. Mise à jour de l'agent dans la base de données
        $agent->update($validatedData);

        // 4. Redirection avec un message de succès
        return redirect()->route('agents.index')->with('success', 'Les informations de l\'agent ' . $agent->first_name . ' ont été mises à jour avec succès.');
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
}
