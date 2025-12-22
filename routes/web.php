<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourrierAffectationController;
use App\Models\Courrier;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\TypeAbsenceController;
use App\Http\Controllers\EtatAgentsController;
use App\Http\Controllers\EtatAgentsController1;
use App\Http\Controllers\NotificationTacheController;
use App\Http\Controllers\AdminController;
// ... autres routes




Route::get('/', function () {
    return view('welcome-login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::resource('courriers', CourrierController::class);
Route::resource('courriers.affectations', AffectationController::class)->shallow();



// Routes principales pour les courriers (index, show, create, store, edit, update, destroy)
Route::resource('courriers', CourrierController::class);

//
// Fichier : routes/web.php


// Cette seule ligne gère toutes les routes (index, create, store, show, edit, update, destroy)
Route::resource('users', UserController::class);


Route::get('/affectation/create', function () {
    $courriers = Courrier::all();
    return view('courriers.affectation.create', compact('courriers'));
});



Route::get('/courriers/{id}/affecter', [CourrierAffectationController::class, 'create'])->name('courriers.affectation.create');
Route::post('/courriers/{id}/affecter', [CourrierAffectationController::class, 'store'])->name('courriers.affectation.store');



// Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
// Route::get('/affectations/create', [AffectationController::class, 'create'])->name('affectations.create');
// Route::post('/affectations', [AffectationController::class, 'store'])->name('affectations.store');


Route::resource('affectation', AffectationController::class);

// Add a route for the custom status update method
Route::put('/affectations/{affectation}/status', [AffectationController::class, 'updateStatus'])->name('affectations.updateStatus');




Route::resource('directions', DirectionController::class);


// ... autres routes ...

Route::resource('services', ServiceController::class);


Route::resource('agents', AgentController::class);



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

route::resource('affectations', AffectationController::class);


Route::resource('presences', PresenceController::class);

Route::resource('absences', AbsenceController::class);

Route::resource('typeabsences', TypeAbsenceController::class);

Route::get('/courriers/affectation', [CourrierAffectationController::class, 'index'])->name('courriers.affectation.index');
Route::post('/courriers/affectation', [CourrierAffectationController::class, 'store'])->name('courriers.affectation.store');

Route::resource('courriers.affectation', CourrierAffectationController::class)->except(['index', 'create', 'store', 'show', 'destroy']);
// Nous nous concentrons ici uniquement sur 'edit' et 'update'

Route::get('/etats.agents_par_service', [EtatAgentsController::class, 'index'])->name('etats.agents_par_service');


// Utilisez cette route pour gérer à la fois l'affichage initial et la recherche POST/GET
Route::get('/etats.agents_par_service_recherche', [EtatAgentsController::class, 'Recherche'])->name('etats.agents_par_service_recherche');

Route::get('/courriers.RechercheAffichage', [CourrierController::class, 'RechercheAffichage'])->name('courriers.RechercheAffichage');

Route::resource('typeabsences', TypeAbsenceController::class);

Route::middleware(['auth'])->group(function () {
    // Routes de ressources standard
    Route::resource('notifications', NotificationTacheController::class)->parameters([
        'notifications' => 'id_notification' // Utilise id_notification au lieu de {notification} dans l'URL
    ]);

    // Route supplémentaire pour marquer comme lue
    Route::post('notifications/{id_notification}/read', [NotificationTacheController::class, 'markAsRead'])->name('notifications.markAsRead');
});
// routes/web.php
Route::get('/notifications/{id}/visualiser', [NotificationTacheController::class, 'visualiserDocument'])->name('notifications.visualiser');


Route::get('/courriers/visualiser', [CourrierController::class, 'visualiserDocument'])->name('courriers.visualiser');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index']);
    // Toutes les routes ici sont réservées aux admins
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // La route pour afficher le formulaire de création
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');

    // La route pour enregistrer les données (utilisée par le formulaire)
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
});

// Accès Agent
Route::middleware(['auth'])->group(function () {
    Route::get('/tableau-de-bord', [AgentController::class, 'dashb'])->name('agent.dashboard');
});

// Route pour générer et télécharger le PDF
Route::get('/notifications/pdf', [NotificationtacheController::class, 'genererPdf'])->name('notifications.index_pdf');
Route::get('/notifications/{id}/pdf', [NotificationTacheController::class, 'visualiserDocument'])->name('notifications.index_pdf');
