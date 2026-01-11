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

class PresenceController extends Controller
{
    /**
     * Affiche une liste des ressources (présences).
     */



   public function index(): View
    {
        // Récupère toutes les présences et les passe à la vue 'presences.index'
        // $presences = Presence::latest()->paginate(10);//Prière utiliser un datatable pour gérer la paginantion
        $presences = Presence::with('agent')->get();
        // dd($presences );die;
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
            // 1. Définition de l'heure d'arrivée
            $heureArrivee = $request->filled('heure_arrivee')
                ? \Carbon\Carbon::parse($request->heure_arrivee)
                : \Carbon\Carbon::now();

            // 2. Récupération de l'horaire (on cherche en anglais car votre système est en anglais)
            $jourQuery = $heureArrivee->format('l');
            $horaireFixe = \App\Models\Horaire::where('jour', $jourQuery)->first();

            if (!$horaireFixe) {
                return redirect()->back()->with('error', "Aucun horaire trouvé pour : $jourQuery");
            }

            // 3. CALCUL DES MINUTES (Correction de la logique)
            // Heure d'arrivée en minutes depuis minuit
            $minutesArrivee = ($heureArrivee->hour * 60) + $heureArrivee->minute;

            // Heure de début théorique (ex: 07:30)
            $debutTheorique = \Carbon\Carbon::parse($horaireFixe->getRawOriginal('heure_debut'));

            // On force la tolérance en entier (int) pour éviter les erreurs de calcul
            $tolerance = (int) ($horaireFixe->tolerance_retard ?? 15);

            $minutesLimite = ($debutTheorique->hour * 60) + $debutTheorique->minute + $tolerance;

            // 4. Attribution du statut
            // Si l'heure d'arrivée est INFÉRIEURE ou ÉGALE à la limite, il est Présent
            $statut = ($minutesArrivee <= $minutesLimite) ? 'Présent' : 'En Retard';

            // 5. Enregistrement
            \App\Models\Presence::create([
                'agent_id'      => $request->agent_id,
                'heure_arrivee' => $heureArrivee,
                'statut'        => $statut,
                'notes'         => $request->notes,
                'heure_depart'  => $request->heure_depart // Sera null si non rempli
            ]);

           // ... fin de l'enregistrement ...

            return redirect()->route('presences.index')
                            ->with('success', "Pointage enregistré ($statut) à " . $heureArrivee->format('H:i'));

        }
    // --- La méthode à introduire ---
        // On la met en 'private' ou 'protected' car c'est un outil interne au contrôleur

        protected function verifierStatutArrivee()
        {
            // 1. Récupère le nom du jour (ex: "lundi") et met une majuscule
            $nomJour = ucfirst(Carbon::now()->translatedFormat('l'));

            // 2. Cherche l'horaire en base
            $horaireDuJour = Horaire::where('jour', $nomJour)->first();

            // 3. Utilise la méthode estEnRetard() définie dans le modèle Horaire
            if ($horaireDuJour && $horaireDuJour->estEnRetard(Carbon::now())) {
                return 'En Retard';
            }

            return 'Présent';
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

    for ($date = $debutSemaine->copy(); $date <= $finSemaine; $date->addDay()) {
        $jour = $date->format('Y-m-d');

        foreach ($agents as $agent) {
            // 1. Vérifier si présent dans la table 'presences'
            $aPointe = Presence::where('agent_id', $agent->id)->whereDate('created_at', $jour)->exists();

            if (!$aPointe) {
                // 2. Vérifier si une absence est validée dans la table 'absences' pour ce jour
                $absenceValidee = \App\Models\Absence::where('agent_id', $agent->id)
                    ->where('approuvee', true)
                    ->whereDate('date_debut', '<=', $jour)
                    ->whereDate('date_fin', '>=', $jour)
                    ->first();

                $absencesDetectees[] = [
                    'agent_id' => $agent->id,
                    'nom' => $agent->last_name . ' ' . $agent->first_name,
                    'date' => $jour,
                    'est_justifie' => !is_null($absenceValidee),
                    'motif' => $absenceValidee ? $absenceValidee->typeAbsence->libelle : 'Non justifié'
                ];
            }
        }
    }
    return view('presences.validation-hebdo', compact('absencesDetectees'));
}


        public function storeValidationHebdo(Request $request)
        {
            $absences = $request->input('absences', []);

            foreach ($absences as $data) {
                if (isset($data['selected'])) {
                    Presence::create([
                        'agent_id' => $data['agent_id'],
                        'statut'   => 'Absent',
                        'notes'    => 'Absence hebdomadaire validée le lundi.',
                        'created_at' => $data['date'] . ' 08:00:00', // On enregistre à la date concernée
                        'heure_arrivee' => $data['date'] . ' 08:00:00', // AJOUTEZ CETTE LIGNE
                    ]);
                }
            }

            return redirect()->route('presences.index')->with('success', 'Le registre hebdomadaire a été mis à jour.');
        }

        public function rapport(Request $request) 
        {
            // 1. Normalisation des dates (Carbon) pour éviter les erreurs de format
            $debut = $request->debut ? \Carbon\Carbon::parse($request->debut)->startOfDay() : now()->startOfMonth();
            $fin = $request->fin ? \Carbon\Carbon::parse($request->fin)->endOfDay() : now()->endOfMonth();

            // 2. Récupération des données avec les bonnes relations
            // On remplace 'agent.autorisations' par 'agent.absences' car votre table s'appelle 'absences'
            $presences = \App\Models\Presence::with(['agent.absences' => function($q) use ($debut, $fin) {
                $q->where('approuvee', 1)
                ->where(function($query) use ($debut, $fin) {
                    $query->whereBetween('date_debut', [$debut, $fin])
                            ->orWhereBetween('date_fin', [$debut, $fin]);
                });
            }])
            ->whereBetween('heure_arrivee', [$debut, $fin])
            ->get();

            // 3. Calcul des analyses (KPI) avec sécurité division par zéro
            $totalPresences = $presences->count();
            
            $analyses = [
                'taux_presence' => $totalPresences > 0 
                    ? round(($presences->where('statut', 'Présent')->count() / $totalPresences) * 100, 2) 
                    : 0,
                'total_retards' => $presences->where('statut', 'En Retard')->count(),
                // On compte dans la table 'absences'
                'absences_autorisees' => \App\Models\Absence::where('approuvee', 1)
                                        ->whereBetween('date_debut', [$debut, $fin])->count(),
                'absences_injustifiees' => $presences->where('statut', 'Absent')->count(),
            ];

            // 4. Retour à la vue avec les variables formatées pour les inputs date
            return view('presences.etat_periodique', [
                'donnees' => $presences,
                'analyses' => $analyses,
                'debut' => $debut->toDateString(),
                'fin' => $fin->toDateString()
            ]);
        }


// Fichier: app/Http/Controllers/PresenceController.php

        public function rapportPeriodique(Request $request) 
        {
            // Récupération des dates depuis le formulaire (par défaut mois en cours)
            $debut = $request->input('debut', now()->startOfMonth()->toDateString());
            $fin = $request->input('fin', now()->endOfMonth()->toDateString());

            // C'EST ICI QUE VOUS METTEZ LA REQUÊTE ELOQUENT
            $presences = Presence::with(['agent.absences' => function($q) use ($debut, $fin) {
                $q->where('approuvee', 1) 
                ->whereBetween('date_debut', [$debut, $fin]);
            }])
            ->whereBetween('heure_arrivee', [$debut, $fin])
            ->get();

            return view('votre_vue', compact('presences', 'debut', 'fin'));
        }


}
