<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Reponse;
use Illuminate\Http\Request;
use App\Models\Imputation;
use Illuminate\Support\Facades\DB;   // Résout "Undefined DB"

use App\Models\User;


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
    // TEMPORAIRE : Pour tester si les fichiers arrivent
        // Si cela affiche "array:0 []", c'est que votre formulaire HTML est mal configuré


        $request->validate([
            'imputation_id' => 'required|exists:imputations,id',
            'contenu' => 'required|string',
            'pourcentage_avancement' => 'required|integer',
            'fichiers.*' => 'nullable|file'

        ]);

                $filePaths = [];
                if ($request->hasFile('fichiers')) {
                    foreach ($request->file('fichiers') as $file) {
                        // Génération d'un nom unique avec timestamp
                        $fileName = time() . '_' . $file->getClientOriginalName();

                        // Déplacement physique vers public/documents/imputations/annexes
                        $file->move(public_path('reponses'), $fileName);

                        // On ajoute le nom du fichier au tableau
                        $filePaths[] = $fileName;
                    }
                }


        $reponse = new Reponse();
        $reponse->imputation_id = $request->imputation_id;
        $reponse->agent_id = auth::user()->agent->id;
        $reponse->contenu = $request->contenu;
        $reponse->fichiers_joints = $filePaths;
        $reponse->date_reponse = now();
        $reponse->pourcentage_avancement = $request->pourcentage_avancement;
        $reponse->save(); // Utilisez save() pour mieux déboguer les erreurs SQL

        // Mise à jour de l'imputation parente
        $imputation = Imputation::find($request->imputation_id);
        $imputation->statut = ($request->pourcentage_avancement == 100) ? 'termine' : 'en_cours';
        $imputation->save();

        return redirect()->route('imputations.show', $request->imputation_id)->with('success', 'Enregistré !');
    }

public function valider(Request $request, $id)
{
    // 1. Récupération de la réponse
    $reponse = \App\Models\Reponse::findOrFail($id);

    // 2. Validation du fichier
    $request->validate([
        'document_final' => 'required|file|mimes:pdf|max:10240',
    ]);

    if ($request->hasFile('document_final')) {
        $file = $request->file('document_final');

        // 3. Gestion du fichier (Logique public/archives/final)
        $fileName = time() . '_FINAL_' . $file->getClientOriginalName();
        $file->move(public_path('archives/final'), $fileName);

        // 4. MISE À JOUR MANUELLE (Plus fiable que update())
        $reponse->validation = 'acceptee';
        $reponse->document_final_signe = 'archives/final/' . $fileName;
        $reponse->date_approbation = now();

        // On force l'enregistrement dans MariaDB
        $reponse->save();

        // 5. Mise à jour de l'Imputation parente
        if ($reponse->imputation_id) {
            $imputation = \App\Models\Imputation::find($reponse->imputation_id);
            if ($imputation) {
                $imputation->statut = 'termine';
                $imputation->save();

                // 6. Mise à jour du Courrier lié
                if ($imputation->courrier_id) {
                    $courrier = \App\Models\Courrier::find($imputation->courrier_id);
                    if ($courrier) {
                        $courrier->statut = 'archivé';
                        $courrier->save();
                    }
                }
            }
        }

        return back()->with('success', 'Dossier validé, document enregistré et base de données mise à jour !');
    }

    return back()->with('error', 'Échec de la validation : fichier manquant.');
}


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
