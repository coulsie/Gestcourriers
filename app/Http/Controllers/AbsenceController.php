<?php
namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Agent;
use App\Models\TypeAbsence;
use Illuminate\Support\Facades\Storage;


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
        return view('Absences.index', compact('absences'));
    }
  

    public function create()
    {
        // 1. Récupérer tous les agents pour la liste déroulante
        $agents = Agent::all();

        // 2. Récupérer tous les types d'absences (C'EST ICI QUE MANQUAIT LA VARIABLE)
        $typeAbsences = TypeAbsence::all();

        // 3. Envoyer les deux variables à la vue
        return view('absences.create', compact('agents', 'typeAbsences'));
    }

    /**
     * Stocke une nouvelle ressource dans la base de données.
     */
 public function store(Request $request)
{
    $validatedData = $request->validate([
        'agent_id' => 'required|exists:agents,id',
        'type_absence_id' => 'required|exists:type_absences,id',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'document_justificatif' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        // On retire 'approuvee' de la validation stricte ou on utilise 'boolean'
        'approuvee' => 'nullable', 
    ]);

    // FORCE LA VALEUR : Si la case n'est pas cochée, on met 0
    $validatedData['approuvee'] = $request->has('approuvee') ? 1 : 0;

    // Gestion du fichier (comme vu précédemment)
    if ($request->hasFile('document_justificatif')) {
        $path = $request->file('document_justificatif')->store('justifications', 'public');
        $validatedData['document_justificatif'] = $path;
    }

    Absence::create($validatedData);

    return redirect()->route('absences.index')->with('success', 'Absence enregistrée.');
}

    /**
     * Met à jour la ressource spécifiée dans la base de données.
     */
    

public function update(Request $request, Absence $absence): RedirectResponse
{
    // 1. Validation rigoureuse
    $validatedData = $request->validate([
        'agent_id' => 'required|exists:agents,id',
        'type_absence_id' => 'required|exists:type_absences,id',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'approuvee' => 'boolean',
        'document_justificatif' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // Règle pour le scan
        'notes' => 'nullable|string',
    ]);

    // 2. Gestion du document scanné
    if ($request->hasFile('document_justificatif')) {
        
        // Supprimer l'ancien fichier du disque s'il existe
        if ($absence->document_justificatif) {
            Storage::disk('public')->delete($absence->document_justificatif);
        }

        // Stocker le nouveau fichier
        $path = $request->file('document_justificatif')->store('justifications', 'public');
        
        // Ajouter le nouveau chemin aux données à mettre à jour
        $validatedData['document_justificatif'] = $path;
        
        // Mise à jour automatique du statut si un document est fourni
        $validatedData['statut'] = 'Justifiée';
    }

    // 3. Mise à jour de l'instance du modèle avec les données validées
    $absence->update($validatedData);

    // 4. Redirection
    return redirect()->route('absences.index')->with('success', 'Absence et justificatif mis à jour avec succès.');
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
        return view('Absences.show', compact('absence'));
    }


    public function edit(absence $absence): View
    {
        $type_absences = TypeAbsence::all();
        $agents = Agent::all();

        return view('Absences.edit', compact('absence', 'type_absences', 'agents'));
    }

}
