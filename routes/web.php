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


Route::get('/', function () {
    return view('welcome-login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('courriers', CourrierController::class);
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


Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
Route::get('/affectations/create', [AffectationController::class, 'create'])->name('affectations.create');



Route::resource('directions', DirectionController::class);


// ... autres routes ...

Route::resource('services', ServiceController::class);