<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
   /**
     * Affiche la liste des annonces (pour l'administration)
     */
    public function index()
    {
       // On récupère les annonces actives
        $recentAnnonces = Annonce::active()->take(5)->get();
        return view('annonces.index', compact('recentAnnonces'));
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    
    /**
     * Affiche la liste des annonces (pour l'administration)
     */
   
}
