<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Agent;

class EtatAgentsController extends Controller
{
    public function index(): View
    {
        // Récupère tous les services et charge en même temps les agents associés à chaque service
        $services = Service::with('agents')->get();

        // Vous pouvez aussi ajouter un tri ou un filtre si nécessaire, par exemple trier les agents par nom
        /*
        $services = Service::with(['agents' => function ($query) {
            $query->orderBy('last_name', 'asc');
        }])->get();
        */

        return view('etats.agents_par_service', compact('services'));
    }

    public function Recherche(Request $request): View
    {
        $servicesList = Service::all(['id', 'name']);
        $agents = collect(); // Collection vide par défaut
        $selectedService = null;
        $dateDebutSemaine = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $dateFinSemaine = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Si un ID de service est fourni dans la requête (via le formulaire)
        if ($serviceId = $request->input('service_id')) {
            $selectedService = Service::with('agents.absences')->find($serviceId);

            if ($selectedService) {
                $agents = $selectedService->agents;

                // Optionnel: Calculer les heures d'absence pour la semaine
                foreach ($agents as $agent) {
                    $totalMinutes = $agent->absences
                        ->whereBetween('date_debut', [$dateDebutSemaine, $dateFinSemaine])
                        ->sum(function ($absence) {
                            $debut = Carbon::parse($absence->date_debut);
                            $fin = Carbon::parse($absence->date_fin);
                            return $fin->diffInMinutes($debut);
                        });
                    // Ajoute une propriété dynamique à l'objet agent
                    $agent->heuresAbsenceHebdo = floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';
                }
            }
        }

        return view('etats.agents_par_service_recherche', compact(
            'servicesList',
            'agents',
            'selectedService',
            'dateDebutSemaine',
            'dateFinSemaine'
        ));
     }

}
