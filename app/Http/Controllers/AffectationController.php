<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Affectation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AffectationController extends Controller
{
    /**
     * Affiche l'historique des affectations pour un courrier spécifique.
     *
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function index(Courrier $courrier)
    {
        // Charge les affectations avec les détails de l'utilisateur associé
        $affectations = $courrier->affectations()->with('user')->get();
        
        return view('affectations.index', compact('courrier', 'affectations'));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle affectation.
     *
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function create(Courrier $courrier)
    {
        $users = User::pluck('name', 'id');
        return view('affectations.create', compact('courrier', 'users'));
    }

    /**
     * Enregistre une nouvelle affectation dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Courrier $courrier)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'statut' => 'required|string|max:50',
            'commentaires' => 'nullable|string',
        ]);

        // Crée une nouvelle affectation
        $affectation = $courrier->affectations()->create([
            'user_id' => $validatedData['user_id'],
            'statut' => $validatedData['statut'],
            'commentaires' => $validatedData['commentaires'],
            'date_affectation' => Carbon::now(),
            // date_traitement reste null pour l'instant
        ]);

        // Optionnel: Mettez à jour le statut principal du courrier dans la table courriers si vous le souhaitez
        $courrier->update(['statut' => $validatedData['statut'], 'assigne_a' => $validatedData['user_id']]);

        return redirect()->route('courriers.show', $courrier->id)
                         ->with('success', 'Courrier affecté avec succès.');
    }
    
    /**
     * Met à jour le statut d'une affectation spécifique (par exemple, marquer comme terminé).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Affectation  $affectation
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Affectation $affectation)
    {
        $validatedData = $request->validate([
            'statut' => 'required|string|max:50',
        ]);
        
        $affectation->update([
            'statut' => $validatedData['statut'],
            'date_traitement' => Carbon::now(),
        ]);

        // Optionnel: Mettez à jour le statut principal du courrier
        $affectation->courrier->update(['statut' => $validatedData['statut']]);

        return redirect()->back()->with('success', 'Statut de traitement mis à jour.');
    }
}
