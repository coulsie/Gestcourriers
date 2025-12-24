<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\ReponseNotification;
use Illuminate\Support\Facades\Auth;
USE app\Models\User;

class ReponseNotificationController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'id_notification' => 'required|exists:notifications_taches,id',
        'message' => 'required|string',
        'Reponse_Piece_jointe' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

    $reponse = new ReponseNotification();
    $reponse->id_notification = $validated['id_notification'];
    $reponse->agent_id = Auth::id();
    $reponse->message = $validated['message'];

    if ($request->hasFile('Reponse_Piece_jointe')) {
        $path = $request->file('Reponse_Piece_jointe')->store('pieces_jointes', 'public');
        $reponse->Reponse_Piece_jointe = $path;
    }

    $reponse->save();

    return back()->with('success', 'Réponse envoyée avec succès.');
}

 public function create($id_notification = null, $agent_id = null)
{
    // On passe les IDs à la vue pour les mettre dans des champs cachés (hidden)
   return redirect()->route('reponses.create', [
    'id_notification' => $id_notification,
    'agent_id' => $agent_id
    ]);
}




}
