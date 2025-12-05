<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Affectation;
use App\Models\Courrier;
use App\Models\User; // Modèle utilisé pour les agents
use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AffectationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupère toutes les affectations avec leurs relations (courrier et agent)
        $affectations = Affectation::with(['courrier', 'agent'])->latest()->get();

        return view('Affectations.index', compact('affectations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Récupère les courriers non encore traités ou clôturés
        $courriers = Courrier::where('statut', '!=', 'traite')
                             ->where('statut', '!=', 'cloture')
                             ->get();

        // Récupère les utilisateurs qui agissent comme agents
        $agents = User::where('role', 'agent')->orWhere('role', 'superviseur')->get();

        return view('Affectations.create', compact('courriers', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'courrier_id' => 'required|exists:courriers,id',
            'agent_id' => 'required|exists:users,id',
            'statut' => 'required|in:affecte,en_cours,traite',
            'commentaires' => 'nullable|string|max:500',
        ]);

        // Utilisation d'une transaction pour garantir la cohérence des données
        DB::beginTransaction();

        try {
            // Création de l'affectation
            $affectation = Affectation::create([
                'courrier_id' => $request->courrier_id,
                'agent_id' => $request->agent_id,
                'statut' => $request->statut,
                'commentaires' => $request->commentaires,
                'date_affectation' => now(), // Définit la date d'affectation au moment de la création
                // date_traitement, created_at, updated_at sont gérés automatiquement
            ]);

            // Optionnel: Mettre à jour le statut du courrier parent
            $courrier = Courrier::findOrFail($request->courrier_id);
            $courrier->statut = $request->statut; // Par exemple 'affecte'
            $courrier->save();

            DB::commit();

            return redirect()->route('affectations.index')->with('success', 'Affectation créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'affectation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Affectation  $affectation
     * @return \Illuminate\Http\Response
     */
     public function show(string $id): View
    {
        // 1. Récupérer le courrier (findOrFail lèvera une 404 si non trouvé)
        $courrier = Courrier::findOrFail($id);

        // 2. Récupérer l'affectation (find retournera null si non trouvé)
        // Note: Si $affectation doit être liée au $courrier, la requête doit être adaptée.
        // En supposant qu'elles partagent le même ID pour l'instant :

        $affectation = Affectation::find($id);
        $affectation = Affectation::where('courrier_id', $courrier->id)->first();
        // 3. Renvoyer les DEUX variables à la vue
        // Utilisez compact pour simplifier le passage de plusieurs variables
        return view('Affectations.show', compact('courrier', 'affectation'));
    }

    /**
     * Update the specified resource in storage.
     * Utilisé principalement pour mettre à jour le statut et la date de traitement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Affectation  $affectation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Affectation $affectation)
    {
        $request->validate([
            'statut' => 'required|in:affecte,en_cours,traite,cloture',
            'commentaires' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['statut', 'commentaires']);

        // Si le statut est mis à 'traite' ou 'cloture', définir la date de traitement
        if (in_array($request->statut, ['traite', 'cloture']) && is_null($affectation->date_traitement)) {
            $data['date_traitement'] = now();
        } elseif (!in_array($request->statut, ['traite', 'cloture'])) {
            $data['date_traitement'] = null; // Réinitialiser si le statut change
        }

        $affectation->update($data);

        // Optionnel: Mettre à jour le statut du courrier parent
        $affectation->courrier->update(['statut' => $request->statut]);

        return redirect()->route('affectations.show', $affectation->id)->with('success', 'Affectation mise à jour avec succès.');
    }

    // Vous pouvez ajouter ici la méthode edit() pour afficher le formulaire d'édition
    // et destroy() pour la suppression si nécessaire.
    public function edit(Affectation $affectation)
    {
        // 1. Récupérer tous les agents actifs pour la liste déroulante (select)
        // Vous pouvez ajouter des conditions (ex: Agent::where('actif', 1)->get()) si nécessaire
        $agents = Agent::all();

        // 2. Récupérer tous les courriers pertinents pour la liste déroulante
        // Peut-être seulement les courriers non encore affectés, selon votre logique métier
        $courriers = Courrier::all();
        // Exemple alternatif : $courriers = Courrier::whereNull('affectation_id')->get();


        // 3. Charger l'affectation actuelle et passer toutes les données à la vue
        return view('Affectations.edit', compact('affectation', 'agents', 'courriers'));
    }

}
