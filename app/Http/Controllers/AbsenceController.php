<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Agent;
use App\Models\Absence;
use App\Models\TypeAbsence;

class AbsenceController extends Controller
{
    /**
     * Affiche une liste des absences (filtré par statut 'Absent').
     */
    public function index(): View
    {
        // Récupère uniquement les enregistrements où le statut est 'Absent'
        $absences = Presence::where('Statut', 'Absent')->latest()->paginate(10);

        return view('Absences.index', compact('absences'));

        //$absences = Absence::with(['agent', 'typeAbsence'])->get();

        //return view('absences.index', compact('absences'));

    }

    /**
     * Affiche le formulaire de création d'une nouvelle absence.
     */
    public function create(): View
    {

        //$agents = Agent::all(['id', 'first_name', 'last_name']);
        // Le statut sera implicitement "Absent" lors du stockage
       // return view('Absences.create', compact('agents'));
        $agents = Agent::all(['id', 'first_name', 'last_name']);
        // Récupérer les types d'absences depuis la table type_absences
        $type_absences = \App\Models\TypeAbsence::all(['id', 'nom_type']);

        return view('Absences.create', compact('agents', 'type_absences'));
    }

    /**
     * Stocke une nouvelle absence dans la base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'AgentID'      => 'required|exists:agents,id',
            'HeureArrivee' => 'nullable|date', // Les absences peuvent ne pas avoir d'heure d'arrivée/départ
            'HeureDepart'  => 'nullable|date|after:HeureArrivee',
            // Le statut n'est pas requis dans le formulaire car il sera fixé à 'Absent' ici
            'Notes'        => 'nullable|string',
        ]);

        // Fixe le statut à 'Absent' manuellement avant la création
        $validatedData['Statut'] = 'Absent';
        $validatedData['HeureArrivee'] = null;
        $validatedData['HeureDepart'] = null;


        // Création de l'enregistrement
        Presence::create($validatedData);

        return redirect()->route('absences.index')
                         ->with('success', 'Absence enregistrée avec succès.');
    }

    /**
     * Les méthodes show, edit, update, et destroy restent similaires
     * mais gèrent uniquement les enregistrements d'absence spécifiques passés
     * via le Model Binding (Route Model Binding).
     */

    public function show(Presence $absence): View
    {
        // On s'assure que c'est bien une absence si nécessaire
        if ($absence->Statut !== 'Absent') {
            abort(404);
        }
        return view('absences.show', compact('absence'));
    }

    public function edit(Presence $absence): View
    {
        if ($absence->Statut !== 'Absent') {
            abort(404);
        }
        return view('absences.edit', compact('absence'));
    }

    public function update(Request $request, Presence $absence): RedirectResponse
    {
         if ($absence->Statut !== 'Absent') {
             // Empêche de modifier le statut d'une présence en absence ici, ou vice versa
            abort(403, 'Vous ne pouvez modifier que les absences via ce contrôleur.');
        }

        $validatedData = $request->validate([
            'AgentID'      => 'required|exists:agents,id',
            'Notes'        => 'nullable|string',
        ]);

        // On force le statut à rester "Absent"
        $validatedData['Statut'] = 'Absent';

        $absence->update($validatedData);

        return redirect()->route('absences.index')
                         ->with('success', 'Absence mise à jour avec succès.');
    }

    public function destroy(Presence $absence): RedirectResponse
    {
        if ($absence->Statut !== 'Absent') {
            abort(403, 'Vous ne pouvez supprimer que les absences via ce contrôleur.');
        }

        $absence->delete();

        return redirect()->route('absences.index')
                         ->with('success', 'Absence supprimée avec succès.');
    }
}
