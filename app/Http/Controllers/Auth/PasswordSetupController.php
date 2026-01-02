<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordSetupController extends Controller
{
    /**
     * Affiche le formulaire de configuration du mot de passe.
     */
    public function show()
    {
        return view('auth.password-setup');
    }

    /**
     * Enregistre le nouveau mot de passe et active le compte.
     */
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
            'password_changed_at' => now(),  // Optionnel : pour l'historique
        ])->save();

        return redirect()->route('dashboard')
            ->with('status', 'Votre mot de passe a été configuré avec succès.');
    }
}
