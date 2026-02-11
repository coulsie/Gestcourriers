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
use App\Models\Direction;
use Illuminate\Support\Facades\Log;   // <--- INDISPENSABLE
use Illuminate\Support\Facades\File;  // <--- INDISPENSABLE pour File::exists

class ImputationController extends Controller
{
 public function index(Request $request)
{
    // 1. Initialisation de la requête
    $query = Imputation::with(['courrier', 'agents.service', 'auteur']);

    // 2. Application des filtres (Recherche, Niveau, Statut, Agent)
    if ($request->filled('search')) {
        $query->whereHas('courrier', function($q) use ($request) {
            $q->where('reference', 'like', "%{$request->search}%")
              ->orWhere('objet', 'like', "%{$request->search}%");
        });
    }

    if ($request->filled('niveau')) {
        $query->where('niveau', $request->niveau);
    }

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    if ($request->filled('agent_id')) {
        $query->whereHas('agents', function($q) use ($request) {
            $q->where('agents.id', $request->agent_id);
        });
    }

    // --- NOUVEAU : CALCUL DES STATISTIQUES (Basé sur la requête filtrée) ---
    // On clone la requête pour ne pas interférer avec la pagination
    $statsQuery = clone $query;

    $stats = [
        'total'      => $statsQuery->count(),
        'en_cours'   => (clone $statsQuery)->where('statut', 'en_cours')->count(),
        'termine'    => (clone $statsQuery)->where('statut', 'termine')->count(),
        'en_retard'  => (clone $statsQuery)->where('statut', '!=', 'termine')
                                           ->whereDate('echeancier', '<', now())
                                           ->count(),
    ];
    // -----------------------------------------------------------------------

    // 3. Finalisation : Tri et Pagination
    $imputations = $query->latest()->paginate(25)->appends($request->query());

    // 4. Données pour les menus déroulants
    $allAgents = Agent::orderBy('last_name')->get();

    // 5. Retour à la vue avec la variable $stats en plus
    return view('Imputations.index', compact('imputations', 'allAgents', 'stats'));
}



