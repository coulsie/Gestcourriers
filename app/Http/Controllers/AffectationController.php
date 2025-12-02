<?php


namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Affectation; // Importez votre modèle Affectation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pour accéder à l'utilisateur connecté

class AffectationController extends Controller
{
    /**
     * Affiche la liste des affectations pour un courrier spécifique.
     */
    public function index(Courrier $courrier)
    {
        // Récupère toutes les affectations liées à ce courrier
        //$affectations = $courrier->affectations()->with('user')->get();

        //return view('affectations.index', compact('courrier', 'affectations'));

// Récupérez les données nécessaires (exemple)
      $courrier = Courrier::all();

    return view('Affectations.index')->with('courrier', $courrier);

    }

    /**
     * Affiche le formulaire pour créer une nouvelle affectation.
     */
    public function create(Courrier $courrier)
    {
        // Vous pouvez passer la liste des utilisateurs disponibles à affecter à la vue
        $users = \App\Models\User::all();
        return view('Affectations.create', compact('courrier', 'users'));
    }

    /**
     * Stocke une nouvelle affectation dans la base de données.
     */
    public function store(Request $request, Courrier $courrier)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'commentaires' => 'nullable|string|max:500',
            // 'statut' peut être défini par défaut dans le contrôleur ou dans le modèle
        ]);

        // Crée une nouvelle affectation en utilisant la relation du modèle Courrier
        $affectation = $courrier->affectations()->create([
            'user_id' => $request->user_id,
            'statut' => 'affecté', // Statut initial
            'date_affectation' => now(),
            'commentaires' => $request->commentaires,
            // 'created_at' et 'updated_at' sont gérés automatiquement par Laravel
        ]);

        // Redirection vers une page de confirmation ou vers le courrier
        return redirect()->route('courriers.show', $courrier->id)
                         ->with('success', 'Le courrier a été affecté avec succès.');
    }
}
