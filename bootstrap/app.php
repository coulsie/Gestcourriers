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

        // 1. Définir les redirections automatiques
        $middleware->redirectTo(
            guests: '/login',
            users: '/home'
        );

        // 2. Désactivation du CSRF sur le login
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout'
        ]);

        // 3. Enregistrement des alias (MODIFIÉ ICI)
        $middleware->alias([
            'force.password' => \App\Http\Middleware\ForcePasswordChange::class,
            // AJOUT DES ALIAS SPATIE CI-DESSOUS
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

