<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Presence;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Horaire; // <--- AJOUTER CET IMPORT
use Carbon\Carbon;      // <--- AJOUTER CET IMPORT
use App\Models\Absence;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class PresenceController extends Controller
{
    /**
     * Affiche une liste des ressources (présences).
     */



 public function index(Request $request)
{
    // 1. Initialisation avec la relation agent
    $query = Presence::with('agent');

    // 2. FILTRE : Recherche par nom ou prénom
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('agent', function($q) use ($search) {
            $q->where(function($subQuery) use ($search) {
                $subQuery->where('last_name', 'like', "%{$search}%")
                         ->orWhere('first_name', 'like', "%{$search}%");
            });
        });
    }

    // 3. FILTRE : Par statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 4. FILTRE : Par date exacte
    if ($request->filled('date')) {
        $query->whereDate('heure_arrivee', $request->date);
    }

    // 5. LOGIQUE DE TRI DYNAMIQUE
    $sort = $request->get('sort', 'heure_arrivee'); // Colonne par défaut
    $direction = $request->get('direction', 'desc'); // Ordre par défaut (plus récent en haut)

    if ($sort === 'nom') {
        // Pour trier par le nom de l'agent, une jointure est nécessaire
        $query->join('agents', 'presences.agent_id', '=', 'agents.id')
              ->select('presences.*') // Important pour garder les IDs de la table presences
              ->orderBy('agents.last_name', $direction)
              ->orderBy('agents.first_name', $direction);
    } else {
        // Tri sur les colonnes de la table presences (heure_arrivee, id, etc.)
        // On vérifie que la colonne existe pour éviter les erreurs SQL
        $allowedSorts = ['id', 'heure_arrivee', 'heure_depart', 'statut'];
        $sortColumn = in_array($sort, $allowedSorts) ? $sort : 'heure_arrivee';
        $query->orderBy($sortColumn, $direction);
    }

    // 6. Exécution avec pagination et conservation des paramètres d'URL
    $presences = $query->paginate(30)->withQueryString();

    return view('presences.index', compact('presences'));
}

    /**
     * Affiche le formulaire de création d'une nouvelle ressource (présence).
     */
    public function create(): View
    {

    // Récupère les agents pour le menu déroulant
    $agents = Agent::all(); // Assurez-vous que Agent a les colonnes 'id', 'first_name' et 'last_name'
    return view('presences.create', compact('agents'));

    }

    /**
     * Stocke une nouvelle ressource (présence) dans la base de données.
                            */
