<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'matricule' => 'required|string|unique:agents|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'Date_Prise_de_service' => 'required|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'service_id' => 'required|exists:services,id', // Doit exister dans la table services
            'user_id' => 'nullable|exists:users,id|unique:agents', // Doit exister et être unique dans agents
        ]);

        // Création de l'enregistrement dans la base de données
        Agent::create($validatedData);

        // Redirection vers l'index avec un message de succès
        return redirect()->route('agents.index')->with('success', 'L\'agent a été créé avec succès.');
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
        // Validation des données (ignore l'unicité du matricule et user_id pour l'enregistrement actuel)
        $validatedData = $request->validate([
            'matricule' => 'required|string|max:255|unique:agents,matricule,'.$agent->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'Date_Prise_de_service' => 'required|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'service_id' => 'required|exists:services,id',
            'user_id' => 'nullable|exists:users,id|unique:agents,user_id,'.$agent->id,
        ]);

        // Mise à jour de l'enregistrement
        $agent->update($validatedData);

        return redirect()->route('agents.index')->with('success', 'L\'agent a été mis à jour avec succès.');
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
