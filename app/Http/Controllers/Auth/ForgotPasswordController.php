<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Affiche le formulaire de demande de lien de réinitialisation.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoie le lien de réinitialisation à l'utilisateur.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validation de l'email
        $request->validate(['email' => 'required|email']);

        // 2. Envoi du lien via le "Password Broker" de Laravel
        // Cette méthode gère la génération du token et l'envoi du mail automatiquement
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Retour selon le succès ou l'échec
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // Si l'email n'existe pas ou erreur, on renvoie une exception de validation
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
