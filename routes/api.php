<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
/**
 * @OA\PathItem(
 *     path="/api",
 *     summary="Base path for API routes",
 *     description="This is the base path for all API routes"
 * )
 */

/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Enregistrer un nouvel utilisateur",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username","email","password","password_confirmation"},
 *             @OA\Property(property="username", type="string", example="JohnDoe"),
 *             @OA\Property(property="email", type="string", example="test@email.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur enregistré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Utilisateur enregistré avec succès"),
 *             @OA\Property(property="access_token", type="string", example="token123"),
 *             @OA\Property(property="token_type", type="string", example="Bearer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur de validation")
 *         )
 *     )
 * )
 */

// Définition de la route de l'enregistrement
Route::post('/register', [RegisteredUserController::class, 'store']);

// Définition de la route de connexion
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Déconnexion de l'utilisateur
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Exemple de route protégée par Sanctum
Route::middleware('auth:sanctum')->get('/profile', function (\Illuminate\Http\Request $request) {
    return response()->json($request->user());
});
