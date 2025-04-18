<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use Illuminate\Http\Request;



/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     type="object",
 *     required={"id", "order_id", "product_id", "quantity", "price"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=10),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="price", type="number", format="decimal", example=99.99)
 * )
 */

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders/{orderId}",
     *     summary="Obtenir une commande avec ses articles et informations d'expédition",
     *     tags={"Commandes"},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commande avec ses articles et informations d'expédition",
     *         
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */
    public function show($orderId)
    {
        $order = Order::with('orderItems', 'shipping')->findOrFail($orderId);
        return response()->json($order);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Créer une nouvelle commande",
     *     tags={"Commandes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "order_date", "status", "total_amount"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur"),
     *             @OA\Property(property="order_date", type="string", format="date-time", example="2025-04-01T12:00:00", description="Date de la commande"),
     *             @OA\Property(property="status", type="string", example="pending", description="Statut de la commande"),
     *             @OA\Property(property="total_amount", type="number", format="decimal", example=200.50, description="Montant total de la commande"),
     *             @OA\Property(
     *                 property="order_items",
     *                 type="array",
     *                 description="Liste des articles de la commande",
     *                @OA\Items(ref="#/components/schemas/OrderItem")
     *             ),
     *             @OA\Property(property="shipping_address", type="string", example="123 Rue Exemple, Paris, France", description="Adresse de livraison"),
     *             @OA\Property(property="shipping_date", type="string", format="date-time", example="2025-04-02T12:00:00", description="Date de livraison"),
     *             @OA\Property(property="tracking_number", type="string", example="ABC123456789", description="Numéro de suivi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commande créée avec succès",
     *         
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Données invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input data")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'order_date' => 'required|date',
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
            'order_items' => 'required|array',
            'shipping_address' => 'required|string',
            'shipping_date' => 'required|date',
            'tracking_number' => 'nullable|string',
        ]);

        $order = Order::create($validated);

        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        Shipping::create([
            'order_id' => $order->id,
            'shipping_address' => $request->shipping_address,
            'shipping_date' => $request->shipping_date,
            'tracking_number' => $request->tracking_number,
        ]);

        return response()->json($order, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{orderId}",
     *     summary="Mettre à jour une commande",
     *     tags={"Commandes"},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status", "total_amount"},
     *             @OA\Property(property="status", type="string", example="shipped", description="Statut mis à jour de la commande"),
     *             @OA\Property(property="total_amount", type="number", format="decimal", example=250.00, description="Montant total mis à jour de la commande")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commande mise à jour",
     *         
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */
    public function update(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
        ]);

        $order = Order::findOrFail($orderId);
        $order->update($validated);

        return response()->json($order);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{orderId}",
     *     summary="Supprimer une commande",
     *     tags={"Commandes"},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Commande supprimée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */
    public function destroy($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->delete();

        return response()->json(null, 204);
    }
}
