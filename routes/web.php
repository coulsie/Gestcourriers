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

// Routes d'authentification standards (Login, Logout, Register)
Auth::routes();

/*
|--------------------------------------------------------------------------
| 2. ESPACE SÉCURISÉ (Authentification Requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

// ... vos autres routes (users, admin, etc.)

    // --- GESTION DES STRUCTURES (Directions & Services) ---
    // Ces routes sont protégées par authentification
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);

    // Si vous avez besoin d'une route spécifique pour lier services et directions
    Route::get('/directions/{direction}/services', [DirectionController::class, 'getServices'])
        ->name('directions.services');


    // --- CONFIGURATION INITIALE & MOT DE PASSE ---
    Route::get('/password/setup', [PasswordSetupController::class, 'show'])->name('password.setup');
    Route::post('/password/setup', [PasswordSetupController::class, 'update'])->name('password.setup.update');

    // --- ADMINISTRATION & GESTION UTILISATEURS (Permissions Spatie) ---
    // On regroupe tout ce qui touche aux utilisateurs sous les permissions manage/voir
    Route::middleware(['can:voir-utilisateurs'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/upgrade', [UserController::class, 'upgradeUser'])->name('users.upgrade');
    });

    Route::middleware(['can:access-admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // --- GESTION DES POSTS ---
    Route::delete('/post/{id}', [PostController::class, 'destroy'])
        ->middleware('can:supprimer-articles');

    // --- STATISTIQUES & VALIDATIONS GLOBALES ---
    Route::prefix('statistiques')->name('statistiques.')->group(function () {
        Route::get('/', [StatistiqueController::class, 'index'])->name('index');
        Route::get('/dashboard', [StatistiqueController::class, 'dashboard'])->name('dashboard');
    });

    Route::post('/reponses/{reponse}/valider', [ReponseController::class, 'valider'])->name('reponses.valider');

    /*
    |--------------------------------------------------------------------------
    | 3. ROUTES AVEC CHANGEMENT DE MOT DE PASSE FORCÉ (Métier)
    |--------------------------------------------------------------------------
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
        // --- ÉTATS & RAPPORTS (Agents par Service) ---
        Route::match(['get', 'post'], '/etat-agents-par-service', [AgentServiceController::class, 'listeParService'])
            ->name('agents.par.service');

        // Si vous avez besoin de la route de recherche associée
        Route::match(['get', 'post'], '/etat-agents-par-service/recherche', [AgentServiceController::class, 'recherche'])
            ->name('agents.par.service.recherche');

        // --- GESTION DES IMPUTATIONS ---
        Route::prefix('imputations')->name('imputations.')->group(function () {
            Route::get('/mes-imputations', [ImputationController::class, 'mesImputations'])->name('mes_imputations');
        });
        Route::resource('imputations', ImputationController::class);
        Route::post('/reponses/store', [ReponseController::class, 'store'])->name('reponses.store');

        // --- GESTION DES COURRIERS & AFFECTATIONS ---
        Route::prefix('courriers')->name('courriers.')->group(function () {
            Route::get('/visualiser/{id}', [CourrierController::class, 'visualiserDocument'])->name('visualiser');
            Route::get('/recherche', [CourrierController::class, 'RechercheAffichage'])->name('RechercheAffichage');
            Route::get('/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('affectation.create');
            Route::post('/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('affectation.store');
            Route::get('/{courrier}/affectation', [CourrierAffectationController::class, 'show'])->name('affectation.show');
        });
        Route::resource('courriers', CourrierController::class);

        // Affectations
        Route::resource('affectations', AffectationController::class);
        Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');

        // --- RESSOURCES HUMAINES ---
        Route::prefix('presences')->name('presences.')->group(function () {
            // Route demandée : Validation hebdomadaire
            Route::get('/validation-hebdo', [PresenceController::class, 'indexValidationHebdo'])->name('validation-hebdo');
            Route::post('/valider-hebdo', [PresenceController::class, 'storeValidationHebdo'])->name('valider-hebdo');

            // Autres routes de présences

            // Ajout de la route Rapport Périodique
            Route::get('/rapports/periodique', [PresenceController::class, 'rapport'])->name('rapports.periodique');
            Route::get('/etat', [PresenceController::class, 'statsPresences'])->name('etat');
            Route::get('/stats', [PresenceController::class, 'stats'])->name('etatperiodique');
        });
        Route::get('/rapports/presences/periodique', [PresenceController::class, 'rapport'])->name('rapports.presences.periodique');

        Route::resource('presences', PresenceController::class);
        Route::resource('presences', PresenceController::class);
        Route::resource('absences', AbsenceController::class);
        Route::resource('typeabsences', TypeAbsenceController::class);

        // --- NOTIFICATIONS & TACHES ---
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/index1', [NotificationTacheController::class, 'index1'])->name('index1');
            Route::get('/{id}/voir', [NotificationTacheController::class, 'showA'])->name('showA');
            Route::post('/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('markAsRead');
        });
        Route::resource('notifications', NotificationTacheController::class)->parameters(['notifications' => 'id_notification']);

        // --- ANNONCES ---
        Route::resource('annonces', AnnonceController::class);

        // --- DIRECTIONS & SERVICES ---
        Route::resource('directions', DirectionController::class);
        Route::resource('services', ServiceController::class);
    });
});
