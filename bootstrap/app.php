<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Controllers\Auth\PasswordSetupController;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // C'est ici que vous ajoutez votre alias
        $middleware->alias([
            'force.password' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);
    }) // Ne pas mettre de point-virgule ici si vous continuez l'enchaînement
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create(); // Le point-virgule final doit être ici
