<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationTache; // Assurez-vous que le nom du modèle est correct
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Agent;
class NotificationTacheController extends Controller
{
    /**
     * Affiche une liste de toutes les tâches de l'agent connecté.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupère uniquement les tâches non complétées ou non annulées pour l'agent actuel
        $taches = NotificationTache::where('id_agent', Auth::id())
                                   ->whereNotIn('statut', ['Complétée', 'Annulée'])
                                   ->orderBy('priorite', 'desc')
                                   ->orderBy('date_echeance', 'asc')
                                   ->get();
                                   // Utilisez paginate() au lieu de get() si vous avez beaucoup de données

        return view('notifications_taches.index', compact('taches'));
    }

    /**
     * Affiche les détails d'une tâche spécifique.
     * Marque la notification comme lue si nécessaire.
     *
     * @param  int  $id_notification
     * @return \Illuminate\View\View
     */
    public function show($id_notification)
    {
        $tache = NotificationTache::findOrFail($id_notification);

        // Optionnel : Marquer la tâche comme "Non lu" ou mettre à jour la date de lecture
        if ($tache->date_lecture === null) {
            $tache->date_lecture = Carbon::now();
            if ($tache->statut === 'Non lu') {
                $tache->statut = 'En cours'; // Mettre à jour le statut en 'En cours'
            }
            $tache->save();
        }

        return view('notifications_taches.show', compact('tache'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle tâche.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Récupérez les agents (adaptez cette requête à votre modèle et vos besoins)
    $agents = Agent::all();

    // Transmettez la variable $agents à la vue
    return view('notifications_taches.create', [
        'agents' => $agents
    ]);

    }

    /**
     * Stocke une nouvelle tâche nouvellement créée dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'id_agent_assigne' => 'required|exists:users,id', // Assurez-vous que 'users' est le bon nom de table
            'titre'            => 'required|string|max:255',
            'description'      => 'required|string',
            'date_echeance'    => 'nullable|date',
            'priorite'         => 'required|in:Faible,Moyenne,Élevée,Urgent',
            'suivi_par'        => 'required|string|max:100',
            'lien_action'      => 'nullable|url|max:512',
        ]);

        NotificationTache::create($validatedData);

        return redirect()->route('notifications_taches.index')->with('success', 'La nouvelle tâche a été créée avec succès.');
    }

    // Ajoutez ici les méthodes edit(), update() et destroy() si nécessaire
}
