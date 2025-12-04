<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Affiche la liste de tous les services.
     */
    public function index(): View
    {
        // Récupère tous les services avec leurs relations (direction, head, agents)
        // pour optimiser les requêtes (Eager Loading)
        $services = Service::with(['direction', 'head', 'agents'])->get();

        return view('services.index', compact('services'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau service.
     */
    public function create(): View
    {
        // Nous avons besoin de toutes les directions et agents pour les listes déroulantes du formulaire
        $directions = Direction::all(['id', 'name']);
        $agents = Agent::all(['id', 'first_name', 'last_name', 'matricule']);

        return view('services.create', compact('directions', 'agents'));
    }

    /**
     * Stocke un nouveau service dans la base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:services',
            'description' => 'nullable|string',
            'direction_id' => 'required|exists:directions,id', // Doit exister dans la table directions
            'head_id' => 'nullable|exists:agents,id',          // Doit exister dans la table agents
        ]);

        // Création de l'enregistrement dans la base de données
        Service::create($validatedData);


        // Redirection vers l'index avec un message de succès
        return redirect()->route('services.index')->with('success', 'Le service a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un service spécifique.
     */
    public function show(Service $service): View
    {
        // Charge les relations pour la vue détaillée
        $service->load(['direction', 'head', 'agents']);

        return view('services.show', compact('service'));
    }

    /**
     * Affiche le formulaire d'édition d'un service.
     */
    public function edit(Service $service): View
    {
        $directions = Direction::all(['id', 'name']);
        $agents = Agent::all(['id', 'first_name', 'last_name', 'matricule']);

        return view('services.edit', compact('service', 'directions', 'agents'));
    }

    /**
     * Met à jour le service spécifié dans la base de données.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        // Validation des données (ignore l'unicité du code pour l'enregistrement actuel)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:services,code,'.$service->id,
            'description' => 'nullable|string',
            'direction_id' => 'required|exists:directions,id',
            'head_id' => 'nullable|exists:agents,id',
        ]);

        // Mise à jour de l'enregistrement
        $service->update($validatedData);

        return redirect()->route('services.index')->with('success', 'Le service a été mis à jour avec succès.');
    }

    /**
     * Supprime le service spécifié de la base de données.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        // Note: Si vous avez défini onDelete('restrict') dans votre migration 'agents',
        // cette suppression échouera s'il y a encore des agents dans ce service.
        return redirect()->route('services.index')->with('success', 'Le service a été supprimé.');
    }
        
    }
