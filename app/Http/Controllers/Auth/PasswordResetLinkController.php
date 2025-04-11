<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/forgot-password",
 *     summary="Demander un lien de réinitialisation de mot de passe",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lien de réinitialisation de mot de passe envoyé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Nous avons envoyé un lien de réinitialisation de mot de passe à votre adresse email.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur lors de l'envoi du lien de réinitialisation",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Le lien de réinitialisation n'a pas pu être envoyé. Veuillez vérifier l'adresse email.")
 *         )
 *     )
 * )
 */

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
