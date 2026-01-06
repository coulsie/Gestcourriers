<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Importation des contrôleurs (Regroupés)
use App\Http\Controllers\{
    HomeController, AdminController, UserController, ProfileController,
    AgentController, CourrierController, AffectationController,
    CourrierAffectationController, DirectionController, ServiceController,
    PresenceController, AbsenceController, TypeAbsenceController,
    EtatAgentsController, NotificationTacheController, AnnonceController,
    ReponseNotificationController, AgentServiceController
};

use App\Http\Controllers\Auth\{
    LoginController, RegisterController, ForgotPasswordController,
    ResetPasswordController, VerificationController, ConfirmPasswordController,
    PasswordSetupController
};

/*
|--------------------------------------------------------------------------
| 1. ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome-login');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| 2. CONFIGURATION DU MOT DE PASSE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/password/setup', [PasswordSetupController::class, 'show'])->name('password.setup');
    Route::post('/password/setup', [PasswordSetupController::class, 'update'])->name('password.setup.update');
});

/*
|--------------------------------------------------------------------------
| 3. ROUTES PROTÉGÉES (Auth + Force Password)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'force.password'])->group(function () {

    // --- ACCUEIL & DASHBOARD ---
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    // --- PROFIL ---
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::match(['put', 'post'], '/update', [ProfileController::class, 'update'])->name('update');
    });
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    // --- ADMINISTRATION ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
    });

    // --- AGENTS ---
    Route::get('/tableau-de-bord', [AgentController::class, 'dashb'])->name('agent.dashboard');
    Route::get('/agents/nouveau', [AgentController::class, 'nouveau'])->name('agents.nouveau');
    Route::post('/agents/enregistrer', [AgentController::class, 'Enr'])->name('agents.enregistrer');
    Route::resource('agents', AgentController::class);

    // --- RH : PRÉSENCES & STATISTIQUES ---
    // Note : Route placée AVANT le resource pour éviter les conflits 404
    Route::get('/presences/etat', [PresenceController::class, 'statsPresences'])->name('presences.etat');
    Route::resource('presences', PresenceController::class);
    Route::get('/presences/stats', [PresenceController::class, 'stats'])->name('presences.etatperiodique');

    Route::resource('absences', AbsenceController::class);
    Route::resource('typeabsences', TypeAbsenceController::class);

    // --- TypeAbsenceController Edit Route Fix ---
    Route::get('/typeabsences/{id}/edit', [TypeAbsenceController::class, 'edit'])->name('typeabsences.edit');



    // --- COURRIERS & AFFECTATIONS ---
    Route::get('/courriers/recherche', [CourrierController::class, 'RechercheAffichage'])->name('courriers.RechercheAffichage');
    Route::resource('courriers', CourrierController::class);
    Route::resource('courriers.affectations', AffectationController::class)->shallow();
    Route::get('/courriers/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('courriers.affectation.create');
    Route::post('/courriers/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('courriers.affectation.store');
    Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');
    Route::resource('affectations', AffectationController::class);
    Route::get('/courriers/{courrier}/affectation', [CourrierAffectationController::class, 'show'])
    ->name('courriers.affectation.show');
    // --- ÉTATS & RAPPORTS ---
    Route::get('/etats/agents-par-service', [EtatAgentsController::class, 'index'])->name('etats.agents_par_service');
    Route::get('/etats/recherche', [EtatAgentsController::class, 'Recherche'])->name('etats.agents_par_service_recherche');

    Route::match(['get', 'post'], '/etat-agents-par-service', [AgentServiceController::class, 'listeParService'])->name('agents.par.service');
    Route::match(['get', 'post'], '/etat-agents-par-service/recherche', [AgentServiceController::class, 'recherche'])->name('agents.par.service.recherche');

    // --- NOTIFICATIONS & RÉPONSES ---
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/index1', [NotificationTacheController::class, 'index1'])->name('index1');
        Route::get('/index2', [NotificationTacheController::class, 'index2'])->name('index2');
        Route::get('/index3', [NotificationTacheController::class, 'index3'])->name('index3');
        Route::get('/{id}/visualiser', [NotificationTacheController::class, 'visualiserDocument'])->name('visualiser');
        Route::post('/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('markAsRead');
    });
    Route::resource('notifications', NotificationTacheController::class)->parameters(['notifications' => 'id_notification']);

    Route::prefix('reponses')->name('reponses.')->group(function () {
        Route::get('/create/{id_notification}', [ReponseNotificationController::class, 'create'])->name('create');
        Route::post('/store', [ReponseNotificationController::class, 'store'])->name('store');
    });

    // --- ANNONCES & STRUCTURE ---
    Route::resource('annonces', AnnonceController::class);
    Route::get('/annonces/{annonce}/download', [AnnonceController::class, 'downloadAttachment'])->name('annonces.downloadAttachment');
    Route::post('/annonces/{annonce}/read', [AnnonceController::class, 'markAsRead'])->name('annonces.markAsRead');

    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
});
