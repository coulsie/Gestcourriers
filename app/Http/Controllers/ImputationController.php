<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imputation;
use App\Models\Courrier;
use App\Models\Agent;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\DB;   // RÉSOUT : Undefined type 'DB'
use Illuminate\Support\Facades\Auth; // RÉSOUT : Undefined method 'user' (via Auth::user)
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ImputationController extends Controller
{
        public function index()
    {
        $imputations = Imputation::with(['courrier', 'agents.service', 'auteur'])
                        ->latest()
                        ->paginate(10);

        return view('Imputations.index', compact('imputations'));
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
            // On enlève le dd() pour laisser le code s'exécuter
            $request->validate([
                'courrier_id' => 'required',
                'agent_ids'   => 'required|array',
                'instructions'=> 'required',
            ]);

            try {
                $user = auth::user();

                // Détermination du niveau
                $roleRaw = ($user->role instanceof \UnitEnum) ? $user->role->value : $user->role;
                $roleValue = strtolower((string)$roleRaw);
                $niveau = match($roleValue) {
                    'directeur' => 'primaire',
                    'sous_directeur' => 'secondaire',
                    'chef_de_service' => 'tertiaire',
                    default => 'tertiaire',
                };

                // Gestion fichiers
                $filePaths = [];
                if ($request->hasFile('annexes')) {
                    foreach ($request->file('annexes') as $file) {
                        $filePaths[] = $file->store('imputations/annexes', 'public');
                    }
                }

                // INSERTION DIRECTE (Sans transaction pour voir l'erreur brute)
                $imputation = new \App\Models\Imputation();
                $imputation->courrier_id = $request->courrier_id;
                $imputation->user_id = $user->id;
                $imputation->niveau = $niveau;
                $imputation->instructions = $request->instructions;
                $imputation->observations = $request->observations;
                $imputation->documents_annexes = json_encode($filePaths);
                $imputation->date_imputation = now()->format('Y-m-d');
                $imputation->echeancier = $request->echeancier;
                $imputation->statut = 'en_attente';

                // On force la sauvegarde
                $saveSuccess = $imputation->save();

                if ($saveSuccess) {
                    // Liaison des agents
                        $imputation->agents()->sync($request->agent_ids);
    // 2. MISE À JOUR DU STATUT DU COURRIER
                // On récupère le courrier et on change son statut en 'Affecté'
                $courrier = \App\Models\Courrier::find($request->courrier_id);
                    if ($courrier) {
                        $courrier->update(['statut' => 'Affecté']);
                    }

                    return redirect()->route('imputations.index')->with('success', 'Enregistré avec succès !');
                } else {
                    dd("L'enregistrement a échoué sans erreur SQL. Vérifiez les événements (Observers) du modèle.");
                }

            } catch (\Exception $e) {
                // CECI DOIT VOUS AFFICHER L'ERREUR REELLE
                dd("ERREUR DETECTEE : " . $e->getMessage());
            }
        }



        public function mesImputations()
        {
            $user = auth::user();
            $agent = $user->agent;

            if (!$agent) {
                return back()->with('error', "Aucun profil agent associé.");
            }

            // Charger les relations pour éviter les erreurs N+1
            $imputations = $agent->imputations()
                ->with(['courrier', 'auteur', 'agents.service'])
                ->latest()
                ->paginate(10);

            return view('Imputations.mes_imputations', compact('imputations'));
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
        $request->validate([
            'agent_ids' => 'required|array',
            'instructions' => 'required|string',
        ]);

        // Synchronisation des agents dans la table pivot
        $imputation->agents()->sync($request->agent_ids);

        // Mise à jour des autres champs
        $imputation->update($request->all());

        return redirect()->route('imputations.index')->with('success', 'Imputation mise à jour.');
    }


    }
