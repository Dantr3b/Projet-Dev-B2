<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\ReviewController;

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





Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart/{userId}', [ShoppingCartController::class, 'showCart']);
    Route::post('/cart/{cartId}/add', [ShoppingCartController::class, 'addProduct']);
    Route::delete('/cart/{cartId}/remove/{cartItemId}', [ShoppingCartController::class, 'removeProduct']);
    Route::put('/cart/{cartId}/update/{cartItemId}', [ShoppingCartController::class, 'updateQuantity']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{reviewId}', [ReviewController::class, 'show']);
    Route::get('/reviews/{productId}', [ReviewController::class, 'index']);
    Route::put('/reviews/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{reviewId}', [ReviewController::class, 'destroy']);

});
