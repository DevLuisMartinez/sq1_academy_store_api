<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $product = Product::create($request->all());
            return response()->json($product, 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error creating product'], 500);  
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return response()->json($product, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error updating product'], 500);  
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json([], 204);
         } catch (ModelNotFoundException $e) {
             return response()->json(['error' => 'Product not found'], 404);
         } catch (\Throwable $th) {
             return response()->json(['error' => 'Error deleting product'], 500);  
         }
    }
}
