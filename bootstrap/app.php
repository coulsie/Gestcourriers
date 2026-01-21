<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Définir les redirections automatiques (CORRIGE VOTRE PROBLÈME)
        $middleware->redirectTo(
            guests: '/login', // Redirige les non-connectés ici
            users: '/home'    // Redirige les connectés ici après le login
        );

        // 2. Désactivation du CSRF sur le login (déjà présent dans votre code)
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout'
        ]);

        // 3. Enregistrement de l'alias de votre middleware (déjà présent)
        $middleware->alias([
            'force.password' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