    /**
     * Affiche le formulaire d'imputation pour un courrier spécifique.
     */


public function create(Request $request)
{
    $directions = Direction::orderBy('name', 'asc')->get();
    $services   = Service::orderBy('name', 'asc')->get();
    $agents     = Agent::with('service')->orderBy('last_name', 'asc')->get();
    $courriers  = Courrier::latest()->get();
    
    // AJOUT : Récupérer les utilisateurs pour le champ "Suivi par"
    // On peut filtrer par rôle si nécessaire (ex: uniquement les chefs)
    $users = \App\Models\User::orderBy('name', 'asc')->get(); 

    $courrierSelectionne = null;
    $chemin_fichier = null;

    if ($request->has('courrier_id')) {
        $courrierSelectionne = Courrier::findOrFail($request->courrier_id);
        $chemin_fichier = $request->input('chemin_fichier', $courrierSelectionne->fichier_chemin);
    }

    return view('Imputations.create', compact(
        'directions',
        'services',
        'agents',
        'courriers',
        'courrierSelectionne',
        'chemin_fichier',
        'users' // <--- AJOUTER ICI
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
    // 1. Validation rigoureuse
    $request->validate([
        'agent_ids'         => 'required|array',
        'instructions'      => 'required|string',
        'documents_annexes' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xls,xlsx,ppt,pptx|max:819200',
        'echeancier'        => 'nullable|date',
        'observations'      => 'nullable|string',
        'statut'            => 'required|string',
        'courrier_id'       => 'required|exists:courriers,id',
        'date_imputation'   => 'required|date',
        'niveau'            => 'required|string',
        'suivi_par'        => 'nullable|exists:users,id',
        'user_id'          => 'required|exists:users,id',

    ]);

    try {
        $user = Auth::user();

        // 2. Récupération du Courrier et de son fichier original
        $courrier = Courrier::findOrFail($request->courrier_id);
        // On récupère le chemin du fichier du courrier pour la traçabilité
        $cheminFichierOriginal = $courrier->fichier_chemin;

        // 3. Détermination du Niveau Hiérarchique (Logique 2026)
        // 1. Récupération sécurisée du rôle (évite l'erreur sur null)
        $statusAgent = $user->agent?->status ?? '';
        $roleName = mb_strtolower((string)$statusAgent, 'UTF-8');

        $niveau = match(true) {
            // Niveau Tertiaire : Uniquement Chef de Service
            str_contains($roleName, 'chef de service') => 'tertiaire',

            // Niveau Secondaire : Sous-directeur et Conseiller Technique
            str_contains($roleName, 'sous-directeur') ||
            str_contains($roleName, 'conseiller technique') => 'secondaire',

            // Niveau Primaire : Directeur (doit contenir 'directeur' mais PAS 'sous')
            str_contains($roleName, 'directeur') && !str_contains($roleName, 'sous') => 'primaire',



            default => 'autre',
        };

        // 4. Gestion du document annexe dans public/documents/imputations/annexes
        $annexePath = null;
        if ($request->hasFile('documents_annexes')) {
            $file = $request->file('documents_annexes');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // Chemin vers le dossier public
            $destinationPath = public_path('documents/imputations/annexes');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $file->move($destinationPath, $fileName);
            $annexePath = 'documents/imputations/annexes/' . $fileName;
        }

        // 5. Création de l'Imputation
        $imputation = new Imputation();
        $imputation->courrier_id      = $request->courrier_id;
        $imputation->user_id          = $user->id;
        $imputation->niveau           = $niveau;
        $imputation->instructions     = $request->instructions;
        $imputation->observations     = $request->observations;
        $imputation->documents_annexes = $annexePath;
        $imputation->chemin_fichier    = $cheminFichierOriginal;
        $imputation->date_imputation   = $request->date_imputation;
        $imputation->echeancier        = $request->echeancier;
        $imputation->statut            = $request->statut;

        $imputation->save();

        // 6. Liaison avec les agents assignés
        $imputation->agents()->sync($request->agent_ids);

        // 7. Mise à jour du Courrier (Affectation)
        $courrier->update([
            'statut'   => 'affecté',
            'affecter' => 1
        ]);

        return redirect()->route('imputations.index')
            ->with('success', "Imputation de niveau " . strtoupper($niveau) . " enregistrée avec succès !");

    } catch (\Exception $e) {
        // Utilisation du chemin complet si l'import pose toujours problème
        \Illuminate\Support\Facades\Log::error('Erreur Imputation 2026 : ' . $e->getMessage());

        return back()->withInput()->with('error', "Une erreur est survenue : " . $e->getMessage());
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
        'suivi_par' => 'nullable|exists:users,id',
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
        ->paginate(25);

    return view('Imputations.mes_imputations', compact('imputations'));
}

public function storeSecondary(Request $request, $id)
{
    // 1. Trouver l'imputation primaire parente
    $imputationPrimaire = Imputation::findOrFail($id);

    // 2. Dupliquer l'imputation (copie les champs sans l'ID ni les timestamps)
    $nouvelleImputation = $imputationPrimaire->replicate();

    // 3. Personnaliser pour le niveau secondaire
    $nouvelleImputation->niveau = 'secondaire'; // Assurez-vous que 'secondaire' est dans votre ENUM
    $nouvelleImputation->user_id = Auth::id();  // L'utilisateur qui crée la secondaire
    $nouvelleImputation->instructions = $request->instructions; // Nouvelles instructions si nécessaire

    // On conserve le courrier_id et le chemin_fichier de l'originale
    $nouvelleImputation->save();

    // 4. Lier aux nouveaux agents (Sous-directeurs / Conseillers)
    if ($request->has('agent_ids')) {
        $nouvelleImputation->agents()->sync($request->agent_ids);
    }

    return redirect()->back()->with('success', 'Imputation secondaire créée avec succès.');
}


}







