<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imputation;
use App\Models\Courrier;
use App\Models\Agent;
use App\Models\User;
use App\Models\Service;
use App\Models\Reponse;
use Illuminate\Support\Facades\DB;   // RÉSOUT : Undefined type 'DB'
use Illuminate\Support\Facades\Auth; // RÉSOUT : Undefined method 'user' (via Auth::user)
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ImputationController extends Controller
{
     public function index(Request $request)
{
    // 1. Initialisation de la requête avec toutes les relations nécessaires
    $query = Imputation::with(['courrier', 'agents.service', 'auteur']);

    // 2. Filtre par recherche (Référence ou Objet du courrier)
    if ($request->filled('search')) {
        $query->whereHas('courrier', function($q) use ($request) {
            $q->where('reference', 'like', "%{$request->search}%")
              ->orWhere('objet', 'like', "%{$request->search}%");
        });
    }

    // 3. Filtre par Niveau (primaire, secondaire, tertiaire)
    if ($request->filled('niveau')) {
        $query->where('niveau', $request->niveau);
    }

    // 4. Filtre par Statut (en_attente, en_cours, termine)
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 5. Filtre par Agent assigné
    if ($request->filled('agent_id')) {
        $query->whereHas('agents', function($q) use ($request) {
            $q->where('agents.id', $request->agent_id);
        });
    }

    // 6. Tri par date de création décroissante et pagination
    // appends(request()->query()) permet de garder les filtres actifs lors du changement de page
    $imputations = $query->latest()->paginate(15)->appends($request->query());

    // 7. Récupérer la liste de tous les agents pour remplir le menu déroulant du filtre
    $allAgents = Agent::orderBy('last_name')->get();

    // 8. Retour à la vue avec les deux variables
    return view('Imputations.index', compact('imputations', 'allAgents'));
}



    /**
     * Affiche le formulaire d'imputation pour un courrier spécifique.
     */
 public function create(Request $request)
{
    // 1. Récupération des données pour les listes déroulantes
    $courriers = Courrier::latest()->get();
    $agents = Agent::orderBy('last_name', 'asc')->get();
    $services = Service::orderBy('name', 'asc')->get();
    $users = User::all();

    // 2. Gestion de la sélection automatique (depuis l'index des courriers)
    $courrierSelectionne = null;
    if ($request->has('courrier_id')) {
        // On utilise findOrFail pour s'assurer que le courrier existe
        $courrierSelectionne = Courrier::findOrFail($request->courrier_id);
    }

    // 3. Retour de la vue avec toutes les données fusionnées
    return view('Imputations.create', compact(
        'courriers',
        'agents',
        'services',
        'users',
        'courrierSelectionne'
    ));
}
    public function show(Imputation $imputation)
    {
        // Charger les relations et les réponses triées par date
        $imputation->load(['courrier', 'agents.service', 'auteur', 'reponses.agent']);

        return view('Imputations.show', compact('imputation'));
    }


    /**
     * Enregistre l'imputation dans la base de données.
     */
public function store(Request $request)
{
    $request->validate([
        'agent_ids' => 'required|array',
        'instructions' => 'required|string',
        'annexes.*' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:10240',
        'echeancier' => 'nullable|date',
        'observations' => 'nullable|string',
        'statut' => 'required|string',
        'courrier_id' => 'required|exists:courriers,id',
        'date_imputation' => 'required|date',
        'user_id' => 'required|exists:users,id',
    ]);

    try {
        $user = auth::user();

        // 1. RÉCUPÉRATION DU COURRIER ET DE SON CHEMIN_FICHIER
        $courrier = \App\Models\Courrier::findOrFail($request->courrier_id);
        $cheminFichierOriginal = $courrier->chemin_fichier;

        // 2. DÉTERMINATION DU NIVEAU HIÉRARCHIQUE (LOGIQUE MISE À JOUR)
        $agentStatus = $user->agent ? $user->agent->status : null;
        $roleName = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : $user->role;

        // On nettoie la chaîne (minuscules, retrait des accents et espaces inutiles)
        $valeurReference = mb_strtolower((string)($agentStatus ?? $roleName), 'UTF-8');

        $niveau = match(true) {
            // NIVEAU PRIMAIRE : Directeur (uniquement)
            // On vérifie que 'directeur' est présent mais que 'sous' ne l'est pas
            str_contains($valeurReference, 'directeur') && !str_contains($valeurReference, 'sous') => 'primaire',

            // NIVEAU SECONDAIRE : Sous-directeur, Conseiller Technique, Conseiller Spécial
            str_contains($valeurReference, 'sous-directeur') ||
            str_contains($valeurReference, 'sous_directeur') ||
            str_contains($valeurReference, 'conseiller') => 'secondaire',

            // NIVEAU TERTIAIRE : Chef de service
            str_contains($valeurReference, 'chef') => 'tertiaire',

            // PAR DÉFAUT
            default => 'tertiaire',
        };

        // 3. GESTION DES PIÈCES JOINTES
        $filePaths = [];
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('documents/imputations/annexes'), $fileName);
                $filePaths[] = $fileName;
            }
        }

        // 4. CRÉATION DE L'IMPUTATION
        $imputation = new \App\Models\Imputation();
        $imputation->courrier_id = $request->courrier_id;
        $imputation->user_id = $user->id;
        $imputation->niveau = $niveau;
        $imputation->instructions = $request->instructions;
        $imputation->observations = $request->observations;
        $imputation->documents_annexes = json_encode($filePaths);
        $imputation->chemin_fichier = $cheminFichierOriginal;
        $imputation->date_imputation = $request->date_imputation;
        $imputation->echeancier = $request->echeancier;
        $imputation->statut = 'en_attente';

        if ($imputation->save()) {
            $imputation->agents()->sync($request->agent_ids);

            // 5. MISE À JOUR DU STATUT DU COURRIER
            $courrier->update([
                'statut' => 'Affecté',
                'affecter' => 1
            ]);

            return redirect()->route('imputations.index')
                ->with('success', "Imputation (Niveau : " . ucfirst($niveau) . ") enregistrée avec succès !");
        }

    } catch (\Exception $e) {
        return back()->withInput()->with('error', "Erreur : " . $e->getMessage());
    }
}



    public function edit(Imputation $imputation)
    {
        // Chargement des données nécessaires pour les listes de choix
        $courriers = Courrier::all();
        $agents = Agent::all();
        $services = Service::all();

        // On passe l'imputation spécifique à la vue
        return view('Imputations.edit', compact('imputation', 'courriers', 'agents', 'services'));
    }


        public function update(Request $request, Imputation $imputation)
{
    $validated = $request->validate([
        'agent_ids' => 'required|array',
        'instructions' => 'required|string',
        'annexes.*' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:10240',
        'echeancier' => 'nullable|date',
        'statut' => 'required|string',
        'courrier_id' => 'required|exists:courriers,id',
        'niveau' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ]);

    try {
        // On utilise les données validées pour éviter les injections
        $data = $request->except(['annexes', 'agent_ids']);

        // 1. GESTION DES DOCUMENTS ANNEXES
        if ($request->hasFile('annexes')) {
            // Nettoyage anciens fichiers
            $anciensFichiers = is_string($imputation->documents_annexes)
                ? json_decode($imputation->documents_annexes, true)
                : $imputation->documents_annexes;

            if (is_array($anciensFichiers)) {
                foreach ($anciensFichiers as $ancienNom) {
                    $ancienPath = public_path('documents/imputations/annexes/' . $ancienNom);
                    if (file_exists($ancienPath)) @unlink($ancienPath);
                }
            }

            // Nouveaux fichiers
            $newFilePaths = [];
            foreach ($request->file('annexes') as $file) {
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('documents/imputations/annexes'), $fileName);
                $newFilePaths[] = $fileName;
            }

            // IMPORTANT : Si vous utilisez le "Casting" array dans le modèle,
            // passez directement le tableau, sinon encodez-le.
            $data['documents_annexes'] = $newFilePaths;
        }

        // 2. Synchronisation des agents
        $imputation->agents()->sync($request->agent_ids);

        // 3. Mise à jour (Assurez-vous que les champs sont en "fillable" dans le modèle)
        $imputation->update($data);

        return redirect()->route('imputations.index')->with('success', 'Imputation mise à jour avec succès.');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
    }
}

        public function reponse() {
            return $this->hasOne(Reponse::class, 'imputation_id');
        }



