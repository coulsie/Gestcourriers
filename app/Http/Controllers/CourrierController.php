<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        $validatedData = $request->validate([
            'reference' => 'required|unique:courriers|max:255',
            'type' => 'required',
            'objet' => 'required',
            'description' => 'nullable|string',
            'date_courrier' => 'nullable|date',
            'expediteur_nom' => 'required|string|max:255',
            'expediteur_contact' => 'nullable|string|max:255',
            'destinataire_nom' => 'required|string|max:255',
            'destinataire_contact' => 'nullable|string|max:255',
            'assigne_a' => 'nullable|string|max:255',
            'chemin_fichier' => 'nullable|string|max:255',

            // ... Ajoutez d'autres règles de validation ici
        ]);
        if ($request->hasFile('chemin_fichier')) {
            $path = $request->file('chemin_fichier')->store('public/documents');
            // Stocke uniquement le chemin relatif pour la DB
            $validatedData['chemin_fichier'] = Storage::url($path);
           }

        // $courrier = Courrier::create($validatedData);
        $courrier = Courrier::create($request->all());//Laissez all() ici
        return redirect()->route('courriers.index')->with('success', 'Courrier créé avec succès.');
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

        // $courrier->update($validatedData);
       //$courrier->update($request->all());
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
        return view('courriers.RechercheAffichage', [
            'courriers' => $courriers,
            'request' => $request->all(), // Utile pour garder les filtres dans le formulaire
        ]);
    }
     public function visualiserDocument($id)
    {
        $courrier = Courrier::findOrFail($id);
        $cheminFichier = $courrier->chemin_fichier; // Assurez-vous que ce chemin est relatif au disque de stockage

        if (Storage::disk('public')->exists($cheminFichier)) {
            // Utiliser response()->file() pour afficher le fichier dans le navigateur
            // Laravel définit automatiquement l'en-tête Content-Disposition sur 'inline' par défaut pour cette méthode
            return response()->file(storage_path('app/public/' . $cheminFichier));

            // Alternativement, pour plus de contrôle sur les en-têtes (par exemple, forcer le téléchargement), vous pouvez utiliser:
            // return Storage::disk('public')->response($cheminFichier, null, ['Content-Disposition' => 'inline']);
        }

        abort(404, "Le document n'a pas été trouvé.");
    }



}
