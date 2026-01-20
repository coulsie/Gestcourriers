<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange 
{
    /**
     * Gère la requête entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si l'utilisateur est connecté ET doit changer son MDP
        // ET qu'il n'est pas déjà sur la page de changement de MDP
        if ($user && $user->must_change_password && !$request->is('password/setup*')) {
            return redirect()->route('password.setup');
        }

        return $next($request);
    }
}
