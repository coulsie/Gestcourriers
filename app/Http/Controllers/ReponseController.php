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



    if ($request->hasFile('fichiers')) {
    foreach ($request->file('fichiers') as $file) {
        if ($file->isValid()) {
            // Laravel s'occupe de créer le dossier 'reponses' dans storage/app/public
            $path = $file->store('reponses', 'public');
            $filePaths[] = $path;
        }
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
