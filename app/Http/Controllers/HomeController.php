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
        // 1. Calculs de base
        $nombreAgents = DB::table('agents')->count();
        $nombreAnnonces = Annonce::count();
        $totalTaches = DB::table('notifications_taches')->count();

        // 2. Notifications sans réponse
        $notifsSansReponse = DB::table('notifications_taches as notifications')
            ->leftJoin('reponse_notifications', 'notifications.id_notification', '=', 'reponse_notifications.id_notification')
            ->whereNull('reponse_notifications.id_notification')
            ->count();

        // 3. Imputations sans réponse (Table: reponses)
        $imputationsSansReponse = DB::table('imputations')
            ->leftJoin('reponses', 'imputations.id', '=', 'reponses.imputation_id')
            ->whereNull('reponses.imputation_id')
            ->count();

        // 4. COURRIERS NON IMPUTÉS (NOUVEAU)
        // On cherche les courriers qui n'apparaissent pas dans la table 'imputations'
        $courriersNonImputes = DB::table('courriers')
            ->leftJoin('imputations', 'courriers.id', '=', 'imputations.courrier_id')
            ->whereNull('imputations.courrier_id')
            ->count();

        // 5. Calcul du pourcentage
        $pourcentageNonExecutees = ($totalTaches > 0)
            ? ($notifsSansReponse / $totalTaches) * 100
            : 0;

        // 6. Récupération des annonces récentes
        $recentAnnonces = Annonce::latest()->take(5)->get();

        // 7. Envoi de TOUTES les variables à la vue
        return view('dashboard', compact(
            'nombreAgents',
            'notifsSansReponse',
            'recentAnnonces',
            'nombreAnnonces',
            'pourcentageNonExecutees',
            'totalTaches',
            'imputationsSansReponse',
            'courriersNonImputes' // <-- NOUVELLE VARIABLE
        ));
    }
}
