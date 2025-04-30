<?php
// filepath: app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders/{orderId}/pay",
     *     summary="Effectuer un paiement Stripe fictif",
     *     tags={"Paiement"},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client secret Stripe pour le paiement",
     *         @OA\JsonContent(
     *             @OA\Property(property="client_secret", type="string"),
     *             @OA\Property(property="message", type="string", example="Paiement Stripe fictif initialisé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */
    public function pay(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Montant en centimes (ex: 10.00€ => 1000)
        $amount = intval($order->total_amount * 100);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'description' => 'Paiement commande #' . $order->id,
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'message' => 'Paiement Stripe fictif initialisé'
        ]);
    }
}