public function store(Request $request)
{
    $request->validate([
        'agent_id' => 'required|exists:agents,id',
        'heure_arrivee' => 'nullable|date',
    ]);

    $heureArrivee = $request->filled('heure_arrivee')
        ? \Carbon\Carbon::parse($request->heure_arrivee)
        : \Carbon\Carbon::now();

    // Traduction pour correspondre à l'insertion (ex: "Lundi")
    $joursFr = [
        'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
    ];
    $jourFr = $joursFr[$heureArrivee->format('l')];

    // On cherche l'horaire
    $horaireFixe = \App\Models\Horaire::where('jour', $jourFr)->first();

    $statut = 'Présent';
    $debugNote = "";

    if (!$horaireFixe) {
        // C'est ce qui arrive actuellement !
        $debugNote = "ERREUR : Aucun horaire configuré pour le jour $jourFr. ";
    } else {
        // Calcul des minutes
        $minArrivee = ($heureArrivee->hour * 60) + $heureArrivee->minute;

        // Comme c'est un type 'time', Carbon le parse très bien
        $debutTheorique = \Carbon\Carbon::parse($horaireFixe->heure_debut);
        $tolerance = (int)$horaireFixe->tolerance_retard;

        $minLimite = ($debutTheorique->hour * 60) + $debutTheorique->minute + $tolerance;

        if ($minArrivee > $minLimite) {
            $statut = 'En Retard';
        }
        $debugNote = "Calculé pour $jourFr (Limite: {$minLimite}m, Arrivée: {$minArrivee}m). ";
    }

    \App\Models\Presence::create([
        'agent_id'      => $request->agent_id,
        'heure_arrivee' => $heureArrivee,
        'statut'        => $statut,
        'notes'         => $debugNote . ($request->notes ?? ''),
        'heure_depart'  => $request->heure_depart ?: null
    ]);

    return redirect()->route('presences.index')->with('success', "Pointage enregistré : $statut");
}

    /**
     * Affiche la ressource (présence) spécifiée.
     */
    public function show(Presence $presence): View
    {
        return view('presences.show', compact('presence'));
    }

    /**
     * Affiche le formulaire d'édition de la ressource (présence) spécifiée.
     */
    public function edit(Presence $presence): View
    {
        $agents = Agent::all();
        return view('presences.edit', compact('presence', 'agents'));
    }

    /**
     * Met à jour la ressource (présence) spécifiée dans la base de données.
     */
    public function update(Request $request, Presence $presence): RedirectResponse
    {
        // Validation des données entrantes pour la mise à jour
        $validatedData = $request->validate([
            // 'Agent_id'      => 'required|exists:agents,id',
            // 'HeureArrivee' => 'required|date',
            // 'HeureDepart'  => 'nullable|date|after:HeureArrivee',
            // 'Statut'       => 'required|string|max:50',
            // 'Notes'        => 'nullable|string',
            'agent_id' => 'required|integer|exists:agents,id',

            // 'heure_arrivee' is required and must be a valid date/time string
            // 'heurearrivee' => 'required|date',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_arrivee' => 'required|date',

            // 'heure_depart' is optional (nullable in DB) and must be a valid date/time if provided
            // 'heuredepart' => 'nullable|date|after:heure_arrivee',//ce champs n'est pas bien nommé. Faire attention au nom des champs
            'heure_depart' => 'nullable|date|after:heure_arrivee',

            // 'statut' must be one of the defined enum values
            'statut' => ['required',Rule::in(['Absent', 'Présent', 'En Retard']),],

            // 'notes' is optional (text field)
            'notes' => 'nullable|string|max:1000',
        ]);

        // Mise à jour de l'enregistrement
        $presence->update($validatedData);

        // Redirection avec un message de succès
        return redirect()->route('presences.index')
                         ->with('success', 'Présence mise à jour avec succès.');
    }

    /**
     * Supprime la ressource (présence) spécifiée de la base de données.
     */
    public function destroy(Presence $presence): RedirectResponse
    {
        $presence->delete();

        // Redirection avec un message de succès
        return redirect()->route('presences.index')
                         ->with('success', 'Présence supprimée avec succès.');
    }


    public function statsPresences()
        {
            $annee = 2026;

            // 1. Stats Journalières (30 derniers jours)
            $journalier = Presence::select(
                    DB::raw('DATE(heure_arrivee) as date'),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN statut = 'Présent' THEN 1 ELSE 0 END) as presents"),
                    DB::raw("SUM(CASE WHEN statut = 'En Retard' THEN 1 ELSE 0 END) as retards"),
                    DB::raw("SUM(CASE WHEN statut = 'Absent' THEN 1 ELSE 0 END) as absents")
                )
                ->whereYear('heure_arrivee', $annee)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();

            // 2. Stats Hebdomadaires
            $hebdo = Presence::select(
                    DB::raw('WEEK(heure_arrivee) as semaine'),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(statut = 'Présent') as presents")
                )
                ->whereYear('heure_arrivee', $annee)
                ->groupBy('semaine')
                ->get();

            // 3. Stats Mensuelles
            $mensuel = Presence::select(
                    DB::raw('MONTH(heure_arrivee) as mois'),
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(statut = 'Présent') as presents"),
                    DB::raw("SUM(statut = 'En Retard') as retards")
                )
                ->whereYear('heure_arrivee', $annee)
                ->groupBy('mois')
                ->get();

            return view('presences.etat', compact('journalier', 'hebdo', 'mensuel', 'annee'));
        }
            public function agent()
            {
                // Laravel cherchera par défaut la colonne agent_id dans la table presences
                return $this->belongsTo(Agent::class, 'agent_id');
            }

