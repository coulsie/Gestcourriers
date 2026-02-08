<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationTache;
use App\Models\User; // Ou Agent
use Illuminate\Http\Request;
use App\Models\Agent;

class AdminController extends Controller
{
    public function index()
    {
        // Récupération des données pour le dashboard
        $Imputations = \App\Models\Imputation::latest('date_creation')->take(5)->get();
        $totalAgents = User::where('role', 'user')->count();
        $notifsNonLues = \App\Models\Imputation::where('statut', 'Non lu')->count();

        return view('admin.dashboard', compact('Imputations', 'totalAgents', 'notifsNonLues'));
    }
    
    public function coffreFort()
{
    // On ne récupère que les courriers marqués comme confidentiels
    $courriers = \App\Models\Courrier::where('is_confidentiel', true)->get();
    
    return view('admin.coffre_fort', compact('courriers'));
}
}
