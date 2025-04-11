<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @OA\Post(
 *     path="/api/confirm-password",
 *     summary="Confirmer le mot de passe de l'utilisateur",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"password"},
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=302,
 *         description="Redirection vers la page d'accueil après confirmation"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Le mot de passe est incorrect"
 *     ),
 *     security={{"sanctum":{}}}
 * )
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation de l'email et du mot de passe
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Stocke la confirmation du mot de passe
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirection vers la page de destination
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
