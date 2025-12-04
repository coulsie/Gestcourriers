<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;

class CourrierController extends Controller
{
    /**
     * Afficher une liste des courriers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courriers = Courrier::all(); // Récupère tous les courriers
        return view('courriers.index', compact('courriers')); // Renvoie vers une vue
    }

    /**
     * Afficher le formulaire de création d'un nouveau courrier.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courriers.create');
    }

    /**
     * Stocker un nouveau courrier dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'reference' => 'required|unique:courriers|max:255',
            'type' => 'required',
            'objet' => 'required',
            // ... Ajoutez d'autres règles de validation ici
        ]);

        Courrier::create($validatedData); // Crée un courrier avec les données validées

        return redirect()->route('courriers.index')
                         ->with('success', 'Courrier créé avec succès.');
    }

    /**
     * Afficher le courrier spécifié.
     *
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function show(Courrier $courrier)
    {
        return view('courriers.show', compact('courrier'));

    }

    /**
     * Afficher le formulaire d'édition du courrier spécifié.
     *
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function edit(Courrier $courrier)
    {
        return view('courriers.edit', compact('courrier'));
    }

    /**
     * Mettre à jour le courrier spécifié dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Courrier $courrier)
    {
        $validatedData = $request->validate([
            'reference' => 'required|max:255|unique:courriers,reference,' . $courrier->id,
            'type' => 'required',
            'objet' => 'required',
            // ... Ajoutez d'autres règles de validation ici
        ]);

        $courrier->update($validatedData);

        return redirect()->route('courriers.index')
                         ->with('success', 'Courrier mis à jour avec succès.');
    }

    /**
     * Supprimer le courrier spécifié de la base de données.
     *
     * @param  \App\Models\Courrier  $courrier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courrier $courrier)
    {
        $courrier->delete();

        return redirect()->route('courriers.index')
                         ->with('success', 'Courrier supprimé avec succès.');
    }
    public function affecter(Courrier $courrier)
    {
        return view('courriers.affectation.index', compact('courrier'));
    }
}
