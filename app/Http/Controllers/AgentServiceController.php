<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use Illuminate\Http\Request;

class AgentServiceController extends Controller
{
    /**
     * Affiche la liste des agents filtrée par service.
     * URL cible: /etat-agents-par-service?service_id=1
     */
    public function listeParService(Request $request)
    {
        // 1. Récupérer l'ID du service depuis l'URL
        $serviceId = $request->query('service_id');

        // 2. Récupérer tous les services pour le menu de filtrage dans la vue
        $services = Service::all();

        // 3. Initialiser la requête des agents avec la relation service
        $query = Agent::with('service');

        // 4. Appliquer le filtre si un service_id est fourni
        if ($serviceId) {
            $query->where('service_id', $serviceId);
            $serviceSelectionne = Service::find($serviceId);
        } else {
            $serviceSelectionne = null;
        }

        // 5. Récupérer les résultats
        $agents = $query->orderBy('last_name', 'asc')->get();

        // 6. Retourner la vue avec les données
        return view('agents.etat_par_service', compact('agents', 'services', 'serviceSelectionne', 'serviceId'));
    }

    public function recherche(Request $request)
        {
            // 1. Récupérer les services pour le menu déroulant
            $services = Service::orderBy('name')->get();

            // 2. Initialiser la requête avec la relation service (Eager Loading)
            $query = Agent::with('service');

            // 3. Filtre par Service (si sélectionné)
            if ($request->filled('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            // 4. Recherche par mot-clé (Nom, Prénom, Matricule, Grade)
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('matricule', 'LIKE', "%{$search}%")
                    ->orWhere('Grade', 'LIKE', "%{$search}%");
                });
            }

            // 5. Exécution et pagination
            $agents = $query->orderBy('last_name', 'asc')->paginate(15);

            // 6. Informations pour la vue
            $serviceSelectionne = Service::find($request->service_id);
            $serviceId = $request->service_id;

            return view('agents.etat_par_service.recherche', compact('agents', 'services', 'serviceSelectionne', 'serviceId'));
        }
 }
