<?php

use Illuminate\Support\Facades\{Auth, Route};
use App\Http\Controllers\{
    HomeController, AdminController, UserController, ProfileController,
    AgentController, CourrierController, AffectationController,
    CourrierAffectationController, DirectionController, ServiceController,
    PresenceController, AbsenceController, TypeAbsenceController,
    EtatAgentsController, NotificationTacheController, AnnonceController,
    ReponseNotificationController, AgentServiceController, ImputationController,
    StatistiqueController, ReponseController, PostController
};
use App\Http\Controllers\Auth\PasswordSetupController;

/*
|--------------------------------------------------------------------------
| 1. ACCÈS PUBLICS
|--------------------------------------------------------------------------
*/





Route::get('/', function () {
    return view('welcome-login');
})->middleware('guest'); // Redirige vers home si déjà connecté

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();


/*
|--------------------------------------------------------------------------
| 2. ESPACE SÉCURISÉ (Authentification Requise uniquement)
|--------------------------------------------------------------------------
| Ces routes sont accessibles même si le mot de passe n'est pas encore changé.
*/
Route::middleware(['auth'])->group(function () {

    // --- CONFIGURATION INITIALE & MOT DE PASSE (CRITIQUE) ---
    // Ces routes doivent être HORS du middleware 'force.password'
    Route::get('/password/setup', [PasswordSetupController::class, 'show'])->name('password.setup');
    Route::post('/password/setup', [PasswordSetupController::class, 'update'])->name('password.setup.update');

    // --- ADMINISTRATION & UTILISATEURS ---
    Route::middleware(['can:manage-users'])->group(function () {
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset_password');
    });

    Route::middleware(['can:voir-utilisateurs'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/upgrade', [UserController::class, 'upgradeUser'])->name('users.upgrade');
    });

    Route::middleware(['can:access-admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // --- GESTION DES STRUCTURES ---
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
    Route::get('/directions/{direction}/services', [DirectionController::class, 'getServices'])->name('directions.services');

    Route::get('/mon-pointage', [PresenceController::class, 'monPointage'])->name('presences.monPointage');
    Route::post('/mon-pointage/enregistrer', [PresenceController::class, 'enregistrerPointage'])->name('presences.enregistrerPointage');
    Route::get('/mon-historique', [PresenceController::class, 'monHistorique'])->name('presences.monHistorique');


    /*
    |--------------------------------------------------------------------------
    | 3. ESPACE MÉTIER (Authentification + Changement de mot de passe forcé)
    |--------------------------------------------------------------------------
    | Toutes les routes ci-dessous redirigeront vers /password/setup si nécessaire.
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

        // --- GESTION DES AGENTS ---
        Route::prefix('agents')->group(function () {
            Route::get('/nouveau', [AgentController::class, 'nouveau'])->name('agents.nouveau');
            Route::post('/enregistrer', [AgentController::class, 'Enr'])->name('agents.enregistrer');
        });
        Route::resource('agents', AgentController::class);

        // États & Rapports Agents
        Route::match(['get', 'post'], '/etat-agents-par-service', [AgentServiceController::class, 'listeParService'])->name('agents.par.service');
        Route::match(['get', 'post'], '/etat-agents-par-service/recherche', [AgentServiceController::class, 'recherche'])->name('agents.par.service.recherche');

        // --- GESTION DES IMPUTATIONS & RÉPONSES ---
        Route::prefix('imputations')->name('imputations.')->group(function () {
            Route::get('/mes-imputations', [ImputationController::class, 'mesImputations'])->name('mes_imputations');
        });
        Route::resource('imputations', ImputationController::class);
        Route::post('/reponses/store', [ReponseController::class, 'store'])->name('reponses.store');
        Route::post('/reponses/{reponse}/valider', [ReponseController::class, 'valider'])->name('reponses.valider');

        // --- GESTION DES COURRIERS & AFFECTATIONS ---
        Route::prefix('courriers')->name('courriers.')->group(function () {
            Route::get('/visualiser/{id}', [CourrierController::class, 'visualiserDocument'])->name('visualiser');
            Route::get('/recherche', [CourrierController::class, 'RechercheAffichage'])->name('RechercheAffichage');
            Route::get('/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('affectation.create');
            Route::post('/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('affectation.store');
            Route::get('/{courrier}/affectation', [CourrierAffectationController::class, 'show'])->name('affectation.show');
        });
        Route::resource('courriers', CourrierController::class);

        Route::resource('affectations', AffectationController::class);
        Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');

        // --- RESSOURCES HUMAINES (Présences & Absences) ---
        Route::prefix('presences')->name('presences.')->group(function () {
            Route::get('/validation-hebdo', [PresenceController::class, 'indexValidationHebdo'])->name('validation-hebdo');
            Route::post('/valider-hebdo', [PresenceController::class, 'storeValidationHebdo'])->name('valider-hebdo');
            Route::get('/rapports/periodique', [PresenceController::class, 'rapport'])->name('rapports.periodique');
            Route::get('/etat', [PresenceController::class, 'statsPresences'])->name('etat');
            Route::get('/stats', [PresenceController::class, 'stats'])->name('etatperiodique');
        });
        Route::get('/rapports/presences/periodique', [PresenceController::class, 'rapport'])->name('rapports.presences.periodique');

        // Les routes pour l'interface Agent





        Route::resource('presences', PresenceController::class);
        Route::resource('absences', AbsenceController::class);
        Route::resource('typeabsences', TypeAbsenceController::class);

        // --- NOTIFICATIONS, TACHES & ANNONCES ---
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/index1', [NotificationTacheController::class, 'index1'])->name('index1');
            Route::get('/{id}/voir', [NotificationTacheController::class, 'showA'])->name('showA');
            // Route mark as read supposée (en fonction de votre prefix)
            Route::post('/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('read');
        });
        Route::resource('notifications', NotificationTacheController::class);
        Route::resource('annonces', AnnonceController::class);

        // --- STATISTIQUES ---
        Route::prefix('statistiques')->name('statistiques.')->group(function () {
            Route::get('/', [StatistiqueController::class, 'index'])->name('index');
            Route::get('/dashboard', [StatistiqueController::class, 'dashboard'])->name('dashboard');
        });

        // --- GESTION DES POSTS ---
        Route::delete('/post/{id}', [PostController::class, 'destroy'])->middleware('can:supprimer-articles');

    }); // Fin du middleware force.password
}); // Fin du middleware auth
