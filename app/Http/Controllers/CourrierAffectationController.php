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
        $request->validate([
            'user_id' => 'required|exists:users,id', // Valide l'ID de l'utilisateur cible
            'commentaires' => 'nullable|string|max:500',
            // 'statut' n'est généralement pas saisi par l'utilisateur mais défini par défaut
        ]);

        // Assurez-vous que le courrier existe
        $courrier = Courrier::findOrFail($id);

        // Utilisez une transaction pour assurer la cohérence si vous mettez à jour le statut du courrier principal aussi
        DB::beginTransaction();

        try {
            // Créer une nouvelle entrée dans la table 'affectations'
            $affectation = Affectation::create([
                'courrier_id' => $courrier->id,
                'user_id' => $request->user_id, // L'ID de l'utilisateur assigné
                'statut' => 'Affecté', // Statut initial par défaut
                'commentaires' => $request->commentaires,
                'date_affectation' => now(), // Laravel gère created_at, mais vous avez une colonne spécifique
                // date_traitement sera NULL initialement
            ]);

            // Optionnel: Mettre à jour le statut du courrier principal
            $courrier->statut = 'Affecté et en cours de traitement';
            $courrier->save();

            DB::commit();

            return redirect()->route('courriers.show', $courrier->id)
                ->with('success', 'Le courrier a été affecté avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'affectation.')->withInput();
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
        return view('Affectations.edit', compact('affectation', 'courrier'));

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
