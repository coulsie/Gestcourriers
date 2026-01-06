<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Courrier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Utilisé pour DB::raw() si nécessaire
use App\Models\Agent;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class CourrierAffectationController extends Controller
{
    /**
     * Affiche le formulaire pour affecter un courrier spécifique.
     *
     * @param  int  $id L'ID du courrier à affecter.
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $courrier = Courrier::findOrFail($id);
        $agents = Agent::all(['id', 'first_name', 'last_name']);


        return view('courriers.affectation.create', compact('courrier', 'agents'));
    }

    /**
     * Stocke l'affectation du courrier dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id L'ID du courrier.
     * @return \Illuminate\Http\RedirectResponse
        */

            public function store(Request $request, $id)
        {
            // 1. Validation stricte (incluant les nouveaux champs du formulaire)
            $validated = $request->validate([
                'agent_id'         => 'required|exists:agents,id',
                'statut'           => 'required|string',
                'commentaires'     => 'nullable|string|max:1000',
                'date_affectation' => 'required|date',
                'date_traitement'  => 'nullable|date',
            ]);

            $courrier = Courrier::findOrFail($id);

            DB::beginTransaction();

            try {
                // 2. Création de l'affectation
                Affectation::create([
                    'courrier_id'      => $courrier->id,
                    'agent_id'         => $request->agent_id,
                    'statut'           => $request->statut, // Utilise la valeur du formulaire (ex: en_cours)
                    'commentaires'     => $request->commentaires,
                    'date_affectation' => $request->date_affectation, // Récupère la date du formulaire
                    'date_traitement'  => $request->date_traitement,
                ]);

                // 3. Mise à jour du statut du courrier
                $courrier->update([
                    'affecter' => true
                ]);

                DB::commit();

                return redirect()->route('courriers.index')
                    ->with('success', "Le courrier a été affecté à l'agent.");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Erreur affectation : " . $e->getMessage());

                return back()
                    ->with('error', 'Erreur : ' . $e->getMessage())
                    ->withInput();
            }
        }

    /**
     * Marque une affectation comme traitée (exemple d'action supplémentaire).
     *
     * @param  int  $affectationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function marquerTraite($affectationId)
    {
        $affectation = Affectation::findOrFail($affectationId);

        // Assurez-vous que seul l'utilisateur concerné peut marquer comme traité
        if ($affectation->user_id != Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à traiter ce courrier.');
        }

        $affectation->statut = 'Traité';
        $affectation->date_traitement = now();
        $affectation->save();

        return back()->with('success', 'Le traitement du courrier a été enregistré.');
    }
    public function edit(string $id): View
    {
        // Récupérer l'affectation avec les relations nécessaires
        $affectation = Affectation::with(['agent', 'courrier'])->findOrFail($id);
        $courrier = $affectation->courrier;

        // Passe les variables '$affectation' et '$courrier' à la vue.
        return view('affectations.edit', compact('affectation', 'courrier'));

    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'statut' => 'required|in:affecte,en_cours,traite,cloture',
            'commentaires' => 'nullable|string|max:500',
        ]);

        $affectation = Affectation::findOrFail($id);

        // Mettre à jour les champs de l'affectation
        $affectation->statut = $request->statut;
        $affectation->commentaires = $request->commentaires;

        // Mettre à jour la date de traitement si le statut est 'traite'
        if ($request->statut === 'traite' && is_null($affectation->date_traitement)) {
            $affectation->date_traitement = now();
        }

        $affectation->save();

        return redirect()->route('courriers.affectation.show', [$affectation->courrier_id, $affectation->id])
                         ->with('success', 'Affectation mise à jour avec succès.');
    }
}
