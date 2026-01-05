<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, AdminController, UserController, ProfileController,
    AgentController, CourrierController, AffectationController,
    CourrierAffectationController, DirectionController, ServiceController,
    PresenceController, AbsenceController, TypeAbsenceController,
    EtatAgentsController, NotificationTacheController,AnnonceController
};

use App\Http\Controllers\Auth\{LoginController,RegisterController,ForgotPasswordController,
ResetPasswordController,VerificationController,ConfirmPasswordController,PasswordSetupController
};


/*
|--------------------------------------------------------------------------
| 1. ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome-login');
});

// Routes d'authentification (générées par laravel/ui)
Auth::routes();

/*
|--------------------------------------------------------------------------
| 2. CONFIGURATION DU MOT DE PASSE (Prioritaire)
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
    Route::get('/dashboard', [AdminController::class, 'index'])->name('home');

    // --- PROFIL UTILISATEUR ---
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::get('/create', [ProfileController::class, 'create'])->name('create');
        Route::post('/', [ProfileController::class, 'store'])->name('store');
        Route::match(['put', 'post'], '/update', [ProfileController::class, 'update'])->name('update');
    });

    // --- ADMINISTRATION (ADMIN ONLY) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
    });

    // --- AGENTS ---
    Route::get('/tableau-de-bord', [AgentController::class, 'dashb'])->name('agent.dashboard');
    Route::get('/agents/nouveau', [AgentController::class, 'nouveau'])->name('agents.nouveau');
    Route::post('/agents/enregistrer', [AgentController::class, 'Enr'])->name('agents.enregistrer');
    Route::resource('agents', AgentController::class);

    // --- COURRIERS & AFFECTATIONS ---
    Route::get('/courriers/RechercheAffichage', [CourrierController::class, 'RechercheAffichage'])->name('courriers.RechercheAffichage');
    Route::resource('courriers', CourrierController::class);
    
    // Affectations imbriquées et spécifiques
    Route::resource('courriers.affectations', AffectationController::class)->shallow();
    Route::get('/courriers/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('courriers.affectation.create');
    Route::post('/courriers/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('courriers.affectation.store');
    
    Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');
    Route::resource('affectations', AffectationController::class);

    // --- STRUCTURE & RH ---
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('presences', PresenceController::class);
    Route::resource('absences', AbsenceController::class);
    Route::resource('typeabsences', TypeAbsenceController::class);

    // --- ÉTATS & RAPPORTS ---
    Route::get('/etats/agents-par-service', [EtatAgentsController::class, 'index'])->name('etats.agents_par_service');
    Route::get('/etats/recherche', [EtatAgentsController::class, 'Recherche'])->name('etats.agents_par_service_recherche');

    // --- NOTIFICATIONS ---
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/index1', [NotificationTacheController::class, 'index1'])->name('index1');
        Route::get('/index2', [NotificationTacheController::class, 'index2'])->name('index2');
        Route::get('/{id}/visualiser', [NotificationTacheController::class, 'visualiserDocument'])->name('visualiser');
        Route::post('/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('markAsRead');
    });
    Route::resource('notifications', NotificationTacheController::class)->parameters([
        'notifications' => 'id_notification'
    ]);

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');