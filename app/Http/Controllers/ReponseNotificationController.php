<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\ReponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NotificationTache;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReponseNotificationController extends Controller
{
    /**
     * Enregistrer une nouvelle réponse.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'id_notification' => 'required|exists:notifications_taches,id_notification',
            'agent_id'        => 'required|exists:agents,id',
            'message'         => 'required|string',
            'piece_jointe'    => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
            'approuvee',
            'appreciation_du_superieur',
        ]);

        // 2. Gestion du fichier (Pièce jointe)
        $path = null;
        if ($request->hasFile('piece_jointe')) {
            // Stocke le fichier dans le dossier 'public/reponses'
            $path = $request->file('piece_jointe')->store('reponsesNotifictions', 'public');
        }

        // 3. Création de l'enregistrement
        $reponse = ReponseNotification::create([
            'id_notification'      => $request->id_notification,
            'agent_id'             => $request->agent_id,
            'message'              => $request->message,
            'Reponse_Piece_jointe' => $path,
            'approuvee'            => $request->approuvee,
            'appreciation_du_superieur' => $request->appreciation_du_superieur
        ]);

        return redirect()->route('notifications.index1')->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Afficher toutes les réponses d'une notification spécifique.
     */
    public function showByNotification($id_notification)
    {
            $notification = reponseNotification::find($id_notification);
            if (!$notification) {
                return redirect()->back()->with('error', 'Notification introuvable');
            }
            return view('reponses.showByNotification', compact('notification'));


    }

    /**
     * Supprimer une réponse (et son fichier associé).
     */
    public function destroy($id)
    {
        $reponse = ReponseNotification::findOrFail($id);

        // Supprimer le fichier physique s'il existe
        if ($reponse->Reponse_Piece_jointe) {
            Storage::disk('public')->delete($reponse->Reponse_Piece_jointe);
        }

        $reponse->delete();

        return response()->json(['message' => 'Réponse supprimée']);
    }
        public function __construct() {
            $this->middleware('auth');
        }

        public function create($id_notification)
        {
            if (Auth::check()) {
                $user = Auth::user();

                if ($user && $user->agent) {
                    $agent_id = $user->agent->id;

                    // --- AJOUTEZ CETTE LIGNE ---
                    // On récupère l'objet notification complet à partir de l'ID
                    $notification = NotificationTache::findOrFail($id_notification);
                    // ---------------------------

                } else {
                    return redirect()->back()->with('error', "Cet utilisateur n'est pas lié à un agent.");
                }
            } else {
                return redirect()->route('login');
            }

            // Ajoutez 'notification' dans le compact
            return view('reponsesNotifications.create', compact('id_notification', 'agent_id', 'notification'));
        }

}
