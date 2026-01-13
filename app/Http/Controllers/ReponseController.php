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
            'contenu' => 'required|string|min:10',
            'fichiers.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        try {
          return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $agent = auth::user()->agent; // On récupère l'agent lié à l'user connecté

                // 1. Gestion des fichiers
                $paths = [];
                if ($request->hasFile('fichiers')) {
                    foreach ($request->file('fichiers') as $file) {
                        $paths[] = $file->store('reponses/fichiers', 'public');
                    }
                }

                // 2. Création de la réponse
                $reponse = Reponse::create([
                    'imputation_id' => $request->imputation_id,
                    'agent_id' => $agent->id,
                    'contenu' => $request->contenu,
                    'fichiers_joints' => $paths,
                    'pourcentage_avancement' => $request->pourcentage_avancement,
                ]);

                // 3. Mise à jour automatique du statut de l'imputation
                $imputation = Imputation::find($request->imputation_id);
                if ($request->pourcentage_avancement == 100) {
                    $imputation->update(['statut' => 'termine', 'date_traitement' => now()]);
                } else {
                    $imputation->update(['statut' => 'en_cours']);
                }

                return redirect()->back()->with('success', 'Votre réponse a été transmise.');
            });
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
