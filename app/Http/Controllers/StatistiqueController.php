<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
public function index()
{
    $stats = [
        'total'    => \App\Models\Courrier::count(),
        // Filtrage par Type
        'entrants' => \App\Models\Courrier::where('type', 'Incoming')->count(),
        'sortants' => \App\Models\Courrier::where('type', 'Outgoing')->count(),

        // Filtrage par Statut
        'recus'    => \App\Models\Courrier::where('statut', 'reçu')->count(),
        'affectes' => \App\Models\Courrier::where('statut', 'affecté')->count(),
        'archives' => \App\Models\Courrier::where('statut', 'archivé')->count(),
    ];

    // Calcul du taux d'archivage global
    $stats['taux_archivage'] = $stats['total'] > 0
        ? round(($stats['archives'] / $stats['total']) * 100, 1)
        : 0;

    return view('statistiques.index', compact('stats'));
}

public function dashboard()
{
    $now = now();

    $stats = [
        // 1. Volumes globaux d'imputations
        'total_imputations' => \App\Models\Imputation::count(),
        'en_attente'        => \App\Models\Imputation::where('statut', 'en_attente')->count(),
        'en_cours'          => \App\Models\Imputation::where('statut', 'en_cours')->count(),
        'termine'           => \App\Models\Imputation::where('statut', 'termine')->count(),

        // 2. Analyse de l'urgence (Niveaux)
        'primaire'          => \App\Models\Imputation::where('niveau', 'primaire')->count(),
        'secondaire'        => \App\Models\Imputation::where('niveau', 'secondaire')->count(),

        // 3. Performance et Délais
        'en_retard'         => \App\Models\Imputation::where('statut', '!=', 'termine')
                                ->where('echeancier', '<', $now->toDateTimeString())
                                ->count(),

        // 4. Activité des réponses
        'total_reponses'    => \App\Models\Reponse::count(),
        'avancement_moyen'  => \App\Models\Reponse::avg('pourcentage_avancement') ?? 0,
    ];

    // Calcul du taux d'achèvement
    $stats['taux_reussite'] = $stats['total_imputations'] > 0
        ? round(($stats['termine'] / $stats['total_imputations']) * 100, 1)
        : 0;

    return view('statistiques.dashboard', compact('stats'));
}

}
