<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Annonce;

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
    // 1. Calcul du nombre d'agents
    $nombreAgents = DB::table('agents')->count();
     // CALCUL DU NOMBRE D'ANNONCES
    $nombreAnnonces = Annonce::count(); 
    // Calcul du nombre total de tâches (notifications)
     $totalTaches = DB::table('notifications_taches')->count();

    // 2. Calcul des notifications sans réponse
    $notifsSansReponse = DB::table('notifications_taches as notifications')
        ->leftJoin('reponse_notifications', 'notifications.id_notification', '=', 'reponse_notifications.id_notification')
        ->whereNull('reponse_notifications.id_notification')
        ->count();

      // 3. Calculer le pourcentage (sécurité contre la division par zéro)
    $pourcentageNonExecutees = ($totalTaches > 0) 
        ? ($notifsSansReponse / $totalTaches) * 100 
        : 0;

    // 3. Récupération des annonces récentes
    $recentAnnonces = Annonce::latest()->take(5)->get();

    // 4. Envoi de TOUTES les variables à la vue dashboard
    return view('dashboard', compact('nombreAgents', 'notifsSansReponse', 'recentAnnonces', 'nombreAnnonces','pourcentageNonExecutees',
        'totalTaches'));
}

}