public function stats(Request $request)
{
    $annee = 2026;

    // Récupération des dates depuis le formulaire de recherche
    $dateDebut = $request->input('date_debut');
    $dateFin = $request->input('date_fin');

    // Query de base avec filtrage optionnel
    $query = Presence::with('agent')
        ->select(
            DB::raw('DATE(heure_arrivee) as date'),
            DB::raw("COUNT(*) as total"),
            DB::raw("SUM(CASE WHEN statut = 'Présent' THEN 1 ELSE 0 END) as presents"),
            DB::raw("SUM(CASE WHEN statut = 'En Retard' THEN 1 ELSE 0 END) as retards"),
            DB::raw("SUM(CASE WHEN statut = 'Absent' THEN 1 ELSE 0 END) as absents")
        )
        ->whereYear('heure_arrivee', $annee);

    // Appliquer le filtre si les dates sont saisies
    if ($dateDebut && $dateFin) {
        $query->whereBetween(DB::raw('DATE(heure_arrivee)'), [$dateDebut, $dateFin]);
    }

    $journalier = $query->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

    // On conserve vos autres stats (hebdo/mensuel) pour la vue
    $hebdo = Presence::select(DB::raw('WEEK(heure_arrivee) as semaine'), DB::raw("COUNT(*) as total"), DB::raw("SUM(statut = 'Présent') as presents"))
        ->whereYear('heure_arrivee', $annee)->groupBy('semaine')->get();

    $mensuel = Presence::select(DB::raw('MONTH(heure_arrivee) as mois'), DB::raw("COUNT(*) as total"), DB::raw("SUM(statut = 'Présent') as presents"))
        ->whereYear('heure_arrivee', $annee)->groupBy('mois')->get();

    return view('presences.stats', compact('journalier', 'hebdo', 'mensuel', 'annee', 'dateDebut', 'dateFin'));
}

        // app/Http/Controllers/PresenceController.php

        public function indexValidationHebdo()
{
    $debutSemaine = now()->subWeek()->startOfWeek();
    $finSemaine = now()->subWeek()->endOfWeek()->subDays(2); // Vendredi

    $agents = Agent::all();
    $absencesDetectees = [];

    foreach ($agents as $agent) {
        $currentDate = $debutSemaine->copy();
        while ($currentDate <= $finSemaine) {
            $dateStr = $currentDate->toDateString();

            // CRUCIAL : On ne l'ajoute que si AUCUN enregistrement n'existe dans 'presences'
            // Cela fera disparaître la ligne dès que vous aurez cliqué sur "Valider"
            $dejaValide = Presence::where('agent_id', $agent->id)
                                 ->whereDate('heure_arrivee', $dateStr)
                                 ->exists();

            if (!$dejaValide) {
                $justif = \App\Models\Absence::where('agent_id', $agent->id)
                    ->whereDate('date_debut', '<=', $dateStr)
                    ->whereDate('date_fin', '>=', $dateStr)
                    ->first();

                $absencesDetectees[] = [
                    'agent_id'     => $agent->id,
                    'nom'          => $agent->last_name . ' ' . $agent->first_name,
                    'date'         => $dateStr,
                    'est_justifie' => !is_null($justif),
                    'motif'        => $justif ? $justif->motif : null,
                ];
            }
            $currentDate->addDay();
        }
    }
    return view('presences.validation-hebdo', compact('absencesDetectees'));
}


