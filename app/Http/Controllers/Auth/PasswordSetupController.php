<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// --- IMPORTS OBLIGATOIRES POUR FIXER LES ERREURS ---
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// -------------------------------------------
class PasswordSetupController extends Controller
{
    public function update(Request $request)
    {
        // Validation stricte (standards 2026)
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        // Mise à jour sécurisée
        $user->forceFill([
            'password' => Hash::make($request->password),
            'must_change_password' => false, // Désactive la redirection forcée
           
        ])->save();

        return redirect()->route('home')
            ->with('status', 'Votre mot de passe a été configuré avec succès.');
    }

    public function show()
        {
            // Renvoie vers le fichier resources/views/auth/password-setup.blade.php
            return view('auth.password-setup');
        }

}

