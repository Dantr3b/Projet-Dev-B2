<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/verification-notification",
 *     summary="Envoyer un lien de vérification par e-mail",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=302,
 *         description="Redirection vers la page d'accueil si l'email est vérifié"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="L'utilisateur a déjà vérifié son adresse e-mail"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lien de vérification envoyé avec succès"
 *     ),
 *     security={{"sanctum":{}}}
 * )
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Envoyer un lien de vérification par e-mail.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