public function storeValidationHebdo(Request $request)
{
    $absencesInput = $request->input('absences', []);

    foreach ($absencesInput as $data) {
        if (isset($data['selected']) && $data['selected'] == "1") {
            $agentId = $data['agent_id'];
            $dateAbsence = $data['date'];

            // 1. Recherche du justificatif
            $justificatif = \App\Models\Absence::where('agent_id', $agentId)
                ->whereDate('date_debut', '<=', $dateAbsence)
                ->whereDate('date_fin', '>=', $dateAbsence)
                ->first();

            $statut = $justificatif ? 'Absence Justifiée' : 'Absent';
            $note = $justificatif ? "Justifié: " . $justificatif->motif : "Absence hebdomadaire.";

            try {
                // 2. Insertion ou Mise à jour forcée (SQL brut)
                DB::statement("
                    INSERT INTO presences (agent_id, heure_arrivee, statut, notes, created_at, updated_at)
                    VALUES (?, ?, ?, ?, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                        statut = VALUES(statut),
                        notes = VALUES(notes),
                        updated_at = NOW()
                ", [
                    $agentId,
                    $dateAbsence . ' 08:00:00',
                    $statut,
                    $note
                ]);
            } catch (\Exception $e) {
                // Utilisation de la façade Log maintenant importée
                Log::error("Erreur storeValidationHebdo : " . $e->getMessage());
            }
        }
    }

    return redirect()->route('presences.index')->with('success', 'Traitement terminé.');
}




        public function rapport(Request $request)
{
    $debut = $request->debut ?? now()->startOfMonth()->toDateString();
    $fin = $request->fin ?? now()->toDateString();

    // 1. Récupérer les données
    $donnees = Presence::with(['agent', 'Absence.typeAbsence'])
                ->whereBetween('created_at', [$debut . ' 00:00:00', $fin . ' 23:59:59'])
                ->get();

    $total = $donnees->count();

    // 2. Calculer les statistiques (KPIs) avec sécurité si total = 0
    $analyses = [
        'taux_presence'   => $total > 0 ? round(($donnees->where('statut', 'Présent')->count() / $total) * 100, 1) : 0,
        'taux_retard'     => $total > 0 ? round(($donnees->where('statut', 'En Retard')->count() / $total) * 100, 1) : 0,
        'taux_justifie'   => $total > 0 ? round(($donnees->where('statut', 'Absence justifiée')->count() / $total) * 100, 1) : 0,
        'taux_injustifie' => $total > 0 ? round(($donnees->where('statut', 'Absent')->count() / $total) * 100, 1) : 0,
    ];

    return view('presences.etat_periodique', compact('donnees', 'analyses', 'debut', 'fin'));
}


// Fichier: app/Http/Controllers/PresenceController.php











/**
 * Vue pour que l'agent puisse pointer lui-même
 */
public function monPointage()
{
    $agentId = Auth::user()->agent->id; // On récupère l'ID agent lié à l'utilisateur connecté
    $aujourdhui = Carbon::today();

    // On cherche si un pointage existe aujourd'hui pour cet agent
    $presence = Presence::where('agent_id', $agentId)
                        ->whereDate('heure_arrivee', $aujourdhui)
                        ->first();

    return view('presences.self_pointage', compact('presence'));
}

/**
 * Logique de clic unique (Arrivée ou Départ)
 */
