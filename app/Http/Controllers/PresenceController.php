<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Presence;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PresenceController extends Controller
{
    /**
     * Affiche une liste des ressources (présences).
     */
    public function index(): View
    {
        // Récupère toutes les présences et les passe à la vue 'presences.index'
        $presences = Presence::latest()->paginate(10);

        return view('presences.index', compact('presences'));
    }


    /**
     * Affiche le formulaire de création d'une nouvelle ressource (présence).
     */
    public function create(): View
    {
    // Récupère les agents pour le menu déroulant
    $agents = Agent::all(['id', 'first_name', 'last_name']); // Assurez-vous que Agent a les colonnes 'id', 'first_name' et 'last_name'

    return view('presences.create', compact('agents'));

    }

    /**
     * Stocke une nouvelle ressource (présence) dans la base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'Agent_ID'      => 'required|exists:agents,id',
            'Heure_Arrivee' => 'required|date',
            'Heure_Depart'  => 'nullable|date|after:Heure_Arrivee',
            'Status'       => 'required|string|max:50',
            'Notes'        => 'nullable|string',
        ]);

        // Création de l'enregistrement dans la base de données
        Presence::create($validatedData);

        // Redirection avec un message de succès
        return redirect()->route('presences.index')->with('success', 'Présence enregistrée avec succès.');
    }

    /**
     * Affiche la ressource (présence) spécifiée.
     */
    public function show(Presence $presence): View
    {
        return view('presences.show', compact('presence'));
    }

    /**
     * Affiche le formulaire d'édition de la ressource (présence) spécifiée.
     */
    public function edit(Presence $presence): View
    {
        return view('presences.edit', compact('presence'));
    }

    /**
     * Met à jour la ressource (présence) spécifiée dans la base de données.
     */
    public function update(Request $request, Presence $presence): RedirectResponse
    {
        // Validation des données entrantes pour la mise à jour
        $validatedData = $request->validate([
            'AgentID'      => 'required|exists:agents,id',
            'HeureArrivee' => 'required|date',
            'HeureDepart'  => 'nullable|date|after:HeureArrivee',
            'Statut'       => 'required|string|max:50',
            'Notes'        => 'nullable|string',
        ]);

        // Mise à jour de l'enregistrement
        $presence->update($validatedData);

        // Redirection avec un message de succès
        return redirect()->route('presences.index')
                         ->with('success', 'Présence mise à jour avec succès.');
    }

    /**
     * Supprime la ressource (présence) spécifiée de la base de données.
     */
    public function destroy(Presence $presence): RedirectResponse
    {
        $presence->delete();

        // Redirection avec un message de succès
        return redirect()->route('presences.index')
                         ->with('success', 'Présence supprimée avec succès.');
    }
}
