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
         // 1. Récupère l'ID de l'utilisateur actuellement connecté
        $currentAgentId = Auth::id();

        // 2. Construit la requête Eloquent
        $taches = NotificationTache::
            // WHERE id_agent = ID de l'agent connecté
            where('id_agent', $currentAgentId)
            // WHERE statut NOT IN ('Complétée', 'Annulée') pour n'afficher que les tâches actives
            ->whereNotIn('statut', ['Complétée', 'Annulée'])
            // Triage principal : Priorité descendante (Urgent en premier)
            ->orderBy('priorite', 'desc')
            // Triage secondaire : Date d'échéance ascendante (les plus proches en premier)
            ->orderBy('date_echeance', 'asc')
            // Exécute la requête et récupère les résultats
            ->get();

        // Si vous avez beaucoup de tâches, utilisez paginate(15) au lieu de get() :
        // ->paginate(15);

        // 3. Passe les données à la vue Blade 'notifications_taches.index'
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
