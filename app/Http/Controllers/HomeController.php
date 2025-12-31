<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       // 1. On calcule le nombre d'agents
        $nombreAgents = DB::table('agents')->count();

        // 2. On ajoute votre code pour les notifications sans réponse
        $notifsSansReponse =DB::table('notifications_taches as notifications')
            ->leftJoin('reponse_notifications', 'notifications.id_notification', '=', 'reponse_notifications.id_notification')
            ->whereNull('reponse_notifications.id_notification')
            ->count();

        // 3. On envoie les deux variables à la vue
        return view('dashboard', compact('nombreAgents', 'notifsSansReponse'));
    }

}

