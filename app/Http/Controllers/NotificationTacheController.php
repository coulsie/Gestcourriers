<?php

namespace App\Http\Controllers;

use App\Models\NotificationTache;
use Illuminate\Http\Request;
use App\Enums\PrioriteEnum;
use App\Enums\StatutEnum;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;


class NotificationTacheController extends Controller
{
    /**
     * Affiche une liste des notifications de tâches.
     */
    public function index()
    {
        // Récupère toutes les notifications, triées par date de création descendante
        $notifications = NotificationTache::orderBy('date_creation', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle notification.
     */
    public function create()
    {
        // Passe les valeurs d'énumération à la vue pour les menus déroulants
        $priorites = PrioriteEnum::cases();
        $statuts = StatutEnum::cases();
        $agents = Agent::all(); // Assurez-vous que le modèle Agent existe
        return view('notifications.create', compact('priorites', 'statuts', 'agents'));
    }

    /**
     * Stocke une nouvelle notification de tâche.
     */
    public function store(Request $request):RedirectResponse
    {
        $validatedData = $request->validate([
            'agent_id' => 'required|exists:agents,id', // Assurez-vous que la table 'agents' existe
            'titre'         => 'required|string|max:255',
            'description'   => 'required|string',
            'date_echeance' => 'nullable|date',
            'suivi_par'     => 'required|string|max:100',
            'priorite'      => 'required|in:' . implode(',', array_column(PrioriteEnum::cases(), 'value')),
            'statut'        => 'required|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'lien_action'   => 'nullable|string|max:512|url',
        ]);
         $datas = $request->all();
         $NotificationTache = NotificationTache::create($datas);

        return redirect()->route('notifications.index')->with('success', 'Notification de tâche créée avec succès.');
    }

    /**
     * Affiche la notification de tâche spécifiée.
     */
    public function show(NotificationTache $notificationTache)
    {
        // Marquer comme lu si nécessaire lors de la visualisation
        if ($notificationTache->statut === StatutEnum::NonLu) {
            $notificationTache->statut = StatutEnum::EnCours;
            $notificationTache->date_lecture = now();
            $notificationTache->save();
        }

        return view('notifications.show', compact('notificationTache'));
    }

    /**
     * Affiche le formulaire d'édition de la notification de tâche spécifiée.
     */
    public function edit(NotificationTache $notificationTache)
    {
        $priorites = PrioriteEnum::cases();
        $statuts = StatutEnum::cases();
        return view('notifications.edit', compact('notificationTache', 'priorites', 'statuts'));
    }

    /**
     * Met à jour la notification de tâche spécifiée.
     */
    public function update(Request $request, NotificationTache $notificationTache)
    {
        $validatedData = $request->validate([
            'id_agent'      => 'required|exists:agents,id_agent',
            'titre'         => 'required|string|max:255',
            'description'   => 'required|string',
            'date_echeance' => 'nullable|date',
            'suivi_par'     => 'required|string|max:100',
            'priorite'      => 'required|in:' . implode(',', array_column(PrioriteEnum::cases(), 'value')),
            'statut'        => 'required|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'lien_action'   => 'nullable|string|max:512|url',
        ]);

        // Gérer la date de complétion automatiquement si le statut devient 'Complétée'
        if ($request->statut === StatutEnum::Completee->value && is_null($notificationTache->date_completion)) {
            $validatedData['date_completion'] = now();
        } elseif ($request->statut !== StatutEnum::Completee->value) {
            $validatedData['date_completion'] = null;
        }

        $notificationTache->update($validatedData);

        return redirect()->route('notifications.index')
                         ->with('success', 'Notification de tâche mise à jour avec succès.');
    }

    /**
     * Supprime la notification de tâche spécifiée.
     */
    public function destroy(NotificationTache $notificationTache)
    {
        $notificationTache->delete();

        return redirect()->route('notifications.index')
                         ->with('success', 'Notification de tâche supprimée avec succès.');
    }
    public function visualiserDocument($id)
    { $notification = NotificationTache::findOrFail($id);
    $filePath = Storage::disk('local')->path($notification->document);

     if (file_exists($filePath)) {
        // Utilise le helper response() global et la méthode file()
        // Cela générera la réponse HTTP appropriée
        return response()->file($filePath, [
             'Content-Disposition' => 'inline; filename="'.$notification->titre.'"'
        ]);
    }

        abort(404); // Fichier non trouvé
    }
}
