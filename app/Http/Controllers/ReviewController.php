<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     required={"product_id", "user_id", "rating"},
 *     @OA\Property(property="review_id", type="integer", description="ID de l'avis"),
 *     @OA\Property(property="product_id", type="integer", description="ID du produit concerné"),
 *     @OA\Property(property="user_id", type="integer", description="ID de l'utilisateur qui a posté l'avis"),
 *     @OA\Property(property="rating", type="integer", description="Note attribuée au produit"),
 *     @OA\Property(property="comment", type="string", description="Commentaire sur le produit")
 * )
 */

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews/{productId}",
     *     summary="Obtenir les avis pour un produit",
     *     tags={"Avis"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des avis pour le produit",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Review"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->get();

        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'Aucun avis trouvé'], 404);
        }

        return response()->json($reviews);
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Ajouter un avis",
     *     tags={"Avis"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "user_id", "rating"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="rating", type="integer", example=4),
     *             @OA\Property(property="comment", type="string", example="Très bon produit !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Avis ajouté avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($validated);

        return response()->json($review, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{reviewId}",
     *     summary="Obtenir un avis spécifique",
     *     tags={"Avis"},
     *     @OA\Parameter(
     *         name="reviewId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'avis",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non trouvé"
     *     )
     * )
     */
    public function show($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Avis non trouvé'], 404);
        }

        return response()->json($review);
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{reviewId}",
     *     summary="Mettre à jour un avis",
     *     tags={"Avis"},
     *     @OA\Parameter(
     *         name="reviewId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating"},
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Produit excellent !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Avis mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non trouvé"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function update(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Avis non trouvé'], 404);
        }

        $review->update($validated);

        return response()->json($review);
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{reviewId}",
     *     summary="Supprimer un avis",
     *     tags={"Avis"},
     *     @OA\Parameter(
     *         name="reviewId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Avis supprimé"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non trouvé"
     *     )
     * )
     */
    public function destroy($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Avis non trouvé'], 404);
        }

        $review->delete();

        return response()->json(null, 204);
    }
}
