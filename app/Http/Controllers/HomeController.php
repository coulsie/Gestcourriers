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
    $totalTaches = DB::table('imputations')->count();

    // 2. Imputations (anciennement notifications) sans réponse
    // Note : On utilise la table 'reponses' car 'imputations' y est liée
    $notifsSansReponse = DB::table('imputations')
        ->leftJoin('reponses', 'imputations.id', '=', 'reponses.imputation_id')
        ->whereNull('reponses.imputation_id')
        ->count();

    // 3. Imputations sans réponse (Doublon technique du point 2, conservé pour votre logique)
    $imputationsSansReponse = $notifsSansReponse;

    // 4. COURRIERS NON IMPUTÉS
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

    // 7. Envoi à la vue
    return view('dashboard', compact(
        'nombreAgents',
        'recentAnnonces',
        'nombreAnnonces',
        'pourcentageNonExecutees',
        'totalTaches',
        'imputationsSansReponse',
        'courriersNonImputes'
    ));
}

}
