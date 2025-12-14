<?php
namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Agent;
use App\Models\TypeAbsence;

class AbsenceController extends Controller
{
    /**
     * Affiche la liste des ressources (absences).
     */
    public function index(): View
    {
        // Récupère toutes les absences et charge les relations Agent et TypeAbsence
        $absences = Absence::with(['agent', 'typeAbsence'])->latest()->paginate();

        // Renvoie les données à une vue Blade (par ex. resources/views/absences/index.blade.php)
        return view('absences.index', compact('absences'));
    }
     public function create()
    {
        // Récupérer tous les agents et types d'absence pour les menus déroulants
        $agents = Agent::all();
        $type_absences = TypeAbsence::all();

        // Passer les données à la vue
        return view('absences.create', compact('agents', 'type_absences'));
    }

    /**
     * Stocke une nouvelle ressource dans la base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            
            'agent_id' => 'required|integer|exists:agents,id',
            'type_absence_id' => 'required|exists:type_absences,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'approuvee' => 'boolean', // Sera 0 par défaut si non coché dans le formulaire
        ]);
        $datas = $request->all();
        // Crée l'enregistrement dans la base de données
        
        $Absence = Absence::create($datas);

        // Redirige l'utilisateur
        return redirect()->route('absences.index')->with('success', 'Absence créée avec succès.');
        
    }

    /**
     * Met à jour la ressource spécifiée dans la base de données.
     */
    public function update(Request $request, Absence $absence): RedirectResponse
    {
        // Validation (similaire à store)
        $validatedData = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type_absence_id' => 'required|exists:type_absences,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'approuvee' => 'sometimes|boolean', // Valide si le champ est présent dans la requête
        ]);

        // Mise à jour de l'instance du modèle
        $absence->update($validatedData);

        // Redirection
        return redirect()->route('absences.index')->with('success', 'Absence mise à jour.');
    }

    /**
     * Supprime la ressource spécifiée de la base de données.
     */
    public function destroy(Absence $absence): RedirectResponse
    {
        $absence->delete();

        return redirect()->route('absences.index')->with('success', 'Absence supprimée.');
    }

    public function show($id)
    {
        // Charge l'absence et la relation 'typeAbsence' associée.
        // Si l'absence n'existe pas, Laravel générera automatiquement une 404.
        $absence = Absence::with('typeAbsence', 'agent')->findOrFail($id);

        // Passe l'objet $absence complet (avec ses relations chargées) à la vue.
        return view('absences.show', compact('absence'));
    }

     public function edit($id)
    {
        // 1. Récupérer l'absence actuelle (avec 404 si non trouvée)
        $absence = Absence::findOrFail($id);

        // 2. Récupérer toutes les options pour les listes déroulantes
        // On suppose que les tables TypeAbsence et Agent ont une colonne 'nom' ou 'libelle'.
        $type_absences = TypeAbsence::pluck('nom_type', 'id'); 
        $agents = Agent::pluck('last_name','first_name', 'id'); // Remplacez 'nom_complet' par le nom réel de votre colonne d'agent

        // 3. Passer toutes les données à la vue
        return view('absences.edit', compact('absence', 'type_absences', 'agents'));
    }
}
