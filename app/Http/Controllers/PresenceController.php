<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Presence;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PresenceController extends Controller
{
    /**
     * Affiche une liste des ressources (présences).
     */



    public function index(): View
    {
        // Récupère toutes les présences et les passe à la vue 'presences.index'
        // $presences = Presence::latest()->paginate(10);//Prière utiliser un datatable pour gérer la paginantion
        $presences = Presence::get();
        // dd($presences );die;
        return view('presences.index', compact('presences'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle ressource (présence).
     */
    public function create(): View
    {

    // Récupère les agents pour le menu déroulant
    $agents = Agent::all(); // Assurez-vous que Agent a les colonnes 'id', 'first_name' et 'last_name'
    return view('presences.create', compact('agents'));

    }

    /**
     * Stocke une nouvelle ressource (présence) dans la base de données.
     */
 public function store(Request $request): RedirectResponse
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            // 'agent_id' must be present, a number, and exist in the 'agents' table
            // 'AgentID' => 'required|integer|exists:agents,id', //ce champs n'est pas bien nommé. Faire attention au nom des champs
            'agent_id' => 'required|integer|exists:agents,id',

            // 'heure_arrivee' is required and must be a valid date/time string
            // 'heurearrivee' => 'required|date',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_arrivee' => 'required|date',

            // 'heure_depart' is optional (nullable in DB) and must be a valid date/time if provided
            // 'heuredepart' => 'nullable|date|after:heure_arrivee',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_depart' => 'nullable|date|after:heure_arrivee',

            // 'statut' must be one of the defined enum values
            'statut' => ['required',Rule::in(['Absent', 'Présent', 'En Retard']),],

            // 'notes' is optional (text field)
            'notes' => 'nullable|string|max:1000',
        ]);

        // 2. Create the Presence record using the validated data
        // This relies on the $fillable property being set in the Presence model.
            $datas = $request->all();
            // dd($datas);
            // $presence = Presence::create($validatedData);
            $Presence = Presence::create($datas);
           return redirect()->route('presences.index')->with('success', 'Présence créée avec succès.');

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
        $agents = Agent::all();
        return view('presences.edit', compact('presence', 'agents'));
    }

    /**
     * Met à jour la ressource (présence) spécifiée dans la base de données.
     */
    public function update(Request $request, Presence $presence): RedirectResponse
    {
        // Validation des données entrantes pour la mise à jour
        $validatedData = $request->validate([
            // 'Agent_id'      => 'required|exists:agents,id',
            // 'HeureArrivee' => 'required|date',
            // 'HeureDepart'  => 'nullable|date|after:HeureArrivee',
            // 'Statut'       => 'required|string|max:50',
            // 'Notes'        => 'nullable|string',
            'agent_id' => 'required|integer|exists:agents,id',

            // 'heure_arrivee' is required and must be a valid date/time string
            // 'heurearrivee' => 'required|date',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_arrivee' => 'required|date',

            // 'heure_depart' is optional (nullable in DB) and must be a valid date/time if provided
            // 'heuredepart' => 'nullable|date|after:heure_arrivee',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_depart' => 'nullable|date|after:heure_arrivee',

            // 'statut' must be one of the defined enum values
            'statut' => ['required',Rule::in(['Absent', 'Présent', 'En Retard']),],

            // 'notes' is optional (text field)
            'notes' => 'nullable|string|max:1000',
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
