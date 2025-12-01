<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\AffectationController;



Route::get('/', function () {
    return view('welcome-login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('courriers', CourrierController::class);
Route::resource('courriers.affectations', AffectationController::class)->shallow();
    


// Routes principales pour les courriers (index, show, create, store, edit, update, destroy)
Route::resource('courriers', CourrierController::class);

// Routes imbriquées pour les affectations d'un courrier spécifique
Route::prefix('courriers/{courrier}')->name('courriers.')->group(function () {
    // GET /courriers/{courrier}/affectations           -> index (historique)
    // GET /courriers/{courrier}/affectations/create    -> create (formulaire de nouvelle affectation)
    // POST /courriers/{courrier}/affectations          -> store (enregistrement de l'affectation)
Route::resource('affectations', AffectationController::class)->only([
        'index', 'create', 'store'
    ]);
});

// Routes pour gérer une affectation individuelle (édition/mise à jour d'une entrée spécifique)
// Ces routes gèrent l'ID de l'affectation seule, pas besoin de l'ID du courrier parent ici.
Route::resource('affectations', AffectationController::class)->only([
    'edit', 'update', 'destroy' // show n'est souvent pas nécessaire si index suffit
]);

// Route personnalisée si vous avez besoin d'une action spécifique (comme marquer comme traité rapidement)
Route::put('/affectations/{affectation}/update-status', [AffectationController::class, 'updateStatus'])->name('affectations.update_status');

