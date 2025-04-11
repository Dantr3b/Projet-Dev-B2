<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/**
 * @OA\PathItem(
 *     path="/api",
 *     summary="Base path for API routes",
 *     description="This is the base path for all API routes"
 * )
 */



// Définition de la route de l'enregistrement
Route::post('/register', [RegisteredUserController::class, 'store']);

// Définition de la route de connexion
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Déconnexion de l'utilisateur
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Routes pour le CRUD des produits
Route::apiResource('products', ProductController::class);

// Exemple de route protégée par Sanctum
Route::middleware('auth:sanctum')->get('/profile', function (\Illuminate\Http\Request $request) {
    return response()->json($request->user());
});
