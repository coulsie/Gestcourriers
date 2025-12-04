<?php

namespace App\Http\Controllers;

use App\Models\TypeAbsence;
use Illuminate\Http\Request;

class TypeAbsenceController extends Controller
{
    /**
     * Affiche une liste des types d'absence.
     */
    public function index()
    {
        $typesAbsence = TypeAbsence::all();
        // Charge la vue resources/views/typeabsences/index.blade.php
        return view('typeabsences.index', compact('typesAbsence'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau type d'absence.
     */
    public function create()
    {
        // Charge la vue resources/views/typeabsences/create.blade.php
        return view('typeabsences.create');
    }

    /**
     * Stocke un nouveau type d'absence dans la base de données.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $validatedData = $request->validate([
            'NomType' => 'required|string|max:50',
            'Code' => 'nullable|string|max:10',
            'Description' => 'nullable|string',
            'EstPaye' => 'boolean',
        ]);

        // 2. Création de l'enregistrement
        TypeAbsence::create($validatedData);

        // 3. Redirection avec un message de succès
        return redirect()->route('typeabsences.index')->with('success', 'Type d\'absence créé avec succès !');
    }

    /**
     * Affiche le type d'absence spécifié.
     */
    public function show(TypeAbsence $typeAbsence)
    {
        return view('typeabsences.show', compact('typeAbsence'));
    }

    /**
     * Affiche le formulaire d'édition du type d'absence spécifié.
     */
    public function edit(TypeAbsence $typeAbsence)
    {
        return view('typeabsences.edit', compact('typeAbsence'));
    }

    /**
     * Met à jour le type d'absence spécifié dans la base de données.
     */
    public function update(Request $request, TypeAbsence $typeAbsence)
    {
        $validatedData = $request->validate([
            'NomType' => 'required|string|max:50',
            'Code' => 'nullable|string|max:10',
            'Description' => 'nullable|string',
            'EstPaye' => 'boolean',
        ]);

        $typeAbsence->update($validatedData);

        return redirect()->route('typeabsences.index')->with('success', 'Type d\'absence mis à jour avec succès !');
    }

    /**
     * Supprime le type d'absence spécifié de la base de données.
     */
    public function destroy(TypeAbsence $typeAbsence)
    {
        $typeAbsence->delete();

        return redirect()->route('typeabsences.index')->with('success', 'Type d\'absence supprimé avec succès !');
    }
}
