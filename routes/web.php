<?php

use Illuminate\Support\Facades\{Auth, Route};
use App\Http\Controllers\{
    HomeController, AdminController, UserController, ProfileController,
    AgentController, CourrierController, AffectationController,
    CourrierAffectationController, DirectionController, ServiceController,
    PresenceController, AbsenceController, TypeAbsenceController,
    EtatAgentsController, NotificationTacheController, AnnonceController,
    ReponseNotificationController, AgentServiceController, ImputationController,StatistiqueController,
    ReponseController,PasswordController
};
use App\Http\Controllers\Auth\PasswordSetupController;

/*
|--------------------------------------------------------------------------
| 1. ACCÈS PUBLICS & AUTHENTIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome-login');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| 2. ESPACE SÉCURISÉ (Authentification Requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- CONFIGURATION INITIALE & MOT DE PASSE ---
    Route::get('/password/setup', [PasswordSetupController::class, 'show'])->name('password.setup');
    Route::post('/password/setup', [PasswordSetupController::class, 'update'])->name('password.setup.update');

    // Route pour les statistiques générales des courriers
    Route::get('/statistiques', [StatistiqueController::class, 'index'])
        ->name('statistiques.index');

    // Route pour le tableau de bord détaillé (Imputations, Réponses, Performance)
    Route::get('/statistiques/dashboard', [StatistiqueController::class, 'dashboard'])
        ->name('statistiques.dashboard');

    Route::post('/reponses/{reponse}/valider', [ReponseController::class, 'valider'])
        ->name('reponses.valider');
         // --- GESTION DES UTILISATEURS (Accès Restreint) ---
    // On protège ces routes pour que seuls les hauts gradés puissent gérer les comptes


    /*
    |----------------------------------------------------------------------
    | 3. ROUTES AVEC CHANGEMENT DE MOT DE PASSE FORCÉ
    |----------------------------------------------------------------------
    */
    Route::middleware(['force.password'])->group(function () {

        // --- ACCUEIL & DASHBOARDS ---
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/tableau-de-bord', [AgentController::class, 'dashb'])->name('agent.dashboard');

        // --- PROFIL UTILISATEUR ---
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::get('/create', [ProfileController::class, 'create'])->name('create');
            Route::match(['put', 'post'], '/update', [ProfileController::class, 'update'])->name('update');
        });

        Route::middleware(['can:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
        // Cette ligne crée automatiquement :
        // users.index (Liste), users.create (Formulaire), users.store (Enregistrement)
        // users.edit (Modif), users.update, users.destroy (Suppression)
        });


         
            Route::post('/users/{user}/upgrade', [UserController::class, 'upgradeUser'])
                ->name('users.upgrade')
                ->middleware('auth');
        // --- ADMINISTRATION (ADMIN ONLY) ---


        // --- GESTION DES AGENTS ---
        Route::prefix('agents')->group(function () {
            Route::get('/nouveau', [AgentController::class, 'nouveau'])->name('agents.nouveau');
            Route::post('/enregistrer', [AgentController::class, 'Enr'])->name('agents.enregistrer');
        });
        Route::resource('agents', AgentController::class);

        // --- GESTION DES IMPUTATIONS (Nouveauté 2026) ---
        Route::prefix('imputations')->name('imputations.')->group(function () {
            Route::get('/mes-imputations', [ImputationController::class, 'mesImputations'])->name('mes_imputations');
        });
        Route::resource('imputations', ImputationController::class);
        Route::post('/reponses/store', [ReponseController::class, 'store'])->name('reponses.store');



        // --- GESTION DES COURRIERS & AFFECTATIONS ---
        Route::prefix('courriers')->name('courriers.')->group(function () {
            Route::get('/visualiser/{id}', [CourrierController::class, 'visualiserDocument'])->name('visualiser');
            Route::get('/recherche', [CourrierController::class, 'RechercheAffichage'])->name('RechercheAffichage');

            // Affectations directes liées au Courrier
            Route::get('/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('affectation.create');
            Route::post('/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('affectation.store');
            Route::get('/{courrier}/affectation', [CourrierAffectationController::class, 'show'])->name('affectation.show');
        });
        Route::resource('courriers', CourrierController::class);

        // Affectations Générales
        Route::resource('courriers.affectations', AffectationController::class)->shallow();
        Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');
        Route::resource('affectations', AffectationController::class);

        // --- RESSOURCES HUMAINES (Présences & Absences) ---
        Route::prefix('presences')->name('presences.')->group(function () {
            Route::get('/validation-hebdo', [PresenceController::class, 'indexValidationHebdo'])->name('validation-hebdo');
            Route::post('/valider-hebdo', [PresenceController::class, 'storeValidationHebdo'])->name('valider-hebdo');
            Route::get('/etat', [PresenceController::class, 'statsPresences'])->name('etat');
            Route::get('/stats', [PresenceController::class, 'stats'])->name('etatperiodique');
        });
        Route::get('/rapports/presences/periodique', [PresenceController::class, 'rapport'])->name('rapports.presences.periodique');

        Route::resource('presences', PresenceController::class);
        Route::resource('absences', AbsenceController::class);
        Route::resource('typeabsences', TypeAbsenceController::class);
        Route::get('/typeabsences/{id}/edit', [TypeAbsenceController::class, 'edit'])->name('typeabsences.edit');

        // --- ÉTATS & RAPPORTS STATISTIQUES ---
        Route::prefix('etats')->name('etats.')->group(function () {
            Route::get('/agents-par-service', [EtatAgentsController::class, 'index'])->name('agents_par_service');
            Route::get('/recherche', [EtatAgentsController::class, 'Recherche'])->name('agents_par_service_recherche');
        });

        Route::prefix('etat-agents-par-service')->name('agents.par.service')->group(function () {
            Route::match(['get', 'post'], '/', [AgentServiceController::class, 'listeParService']);
            Route::match(['get', 'post'], '/recherche', [AgentServiceController::class, 'recherche'])->name('.recherche');
        });

        // --- NOTIFICATIONS & TACHES ---
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/index1', [NotificationTacheController::class, 'index1'])->name('index1');
            Route::get('/index2', [NotificationTacheController::class, 'index2'])->name('index2');
            Route::get('/index3', [NotificationTacheController::class, 'index3'])->name('index3');
            Route::get('/{id}/voir', [NotificationTacheController::class, 'showA'])->name('showA');
            Route::get('/{id}/visualiser', [NotificationTacheController::class, 'visualiserDocument'])->name('visualiser');
            Route::post('/transmettre/{id}', [NotificationTacheController::class, 'transmettre'])->name('transmettre');
            Route::post('/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('markAsRead');
        });
        Route::resource('notifications', NotificationTacheController::class)->parameters(['notifications' => 'id_notification']);

        // --- RÉPONSES AUX NOTIFICATIONS ---
        Route::prefix('reponsesNotifications')->name('reponsesnotifications.')->group(function () {
            Route::get('/create/{id_notification}', [ReponseNotificationController::class, 'create'])->name('create');
            Route::post('/store', [ReponseNotificationController::class, 'store'])->name('store');
        });

        // --- ANNONCES ---
        Route::resource('annonces', AnnonceController::class);

    }); // Fin Middleware force.password
}); // Fin Middleware auth
