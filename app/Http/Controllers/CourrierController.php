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
    public function index(Request $request)
{
    $query = Courrier::query();

    // Filtre Référence
    if ($request->filled('reference')) {
        $query->where('reference', 'like', '%' . $request->reference . '%');
    }

    // Filtre Type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Filtre Statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    $courriers = $query->orderBy('id', 'desc')->paginate(15);

    return view('courriers.index', compact('courriers'));
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
        // 1. Validation des données
        $validatedData = $request->validate([
            'reference'            => 'required|unique:courriers|max:255',
            'type'                 => 'required',
            'objet'                => 'required',
            'description'          => 'nullable|string',
            'date_courrier'        => 'nullable|date',
            'expediteur_nom'       => 'required|string|max:255',
            'expediteur_contact'   => 'nullable|string|max:255',
            'destinataire_nom'     => 'required|string|max:255',
            'destinataire_contact' => 'nullable|string|max:255',
            'assigne_a'            => 'nullable|string|max:255',
            'statut'               => 'required|string',
            'affecter'             => 'required|boolean',
            'chemin_fichier'       => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);
             $validatedData['statut'] = 'reçu';
        // 2. Gestion du téléchargement du fichier (Nouveau Courrier)
        if ($request->hasFile('chemin_fichier')) {
            $file = $request->file('chemin_fichier');

            // Générer un nom unique
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Déplacer vers public/Documents
            $file->move(public_path('Documents'), $fileName);

            // Enregistrer le nom du fichier dans le tableau des données
            $validatedData['chemin_fichier'] = $fileName;
        }

        // 3. Création du courrier en base de données
        Courrier::create($validatedData);

        // 4. Redirection
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
        // 1. Validation des données (on exclut la référence actuelle de la règle unique)
        $validatedData = $request->validate([
            'reference'            => 'required|max:255|unique:courriers,reference,' . $courrier->id,
            'type'                 => 'required',
            'objet'                => 'required',
            'description'          => 'nullable|string',
            'date_courrier'        => 'nullable|date',
            'expediteur_nom'       => 'required|string|max:255',
            'expediteur_contact'   => 'nullable|string|max:255',
            'destinataire_nom'     => 'required|string|max:255',
            'destinataire_contact' => 'nullable|string|max:255',
            'assigne_a'            => 'nullable|string|max:255',
            'statut'               => 'required|string',
            'affecter'             => 'required|boolean',
            'chemin_fichier'       => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        // 2. Gestion du fichier (Mise à jour)
        if ($request->hasFile('chemin_fichier')) {

            // --- ÉTAPE A : Supprimer l'ancien fichier du dossier public/Documents s'il existe ---
            if ($courrier->chemin_fichier) {
                $ancienPath = public_path('Documents/' . $courrier->chemin_fichier);
                if (file_exists($ancienPath)) {
                    unlink($ancienPath);
                }
            }

            // --- ÉTAPE B : Stocker le nouveau fichier ---
            $file = $request->file('chemin_fichier');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('Documents'), $fileName);

            // --- ÉTAPE C : Mettre à jour le nom dans le tableau de validation ---
            $validatedData['chemin_fichier'] = $fileName;

        } else {
            // Si aucun nouveau fichier n'est envoyé, on garde l'ancien nom de fichier
            // (On retire 'chemin_fichier' de la validation pour ne pas écraser par NULL)
            unset($validatedData['chemin_fichier']);
        }

        // 3. Mise à jour de la base de données
        $courrier->update($validatedData);

        // 4. Redirection
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
        $courriers = $query->orderBy('date_courrier', 'desc')->paginate(25);

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
