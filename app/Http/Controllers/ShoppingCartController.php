<?php
namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="ShoppingCart",
 *     type="object",
 *     required={"cart_id", "user_id"},
 *     @OA\Property(property="cart_id", type="integer", example=1, description="ID du panier"),
 *     @OA\Property(property="user_id", type="integer", example=5, description="ID de l'utilisateur propriétaire du panier"),
 *     @OA\Property(
 *         property="cartItems",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CartItem"),
 *         description="Liste des articles dans le panier"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-18T12:34:56Z")
 * )
 */


/**
 * @OA\Schema(
 *     schema="CartItem",
 *     type="object",
 *     required={"id", "cart_id", "product_id", "quantity"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID de l'article du panier"),
 *     @OA\Property(property="cart_id", type="integer", example=1, description="ID du panier associé"),
 *     @OA\Property(property="product_id", type="integer", example=42, description="ID du produit"),
 *     @OA\Property(property="quantity", type="integer", example=3, description="Quantité du produit dans le panier"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-18T12:34:56Z")
 * )
 */

class ShoppingCartController extends Controller
{
    
    

    /**
 * @OA\Get(
 *     path="/api/cart/{userId}",
 *     summary="Obtenir le panier de l'utilisateur",
 *     tags={"Panier"},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des articles du panier",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/CartItem")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Panier non trouvé"
 *     )
 * )
 */
    public function showCart($userId)
    {
        $cart = ShoppingCart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['message' => 'Panier vide ou non trouvé'], 404);
        }

        return response()->json($cart->cartItems);
    }

    /**
     * @OA\Post(
     *     path="/addproduct",
     *     summary="Ajouter un produit au panier",
     *     description="This endpoint allows the user to add a new product to the inventory.",
     *     operationId="addProduct",
     *     tags={"Panier"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_id", type="int", description="id du produit"),
     *            @OA\Property(property="quantity", type="int", description="Quantité du produit à ajouter")
     *              )  
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product added successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while adding the product")
     *         )
     *     )
     * )
     */
    public function addProduct(Request $request, $cartId)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = ShoppingCart::findOrFail($cartId);

        $cartItem = new CartItem([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        $cart->cartItems()->save($cartItem);

        return response()->json(['message' => 'Produit ajouté au panier'], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/{cartId}/item/{cartItemId}",
     *     summary="Supprimer un produit du panier",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="cartId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="cartItemId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit supprimé du panier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produit supprimé du panier")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Panier ou article non trouvé"
     *     )
     * )
     */
    public function removeProduct($cartId, $cartItemId)
    {
        $cart = ShoppingCart::findOrFail($cartId);
        $cartItem = $cart->cartItems()->findOrFail($cartItemId);

        $cartItem->delete();

        return response()->json(['message' => 'Produit supprimé du panier'], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/cart/{cartId}/item/{cartItemId}",
     *     summary="Mettre à jour la quantité d'un produit dans le panier",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="cartId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="cartItemId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="quantity", type="integer", description="Nouvelle quantité du produit")
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
     *         description="Panier ou article non trouvé"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides"
     *     )
     * )
     */
    public function updateQuantity(Request $request, $cartId, $cartItemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = ShoppingCart::findOrFail($cartId);
        $cartItem = $cart->cartItems()->findOrFail($cartItemId);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['message' => 'Quantité mise à jour'], 200);
    }
}
