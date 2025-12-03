<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DirectionController extends Controller
{
    /**
     * Affiche la liste de toutes les directions.
     */
    public function index(): View
    {
        // Récupère toutes les directions avec leurs responsables (head) et services associés
        $directions = Direction::with(['head', 'services'])->get();

        return view('directions.index', compact('directions'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle direction.
     */
    public function create(): View
    {
        // Pour le formulaire, nous avons besoin de la liste des agents pour choisir le responsable
        $agents = Agent::all(['id', 'first_name', 'last_name']);

        return view('directions.create', compact('agents'));
    }

    /**
     * Stocke une nouvelle direction dans la base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:directions',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:agents,id',
        ]);

        // Création de l'enregistrement dans la base de données via Mass Assignment
        Direction::create($validatedData);

        // Redirection vers l'index avec un message de succès (session flash)
        return redirect()->route('directions.index')->with('success', 'La direction a été créée avec succès.');
    }

    /**
     * Affiche les détails d'une direction spécifique.
     */
    public function show(Direction $direction): View
    {
        // Charge les relations 'head' et 'services' pour la vue détaillée si nécessaire
        $direction->load(['head', 'services']);

        return view('directions.show', compact('direction'));
    }

    /**
     * Affiche le formulaire d'édition d'une direction.
     */
    public function edit(Direction $direction): View
    {
        // Récupère tous les agents pour la liste déroulante du responsable
        $agents = Agent::all(['id', 'first_name', 'last_name']);

        return view('directions.edit', compact('direction', 'agents'));
    }

    /**
     * Met à jour la direction spécifiée dans la base de données.
     */
    public function update(Request $request, Direction $direction): RedirectResponse
    {
        // Validation des données (ignore l'unicité du code pour l'enregistrement actuel)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:directions,code,'.$direction->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:agents,id',
        ]);

        // Mise à jour de l'enregistrement
        $direction->update($validatedData);

        return redirect()->route('directions.index')->with('success', 'La direction a été mise à jour avec succès.');
    }

    /**
     * Supprime la direction spécifiée de la base de données.
     */
    public function destroy(Direction $direction): RedirectResponse
    {
        // Suppression de l'enregistrement.
        // Les services liés seront supprimés automatiquement si onDelete('cascade') est utilisé dans la migration services.
        $direction->delete();

        return redirect()->route('directions.index')->with('success', 'La direction a été supprimée.');
    }
}