public function enregistrerPointage()
{
    $agentId = Auth::user()->agent->id;
    $maintenant = Carbon::now();
    $aujourdhui = Carbon::today();

    // 1. Vérifier si l'agent a déjà un pointage aujourd'hui
    $presence = Presence::where('agent_id', $agentId)
                        ->whereDate('heure_arrivee', $aujourdhui)
                        ->first();

    // CAS 1 : L'agent n'a pas encore pointé (ARRIVÉE)
    if (!$presence) {
        // --- DEBUT LOGIQUE DE RETARD EXISTANTE ---
        $joursFr = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi','Sunday'=>'Dimanche'];
        $jourFr = $joursFr[$maintenant->format('l')];
        $horaireFixe = \App\Models\Horaire::where('jour', $jourFr)->first();

        $statut = 'Présent';
        if ($horaireFixe) {
            $minArrivee = ($maintenant->hour * 60) + $maintenant->minute;
            $debutTheorique = Carbon::parse($horaireFixe->heure_debut);
            $minLimite = ($debutTheorique->hour * 60) + $debutTheorique->minute + (int)$horaireFixe->tolerance_retard;
            if ($minArrivee > $minLimite) $statut = 'En Retard';
        }
        // --- FIN LOGIQUE DE RETARD ---

        Presence::create([
            'agent_id' => $agentId,
            'heure_arrivee' => $maintenant,
            'statut' => $statut,
            'notes' => "Pointage automatique (Self-service)"
        ]);


        return redirect()->to(url()->previous())
                        ->with('success', "Pointage enregistré avec succès !");
    }

    // CAS 2 : L'agent a pointé l'arrivée mais pas le départ (DÉPART)
    if ($presence && is_null($presence->heure_depart)) {
        $presence->update([
            'heure_depart' => $maintenant
        ]);
        return back()->with('success', "Départ enregistré à " . $maintenant->format('H:i'));
    }

    // CAS 3 : L'agent a déjà fait ses deux pointages
    return back()->with('error', "Vous avez déjà terminé vos pointages pour aujourd'hui.");
}

public function monHistorique(Request $request)
{
    $agent = Auth::user()->agent;
    $query = Presence::where('agent_id', $agent->id);

    // FILTRE : Par Statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // FILTRE : Par Période (Date Début et Date Fin)
    if ($request->filled('date_debut') && $request->filled('date_fin')) {
        $query->whereBetween('heure_arrivee', [$request->date_debut . ' 00:00:00', $request->date_fin . ' 23:59:59']);
    } elseif ($request->filled('date_debut')) {
        $query->whereDate('heure_arrivee', '>=', $request->date_debut);
    } elseif ($request->filled('date_fin')) {
        $query->whereDate('heure_arrivee', '<=', $request->date_fin);
    }

    $mesPresences = $query->orderBy('heure_arrivee', 'desc')
                          ->paginate(15)
                          ->withQueryString();

    return view('presences.mon_historique', compact('mesPresences'));
}



public function listeFiltree(Request $request)
{
    // 1. Chargement des relations en cascade (Eager Loading)
    // On s'assure de récupérer l'agent, son service et la direction rattachée au service
    $query = Presence::with(['agent.service.direction']);

    // 2. FILTRE : Période (Strictement pour l'année 2026)
    if ($request->filled('date_debut') && $request->filled('date_fin')) {
        $query->whereBetween('heure_arrivee', [
            $request->date_debut . ' 00:00:00',
            $request->date_fin . ' 23:59:59'
        ]);
    }

    // 3. FILTRE : Statut (Présent, Absent, En Retard)
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 4. FILTRE : Direction (Via la relation hiérarchique)
    if ($request->filled('direction_id')) {
        $query->whereHas('agent.service', function($q) use ($request) {
            $q->where('direction_id', $request->direction_id);
        });
    }

    // 5. FILTRE : Service
    if ($request->filled('service_id')) {
        $query->whereHas('agent', function($q) use ($request) {
            $q->where('service_id', $request->service_id);
        });
    }

    // 6. TRI ALPHABÉTIQUE ET SÉLECTION SÉCURISÉE
    // On utilise join pour le tri, mais select('presences.*') pour que Laravel
    // puisse reconstruire les relations sans conflit de colonnes 'id'
    $directionTri = $request->get('sort_agent', 'asc');

    $query->join('agents', 'presences.agent_id', '=', 'agents.id')
          ->select('presences.*')
          ->orderBy('agents.last_name', $directionTri);

    // 7. Exécution de la pagination
    $resultats = $query->paginate(25)->withQueryString();

    // 8. Récupération des listes pour les menus déroulants des filtres
    $directions = \App\Models\Direction::orderBy('name')->get();
    $services = \App\Models\Service::orderBy('name')->get();

    return view('presences.liste_filtree', compact('resultats', 'directions', 'services'));
}
}
