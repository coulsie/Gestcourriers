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
use illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;



class NotificationTacheController extends Controller
{
    /**
     * Affiche une liste des notifications de tâches.
     */
    public function index()
    {
     // Utilisez paginate() au lieu de get()
     $notifications = NotificationTache::with(['agent'])->latest()->paginate();

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
            'date_creation' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'suivi_par'     => 'required|string|max:100',
            'priorite'      => 'required|in:' . implode(',', array_column(PrioriteEnum::cases(), 'value')),
            'statut'        => 'required|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'document'      => 'nullable|string|max:512',
            'lien_action'   => 'nullable|string|max:512|url',
            'date_lecture'  => 'nullable|date',
            'date_completion' => 'nullable|date',

        ]);
         if ($request->hasFile('document')) {
            $path = $request->file('document')->store('public/documents');
            // Stocke uniquement le chemin relatif pour la DB
            $validatedData['document'] = Storage::url($path);
        }
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
            'id_notification' => 'required|integer|exists:notifications_taches,id_notification',

            'agent_id'      => 'required|exists:agents,agent_id',
            'titre'         => 'required|string|max:255',
            'description'   => 'required|string',
            'date_creation' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'suivi_par'     => 'required|string|max:100',
            'priorite'      => 'required|in:' . implode(',', array_column(PrioriteEnum::cases(), 'value')),
            'statut'        => 'required|in:' . implode(',', array_column(StatutEnum::cases(), 'value')),
            'document'      => 'nullable|string|max:512',
            'lien_action'   => 'nullable|string|max:512|url',
            'date_lecture'  => 'nullable|date',
            'date_completion' => 'nullable|date',

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
    {

        $notification = NotificationTache::findOrFail($id);
        $filePath = Storage::disk('local')->path($notification->document);

         if (file_exists($filePath)) {
        // Utilise le helper response() global et la méthode file()
        // Cela générera la réponse HTTP appropriée
        return response()->file(storage_path('app/public/' . $notification->document), [
               ]);
        }

        abort(404); // Fichier non trouvé
    }

   public function markAsRead(Request $request, $id = null)
    {
        // Votre logique pour marquer la notification comme lue ici
        // Exemple :
        // $notification = $request->user()->notifications()->findOrFail($id);
        // $notification->markAsRead();

        // return back()->with('success', 'Notification marquée comme lue.');
    }







}