public function destroy(Imputation $imputation)
{
    try {
        // 1. Supprimer les fichiers joints physiquement s'ils existent
        if ($imputation->documents_annexes) {
            $fichiers = is_array($imputation->documents_annexes)
                ? $imputation->documents_annexes
                : json_decode($imputation->documents_annexes, true);

            if (is_array($fichiers)) {
                foreach ($fichiers as $fichier) {
                    $chemin = public_path('documents/imputations/annexes/' . $fichier);
                    if (file_exists($chemin)) {
                        @unlink($chemin);
                    }
                }
            }
        }

        // 2. Supprimer les relations dans la table pivot (agents)
        $imputation->agents()->detach();

        // 3. Supprimer l'imputation
        $imputation->delete();

        return redirect()->route('imputations.index')
            ->with('success', 'L\'imputation a été supprimée avec succès.');

    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
    }
}

public function mesImputations()
{
    $user = auth::user();

    // 1. Sécurité : Vérifier si l'utilisateur est connecté
    if (!$user) {
        return redirect()->route('login');
    }

    // 2. Récupérer l'ID de l'agent de manière sécurisée (évite l'erreur "Undefined method id")
    $agentId = $user->agent->id ?? null;

    // 3. Construction de la requête
    $imputations = \App\Models\Imputation::with(['courrier', 'auteur', 'agents'])
        ->where(function($query) use ($agentId, $user) {
            // Cas A : L'agent de l'utilisateur est dans la table pivot (destinataire)
            if ($agentId) {
                $query->whereHas('agents', function($q) use ($agentId) {
                    $q->where('agents.id', $agentId);
                });
            }

            // Cas B : L'utilisateur est l'auteur de l'imputation (expéditeur)
            $query->orWhere('user_id', $user->id);
        })
        ->latest() // Équivalent à orderBy('created_at', 'desc')
        ->paginate(15);

    return view('Imputations.mes_imputations', compact('imputations'));
}



}







