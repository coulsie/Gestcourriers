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




}
