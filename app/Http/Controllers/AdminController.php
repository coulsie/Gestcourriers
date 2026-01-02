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
        $notifications = NotificationTache::latest('date_creation')->take(5)->get();
        $totalAgents = User::where('role', 'user')->count();
        $notifsNonLues = NotificationTache::where('statut', 'Non lu')->count();

        return view('admin.dashboard', compact('notifications', 'totalAgents', 'notifsNonLues'));
    }
}
