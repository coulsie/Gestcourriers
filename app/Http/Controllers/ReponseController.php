<?php

namespace App\Http\Controllers;

use App\Models\Reponse;
use Illuminate\Http\Request;
use App\Models\Imputation;
use Illuminate\Support\Facades\DB;   // Résout "Undefined DB"
use Illuminate\Support\Facades\Auth; // Résout "Undefined Auth" ou Auth::user()

class ReponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $request->validate([
                'imputation_id' => 'required|exists:imputations,id',
                'contenu' => 'required|string|min:5',
                'pourcentage_avancement' => 'required|integer|min:0|max:100',
            ]);

            try {
                DB::transaction(function () use ($request) {
                    // 1. Stockage fichiers
                    $filePaths = [];
                    if ($request->hasFile('fichiers')) {
                        foreach ($request->file('fichiers') as $file) {
                            $filePaths[] = $file->store('reponses/annexes', 'public');
                        }
                    }

                    // 2. Création réponse
                    Reponse::create([
                        'imputation_id' => $request->imputation_id,
                        'agent_id' => auth::user()->agent->id,
                        'contenu' => $request->contenu,
                        'fichiers_joints' => $filePaths,
                        'date_reponse' => now(),
                        'pourcentage_avancement' => $request->pourcentage_avancement,
                    ]);

                    // 3. Mise à jour statut imputation
                    $imputation = Imputation::find($request->imputation_id);
                    if ($request->pourcentage_avancement == 100) {
                        $imputation->update(['statut' => 'termine', 'date_traitement' => now()]);
                    } else {
                        $imputation->update(['statut' => 'en_cours']);
                    }
                });

                return back()->with('success', 'Réponse enregistrée avec succès.');
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur : ' . $e->getMessage());
            }
        }
    /**
     * Display the specified resource.
     */
    public function show(Reponse $reponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reponse $reponse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reponse $reponse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reponse $reponse)
    {
        //
    }
}
