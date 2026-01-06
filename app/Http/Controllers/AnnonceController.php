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
            $validated = $request->validate([
                'titre'   => 'required|string|max:255',
                'contenu' => 'required|string',
                'type'    => 'required|in:urgent,information,evenement,avertissement,general',
                'is_active' => 'boolean'
            ]);

            Annonce::create($validated);

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
            $request->validate([
                'titre' => 'required|string|max:191',
                'contenu' => 'required|string',
                'type' => 'required|in:urgent,information,evenement,avertissement',
                'is_active' => 'nullable|boolean',
                'expires_at' => 'nullable|date',
            ]);

            // 2. Recherche de l'annonce
            $annonce = Annonce::findOrFail($id);

            // 3. Préparation des données
            $data = $request->all();

            // Gestion du statut actif (si la checkbox n'est pas cochée, on force à 0)
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Nettoyage de la date d'expiration si vide
            if (empty($data['expires_at'])) {
                $data['expires_at'] = null;
            }

            // 4. Mise à jour
            $annonce->update($data);

            // 5. Redirection vers l'index avec un message flash
            return redirect()->route('annonces.index')
                ->with('success', "L'annonce « {$annonce->titre} » a été modifiée avec succès le " . Carbon::now()->format('d/m/Y à H:i'));
        }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * Affiche la liste des annonces (pour l'administration)
     */

}
