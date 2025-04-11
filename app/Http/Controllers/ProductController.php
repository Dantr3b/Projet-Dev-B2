<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="Product",  
 *     type="object", 
 *     required={"name", "description", "price", "stock_quantity"},  
 *     @OA\Property(property="product_id", type="integer", description="ID du produit"),
 *     @OA\Property(property="name", type="string", description="Nom du produit"),
 *     @OA\Property(property="description", type="string", description="Description du produit"),
 *     @OA\Property(property="price", type="number", format="float", description="Prix du produit"),
 *     @OA\Property(property="stock_quantity", type="integer", description="Quantité en stock du produit")
 * )
 */


class ProductController extends Controller
{
    

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Obtenir la liste des produits", 
     *     tags={"Produits"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits",
     *         @OA\JsonContent(
     *             type="array", 
     *             @OA\Items(ref="#/components/schemas/Product")  
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Product::all());
    }


    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Ajouter un nouveau produit",  
     *     tags={"Produits"}, 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "stock_quantity"}, 
     *             @OA\Property(property="name", type="string", example="Nom du produit"),
     *             @OA\Property(property="description", type="string", example="Description du produit"),
     *             @OA\Property(property="price", type="number", format="float", example="99.99"),
     *             @OA\Property(property="stock_quantity", type="integer", example="100")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit ajouté avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Afficher un produit spécifique",
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Mettre à jour un produit",  
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "stock_quantity"},
     *             @OA\Property(property="name", type="string", example="Nom du produit"),
     *             @OA\Property(property="description", type="string", example="Description du produit"),
     *             @OA\Property(property="price", type="number", format="float", example="99.99"),
     *             @OA\Property(property="stock_quantity", type="integer", example="100")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Supprimer un produit",  
     *     tags={"Produits"},  
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Produit supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
