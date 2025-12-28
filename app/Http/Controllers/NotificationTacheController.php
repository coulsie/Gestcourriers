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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;





class NotificationTacheController extends Controller
{
    /**
     * Affiche une liste des notifications de tâches.
     */
    public function index(Request $request)
    {
       // On récupère les notifications avec leur agent associé (Eager Loading)
    // Cela évite le problème N+1 et permet d'accéder à first_name/last_name
    $notifications = NotificationTache::with(['agent'])->latest()->paginate(10);

    // Si vous n'avez pas besoin d'un agent spécifique "global",
    // initialisez-le à null pour éviter l'erreur "Variable non définie"
    $agent = null;


    return view('notifications.index', compact('notifications', 'agent'));


    }
    
    public function index1(Request $request)
    {
        // 1. On récupère l'ID de l'agent connecté
        $agentConnecteId = Auth::id();

        // 2. On filtre les notifications pour n'avoir que les siennes
        // On garde le 'with' si vous affichez encore des infos de son propre profil
        $notifications = NotificationTache::with(['agent'])
            ->where('agent_id', $agentConnecteId) // Filtre crucial
            ->latest()
            ->paginate(10);

        // 3. On récupère l'objet Agent complet pour l'utiliser dans la vue (ex: afficher son nom)
        $agent = Auth::user();

        return view('notifications.index1', compact('notifications', 'agent'));
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
            'is_archived' => 'boolean',
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
       public function show($id_notification)
        {
            // Récupère la notification par sa clé primaire id_notification
            // findOrFail retourne une erreur 404 si l'ID n'existe pas
            $NotificationTache = NotificationTache::findOrFail($id_notification);

            // Retourne la vue en passant la variable exacte attendue par votre fichier Blade
            return view('notifications.show', compact('NotificationTache'));
        }


    /**
     * Affiche le formulaire d'édition de la notification de tâche spécifiée.
     */
    public function edit($id)
    {

        $NotificationTache = NotificationTache::findOrFail($id);

        return view('notifications.edit', compact('NotificationTache'));


    }

    /**
     * Met à jour la notification de tâche spécifiée.
     */

 public function update(Request $request, $id): RedirectResponse
    {
        // 1. Validation des données entrantes
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
            'is_archived' => 'boolean',
            'date_completion' => 'nullable|date',
            // Ajoutez vos autres règles ici
        ]);

        // 2. Récupération de l'instance existante
        $NotificationTache = NotificationTache::findOrFail($id);

        // 3. Mise à jour avec les données validées
        $NotificationTache->update($validatedData);

        // 4. Redirection avec un message de succès
        return redirect()->route('notifications.index')
                         ->with('success', 'La notification a été mise à jour avec succès.');
    }


    /**
     * Supprime la notification de tâche spécifiée.
     */
    public function destroy($id_notification)
    {
        $notification = NotificationTache::findOrFail($id_notification);

        // Vérifier si un document existe et le supprimer du disque
        if ($notification->document) {
            Storage::disk('public')->delete($notification->document);
        }

        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Supprimé avec succès');
    }


    public function visualiserDocument($id)
    {

        $notificationTache = NotificationTache::findOrFail($id);
        $document = $notificationTache->document;


        if (Storage::disk('public')->exists($document)) {
            // Utiliser response()->file() pour afficher le fichier dans le navigateur
            // Laravel définit automatiquement l'en-tête Content-Disposition sur 'inline' par défaut pour cette méthode
            return response()->file(storage_path('app/public/' . $document));

        }


        abort(404); // Fichier non trouvé
    }

   public function markAsRead(Request $request, $id = null)
    {
        // Votre logique pour marquer la notification comme lue ici
        // Exemple :
       

        $notificationTache = $request->agent()->notificationtache()->findOrFail($id);
        $notificationTache->markAsRead();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function agent()
    {
        // Indique que agent_id dans cette table pointe vers la clé primaire de la table Agent
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function downloadPDF()
    {
    $notifications = NotificationTache::all();
    $pdf = Pdf::loadView('notifications.index_pdf', compact('notifications'));
    return $pdf->download('notifications.pdf');
    }


    public function archive($id)
    {
        $notification = NotificationTache::where('agent_id', auth::id());
        $notification->update(['is_archived' => true]);

        return back()->with('success', 'Notification archivée.');
    }

    public function genererPdf()
    {
        $notifications = NotificationTache::with('agent')->get();

        $pdf = Pdf::loadView('notifications.index_pdf', compact('notifications'))
                    ->setPaper('a4', 'landscape'); // Force le mode Paysage

        return $pdf->stream('liste-notifications-2025.pdf');
    }

    public function showNotifications($id) {

        $tache = NotificationTache::where('id_notification', $id)->firstOrFail();
        return view('notifications.index', compact('tache')); // ou ['tache' => $tache]
    }

}
