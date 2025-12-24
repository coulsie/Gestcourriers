<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\ReponseNotification;

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
    $reponse->agent_id = auth()->id();
    $reponse->message = $validated['message'];

    if ($request->hasFile('Reponse_Piece_jointe')) {
        $path = $request->file('Reponse_Piece_jointe')->store('pieces_jointes', 'public');
        $reponse->Reponse_Piece_jointe = $path;
    }

    $reponse->save();

    return back()->with('success', 'Réponse envoyée avec succès.');
}

public function create()
{
    // On peut passer des données nécessaires au formulaire (ex: liste des notifications)
    return view('reponses.create');
}

}
