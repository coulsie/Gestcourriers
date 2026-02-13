<?php

use Illuminate\Support\Facades\{Auth, Route};
use App\Http\Controllers\{
    HomeController, AdminController, UserController, ProfileController,
    AgentController, CourrierController, AffectationController,
    CourrierAffectationController, DirectionController, ServiceController,
    PresenceController, AbsenceController, TypeAbsenceController,
    EtatAgentsController, NotificationTacheController, AnnonceController,
    ReponseNotificationController, AgentServiceController, ImputationController,
    StatistiqueController, ReponseController, PostController, RoleController,ExtractionController
};
use App\Http\Controllers\Auth\PasswordSetupController;
use App\Http\Controllers\Auth\ForgotPasswordController;



/*
|--------------------------------------------------------------------------
| 1. ACCÈS PUBLICS & AUTHENTIFICATION DE BASE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome-login');
})->middleware('guest');

Auth::routes();

// Mot de passe oublié
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

/*
|--------------------------------------------------------------------------
| 2. ESPACE SÉCURISÉ (Authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // 1. Affichage de l'interface de saisie
    Route::get('/extraction', [ExtractionController::class, 'index'])
        ->name('extraction.index');

    // 2. Traitement du script SQL/Oracle (POST)
    Route::post('/extraction/execute', [ExtractionController::class, 'execute'])
        ->name('extraction.execute');

    // 3. Exportation des résultats vers Excel (POST)
    Route::post('/extraction/export', [ExtractionController::class, 'export'])
        ->name('extraction.export');

});
Route::get('/extraction/execute', function() {
    return redirect()->route('extraction.index');
});


Route::middleware(['auth'])->group(function () {

    // --- CONFIGURATION INITIALE (HORS FORCE.PASSWORD) ---
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/password/setup', [PasswordSetupController::class, 'show'])->name('password.setup');
    Route::post('/password/setup', [PasswordSetupController::class, 'update'])->name('password.setup.update');

    // --- ADMINISTRATION SYSTÈME & RÔLES ---
    Route::middleware(['can:manage-users'])->group(function () {
        Route::get('/admin/coffre-fort', [AdminController::class, 'coffreFort'])->name('admin.coffre-fort');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset_password');

        // Gestion des Rôles (Spatie)
        Route::resource('roles', RoleController::class);
    });

    Route::middleware(['can:voir-utilisateurs'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/upgrade', [UserController::class, 'upgradeUser'])->name('users.upgrade');
    });

    Route::middleware(['can:access-admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // --- GESTION DES ABSENCES (LOGIQUE DE RÔLES) ---
    // Espace Agent (Tout utilisateur)
    Route::get('/mon-autorisation-absence', [AbsenceController::class, 'monautorisation'])->name('absences.monautorisation');
    Route::post('/absences/monstore', [AbsenceController::class, 'monstore'])->name('absences.monstore');

    // Espace Contrôle (Admin, RH, Superviseur) - Notez la majuscule sur Superviseur
    Route::middleware(['role:admin|rh|Superviseur'])->group(function () {
        Route::get('/absences/validations', [AbsenceController::class, 'validationListe'])->name('absences.validation_liste');
        Route::post('/absences/approuver/{id}', [AbsenceController::class, 'approuver'])->name('absences.approuver');
        Route::resource('typeabsences', TypeAbsenceController::class); // Ajouté pour la cohérence
    });

    /*
    |--------------------------------------------------------------------------
    | 3. ESPACE MÉTIER (Authentification + Changement de mot de passe forcé)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['force.password'])->group(function () {

        // --- DASHBOARDS & PROFIL ---
        Route::get('/tableau-de-bord', [AgentController::class, 'dashb'])->name('agent.dashboard');
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::get('/create', [ProfileController::class, 'create'])->name('create');
            Route::match(['put', 'post'], '/update', [ProfileController::class, 'update'])->name('update');
        });

        // --- GESTION DES STRUCTURES ---
        Route::resource('directions', DirectionController::class);
        Route::resource('services', ServiceController::class);
        Route::get('/directions/{direction}/services', [DirectionController::class, 'getServices'])->name('directions.services');

        // --- GESTION DES AGENTS ---
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/nouveau', [AgentController::class, 'nouveau'])->name('nouveau');
            Route::post('/enregistrer', [AgentController::class, 'Enr'])->name('enregistrer');
            Route::match(['get', 'post'], '/par-service', [AgentServiceController::class, 'listeParService'])->name('par.service');
            Route::match(['get', 'post'], '/par-service/recherche', [AgentServiceController::class, 'recherche'])->name('par.service.recherche');
        });
        Route::resource('agents', AgentController::class)->except(['create', 'store']);

        // --- PRÉSENCES & POINTAGES ---
        Route::prefix('presences')->name('presences.')->group(function () {
            Route::get('/mon-pointage', [PresenceController::class, 'monPointage'])->name('monPointage');
            Route::post('/mon-pointage/enregistrer', [PresenceController::class, 'enregistrerPointage'])->name('enregistrerPointage');
            Route::get('/mon-historique', [PresenceController::class, 'monHistorique'])->name('monHistorique');
            Route::get('/liste-filtree', [PresenceController::class, 'listeFiltree'])->name('listeFiltree');
            Route::get('/etat', [PresenceController::class, 'etat'])->name('etat'); // Ajouté selon votre sidebar
        });



        // --- GESTION DES COURRIERS & AFFECTATIONS ---
            Route::prefix('courriers')->name('courriers.')->group(function () {
                Route::get('/visualiser/{id}', [CourrierController::class, 'visualiserDocument'])->name('visualiser');
                Route::get('/recherche', [CourrierController::class, 'RechercheAffichage'])->name('RechercheAffichage');
                Route::get('/recherche/resultats', [CourrierController::class, 'Recherche'])->name('Recherche');
                Route::get('/archives', [CourrierController::class, 'archives'])->name('archives');
                Route::post('/{courrier}/unlock', [CourrierController::class, 'unlock'])->name('unlock');
            });
            Route::resource('courriers', CourrierController::class);

            // --- RESSOURCES HUMAINES (Présences & Absences) ---
            Route::prefix('presences')->name('presences.')->group(function () {
                // Interfaces de Contrôle & Validation
                Route::get('/validation-hebdo', [PresenceController::class, 'indexValidationHebdo'])->name('validation-hebdo');
                Route::post('/valider-hebdo', [PresenceController::class, 'storeValidationHebdo'])->name('valider-hebdo');
                Route::get('/etat', [PresenceController::class, 'statsPresences'])->name('etat');
                Route::get('/stats', [PresenceController::class, 'stats'])->name('etatperiodique');

                // Interfaces Agent
                Route::get('/mon-pointage', [PresenceController::class, 'monPointage'])->name('monPointage');
                Route::post('/mon-pointage/enregistrer', [PresenceController::class, 'enregistrerPointage'])->name('enregistrerPointage');
                Route::get('/mon-historique', [PresenceController::class, 'monHistorique'])->name('monHistorique');
                Route::get('/liste-filtree', [PresenceController::class, 'listeFiltree'])->name('listeFiltree');
            });

            // Rapports & Ressources (Hors préfixe pour correspondre à vos noms de routes existants)
                Route::get('/rapports/presences/periodique', [PresenceController::class, 'rapport'])->name('rapports.presences.periodique');

                Route::resource('presences', PresenceController::class);
                Route::resource('absences', AbsenceController::class);
                Route::resource('typeabsences', TypeAbsenceController::class);

                // --- GESTION DES AGENTS (Rappel) ---
                Route::prefix('agents')->name('agents.')->group(function () {
                    Route::get('/nouveau', [AgentController::class, 'nouveau'])->name('nouveau');
                    Route::post('/enregistrer', [AgentController::class, 'Enr'])->name('enregistrer');
                    Route::match(['get', 'post'], '/par-service', [AgentServiceController::class, 'listeParService'])->name('par.service');
                });
                Route::resource('agents', AgentController::class)->except(['create', 'store']);

                // --- GESTION DES IMPUTATIONS & RÉPONSES ---
                Route::prefix('imputations')->name('imputations.')->group(function () {
                    Route::get('/mes-imputations', [ImputationController::class, 'mesImputations'])->name('mes_imputations');
                });
                Route::resource('imputations', ImputationController::class);
                Route::post('/reponses/store', [ReponseController::class, 'store'])->name('reponses.store');

                // --- TACHES & ANNONCES ---
                Route::resource('annonces', AnnonceController::class);

                // --- STATISTIQUES ---
                Route::prefix('statistiques')->name('statistiques.')->group(function () {
                    Route::get('/', [StatistiqueController::class, 'index'])->name('index');
                    Route::get('/dashboard', [StatistiqueController::class, 'dashboard'])->name('dashboard');
                });

                // --- GESTION DES POSTS ---
                Route::delete('/post/{id}', [PostController::class, 'destroy'])->middleware('can:supprimer-articles');


    }); // Fin middleware force.password
}); // Fin middleware auth
