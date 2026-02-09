<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // À ajouter en haut du fichier
use Illuminate\Support\Facades\Crypt; // À ajouter en haut du fichier

class CourrierController extends Controller
{

 public function index(Request $request)
{
    $query = Courrier::query();

    // 1. Recherche globale (Num Enreg, Référence, ou Nom Expéditeur)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('num_enregistrement', 'like', "%{$search}%")
              ->orWhere('reference', 'like', "%{$search}%")
              ->orWhere('expediteur_nom', 'like', "%{$search}%");
        });
    }

    // 2. Filtre par Type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // 3. Filtre par Statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 4. Filtre par Plage de Dates (Date de début et Date de fin)
    if ($request->filled('date_debut')) {
        $query->whereDate('date_courrier', '>=', $request->date_debut);
    }

    if ($request->filled('date_fin')) {
        $query->whereDate('date_courrier', '<=', $request->date_fin);
    }

    // 5. Tri et Pagination (avec conservation des paramètres de recherche)
    $courriers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

    return view('courriers.index', compact('courriers'));
}


   public function create()
{
    // Suppression du tableau $categories car les options
    // sont maintenant gérées en dur dans la vue Blade.

    return view('courriers.create');
}


public function store(Request $request)
{
    // 1. Validation des données (ajout des champs de confidentialité)
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
        'chemin_fichier' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xls,xlsx,ppt,pptx,odt,ods|max:20480',

        // Nouveaux champs
        'is_confidentiel'      => 'nullable',
        'code_acces'           => 'required_if:is_confidentiel,1|nullable|numeric|digits_between:4,6',
        'date_document_original' => 'nullable|date|before_or_equal:date_courrier',

    ]);

    // Préparation des données additionnelles
    $validatedData['num_enregistrement'] = 'REG-' . date('Y') . '-' . strtoupper(uniqid());
    $validatedData['statut'] = 'reçu';
    $validatedData['affecter'] = 0;
    $validatedData['assigne_a'] = $request->input('assigne_a', 'Non assigné');

    // Gestion de la confidentialité et Hachage du code
    $validatedData['is_confidentiel'] = $request->has('is_confidentiel');


    // Hachage ou Chiffrement du code
    if ($request->filled('code_acces')) {
        $validatedData['code_acces'] = \Illuminate\Support\Facades\Crypt::encryptString($request->code_acces);
    }


    // 2. Gestion du fichier
    if ($request->hasFile('chemin_fichier')) {
        $file = $request->file('chemin_fichier');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $destinationPath = public_path('Documents/courriers');
        $file->move($destinationPath, $fileName);
        $validatedData['chemin_fichier'] = $fileName;
    }

    // 3. Sauvegarde
    \App\Models\Courrier::create($validatedData);

    return redirect()->route('courriers.index')->with('success', 'Courrier enregistré avec succès.');
}

public function show(Courrier $courrier)
{
    if ($courrier->is_confidentiel && !session("access_granted_{$courrier->id}")) {
        return view('courriers.verify_code', compact('courrier'));
    }
    return view('courriers.show', compact('courrier'));
}

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
    // 1. Validation des données
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
        'affecter'             => 'nullable',
        'chemin_fichier'       => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        // Nouveaux champs
        'is_confidentiel'      => 'nullable',
        'code_acces'           => 'required_if:is_confidentiel,1|nullable|numeric|digits_between:4,6',
        'date_document_original' => 'nullable|date|before_or_equal:date_courrier',
    ]);

    // Force la valeur binaire pour affecter
    $validatedData['affecter'] = $request->has('affecter') ? 1 : 0;

    // 2. Gestion du fichier (Mise à jour)
    if ($request->hasFile('chemin_fichier')) {

        // --- CORRECTION DU CHEMIN : Ajout de /courriers ---
        $destinationPath = public_path('Documents/courriers');

        // Supprimer l'ancien fichier s'il existe
        if ($courrier->chemin_fichier) {
            $ancienPath = $destinationPath . '/' . $courrier->chemin_fichier;
            if (file_exists($ancienPath)) {
                unlink($ancienPath);
            }
        }

        $file = $request->file('chemin_fichier');
        // Nettoyage du nom de fichier (remplacement des espaces)
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

        // Déplacement dans le bon dossier
        $file->move($destinationPath, $fileName);

        // Mise à jour du nom dans le tableau pour la base de données
        $validatedData['chemin_fichier'] = $fileName;
    }

    // 3. Mise à jour de la base de données
    $courrier->update($validatedData);

    return redirect()->route('courriers.index')
                    ->with('success', 'Courrier et document mis à jour avec succès.');
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
        $courriers = $query->orderBy('date_courrier', 'desc')->paginate(15);


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



    public function archives(Request $request)
    {
        $query = Courrier::where('statut', 'archivé');

        // Filtre par période
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_courrier', [$request->date_debut, $request->date_fin]);
        }

        // Filtres texte (Recherche partielle)
        if ($request->filled('expediteur')) {
            $query->where('expediteur_nom', 'like', '%' . $request->expediteur . '%');
        }

        if ($request->filled('destinataire')) {
            $query->where('destinataire_nom', 'like', '%' . $request->destinataire . '%');
        }

        if ($request->filled('objet')) {
            $query->where('objet', 'like', '%' . $request->objet . '%');
        }

        $courriers = $query->orderBy('date_courrier', 'desc')->paginate(15);

        return view('courriers.archives', compact('courriers'));
    }

    public function unlock(Request $request, Courrier $courrier)
{
    $request->validate(['code_saisi' => 'required|numeric']);

    // On décrypte le code stocké et on compare
    if (Crypt::decryptString($courrier->code_acces) === $request->code_saisi) {
        // On stocke l'autorisation en session (expire à la fermeture du navigateur)
        session(["access_granted_{$courrier->id}" => true]);
        return redirect()->route('courriers.show', $courrier->id);
    }

    return back()->with('error', 'Code incorrect. Accès refusé.');
}


}
