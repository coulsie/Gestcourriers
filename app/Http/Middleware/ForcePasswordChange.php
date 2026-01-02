<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle(Request $request, Closure $next)
        
        {
            // On vérifie si l'utilisateur est connecté via la Façade Auth
            if (Auth::check() && Auth::user()->must_change_password) {
                
                if (!$request->is('password/setup*')) {
                    return redirect()->route('password.setup');
                }
            }

            return $next($request);
        }
}
