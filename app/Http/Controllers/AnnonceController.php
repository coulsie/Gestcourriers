<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnonceController extends Controller
{
   /**
     * Affiche la liste des annonces (pour l'administration)
     */
        public function index()
        {
        // On récupère les annonces actives
            $recentAnnonces = Annonce::active()->take(5)->get();
            $annonces = Annonce::orderBy('created_at', 'desc')->get();

            return view('annonces.index', compact('recentAnnonces', 'annonces'));
        }


        /**
         * Affiche le formulaire de création
         */
        public function create()
        {
            return view('annonces.create');
        }

    /**
     * Enregistre une nouvelle annonce
     */
        public function store(Request $request)
{
    // 1. Validation : On retire 'boolean' pour 'is_active' car la checkbox envoie "on"
    $validated = $request->validate([
        'titre'      => 'required|string|max:255',
        'contenu'    => 'required|string',
        'type'       => 'required|in:urgent,information,evenement,avertissement,general',
        'is_active'  => 'nullable',
        'expires_at' => 'nullable|date'
    ]);

    // 2. Traitement manuel des données
    // Conversion de la checkbox en vrai booléen
    $validated['is_active'] = $request->has('is_active');

    // Formatage de la date pour TIMESTAMP (SQL standard)
    if ($request->filled('expires_at')) {
        $validated['expires_at'] = \Carbon\Carbon::parse($request->expires_at)->toDateTimeString();
    } else {
        $validated['expires_at'] = null;
    }

    // 3. Création
    \App\Models\Annonce::create($validated);

    return redirect()->route('annonces.index')
                    ->with('success', 'Annonce publiée avec succès !');
}


    /**
     * Supprime une annonce
     */
        public function destroy(Annonce $annonce)
        {
            $annonce->delete();
            return redirect()->route('annonces.index')
                            ->with('success', 'Annonce supprimée.');
        }
        public function show(string $id)
        {
            //
        }

        /**
     * Show the form for editing the specified resource.
     */
            public function edit($id)
        {
            // 1. Rechercher l'annonce par son ID ou renvoyer une erreur 404 si elle n'existe pas
            $annonce = Annonce::findOrFail($id);

            // 2. Retourner la vue avec les données de l'annonce
            return view('annonces.edit', compact('annonce'));
        }
    /**
     * Update the specified resource in storage.
            */
       public function update(Request $request, $id)
{
    // 1. Validation rigoureuse des données
    // Note : on enlève 'boolean' pour 'is_active' car la checkbox envoie "on" ou rien
    $validatedData = $request->validate([
        'titre'      => 'required|string|max:191',
        'contenu'    => 'required|string',
        'type'       => 'required|in:urgent,information,evenement,avertissement',
        'is_active'  => 'nullable', 
        'expires_at' => 'nullable|date',
    ]);

    // 2. Recherche de l'annonce
    $annonce = Annonce::findOrFail($id);

    // 3. Traitement des données avant mise à jour
    
    // Gestion du statut actif (booléen propre)
    $validatedData['is_active'] = $request->has('is_active');

    // Nettoyage et formatage de la date d'expiration pour TIMESTAMP
    if (!empty($validatedData['expires_at'])) {
        // On s'assure que le format est compatible SQL (YYYY-MM-DD HH:MM:SS)
        $validatedData['expires_at'] = \Carbon\Carbon::parse($validatedData['expires_at'])->startOfDay();
    } else {
        $validatedData['expires_at'] = null;
    }

    // 4. Mise à jour (uniquement avec les données validées)
    $annonce->update($validatedData);

    // 5. Redirection avec un message flash propre
    return redirect()->route('annonces.index')
        ->with('success', "L'annonce « {$annonce->titre} » a été modifiée avec succès le " . now()->format('d/m/Y à H:i'));
}

    /**
     * Remove the specified resource from storage.
     */

    /**
     * Affiche la liste des annonces (pour l'administration)
     */

}
