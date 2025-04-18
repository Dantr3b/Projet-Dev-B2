<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Wishlist",
 *     type="object",
 *     required={"user_id", "name"},
 *     @OA\Property(property="wishlist_id", type="integer", example=1, description="ID de la wishlist"),
 *     @OA\Property(property="user_id", type="integer", example=5, description="ID de l'utilisateur propriétaire de la wishlist"),
 *     @OA\Property(property="name", type="string", example="Wishlist de Noël", description="Nom de la wishlist"),
 *     @OA\Property(
 *         property="wishlistItems",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/WishlistItem"),
 *         description="Liste des articles dans la wishlist"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-18T12:34:56Z")
 * )
 */

/**
 * @OA\Schema(
 *     schema="WishlistItem",
 *     type="object",
 *     required={"wishlist_id", "product_id", "quantity"},
 *     @OA\Property(property="wishlist_item_id", type="integer", example=1, description="ID de l'élément de la wishlist"),
 *     @OA\Property(property="wishlist_id", type="integer", example=1, description="ID de la wishlist associée"),
 *     @OA\Property(property="product_id", type="integer", example=42, description="ID du produit"),
 *     @OA\Property(property="quantity", type="integer", example=3, description="Quantité du produit dans la wishlist"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-18T12:34:56Z")
 * )
 */
class WishlistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/wishlist/{userId}",
     *     summary="Obtenir la wishlist d'un utilisateur",
     *     tags={"Wishlist"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des articles dans la wishlist",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/WishlistItem")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wishlist non trouvée"
     *     )
     * )
     */
    public function show($userId)
    {
        $wishlist = Wishlist::where('user_id', $userId)->first();

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist non trouvée'], 404);
        }

        return response()->json($wishlist->items);
    }

    /**
     * @OA\Post(
     *     path="/api/wishlist/{wishlistId}/addproduct",
     *     summary="Ajouter un produit à la wishlist",
     *     tags={"Wishlist"},
     *     @OA\Parameter(
     *         name="wishlistId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=42, description="ID du produit"),
     *             @OA\Property(property="quantity", type="integer", example=3, description="Quantité du produit")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit ajouté à la wishlist",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produit ajouté à la wishlist")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wishlist non trouvée"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function addProduct(Request $request, $wishlistId)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $wishlist = Wishlist::findOrFail($wishlistId);

        $wishlistItem = new WishlistItem([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        $wishlist->items()->save($wishlistItem);

        return response()->json(['message' => 'Produit ajouté à la wishlist'], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/wishlist/{wishlistId}/item/{wishlistItemId}",
     *     summary="Supprimer un produit de la wishlist",
     *     tags={"Wishlist"},
     *     @OA\Parameter(
     *         name="wishlistId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="wishlistItemId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit supprimé de la wishlist",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produit supprimé de la wishlist")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wishlist ou produit non trouvé"
     *     )
     * )
     */
    public function removeProduct($wishlistId, $wishlistItemId)
    {
        $wishlist = Wishlist::findOrFail($wishlistId);
        $wishlistItem = $wishlist->items()->findOrFail($wishlistItemId);

        $wishlistItem->delete();

        return response()->json(['message' => 'Produit supprimé de la wishlist'], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/wishlist/{wishlistId}/item/{wishlistItemId}",
     *     summary="Mettre à jour la quantité d'un produit dans la wishlist",
     *     tags={"Wishlist"},
     *     @OA\Parameter(
     *         name="wishlistId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="wishlistItemId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=5, description="Nouvelle quantité du produit")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantité mise à jour",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Quantité mise à jour")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wishlist ou produit non trouvé"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides"
     *     )
     * )
     */
    public function updateQuantity(Request $request, $wishlistId, $wishlistItemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $wishlist = Wishlist::findOrFail($wishlistId);
        $wishlistItem = $wishlist->items()->findOrFail($wishlistItemId);

        $wishlistItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['message' => 'Quantité mise à jour'], 200);
    }
}
