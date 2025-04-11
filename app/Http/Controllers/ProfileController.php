<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * @OA\Schema(
 *     schema="Profile",
 *     type="object",
 *     required={"name", "email"},
 *     @OA\Property(property="user_id", type="integer", description="ID de l'utilisateur"),
 *     @OA\Property(property="username", type="string", description="Nom de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur"),
 *     @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création du profil"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour du profil")
 * )
 */


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
