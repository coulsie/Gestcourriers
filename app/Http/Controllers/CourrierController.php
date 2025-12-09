<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\View\View;


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

 public function RechercheAffichage(Request $request): View
    {
        $query = Courrier::query();

        // Appliquer les filtres si des paramètres de recherche sont présents
        if ($request->filled('search_term')) {
            $searchTerm = $request->input('search_term');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('objet', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('expediteur_nom', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('destinataire_nom', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('date_debut')) {
            $query->where('date_courrier', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date_courrier', '<=', $request->input('date_fin'));
        }

        // Récupérer les courriers avec pagination optionnelle (ici on prend 10 par page)
        $courriers = $query->orderBy('date_courrier', 'desc')->paginate(10);

        // Passer les résultats et les anciennes valeurs de recherche à la vue
        return view('courriers.index', [
            'courriers' => $courriers,
            'request' => $request->all(), // Utile pour garder les filtres dans le formulaire
        ]);
    }



}
