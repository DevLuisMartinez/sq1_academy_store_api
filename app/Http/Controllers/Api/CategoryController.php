<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $category = Category::create($request->all());
            return response()->json($category, 201);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error creating category'], 500);  
        }
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $category = Category::findOrFail($id);
            return response()->json($category, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $category = Category::findOrFail($id);
            $category->update($request->all());
            return response()->json($category, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error updating category'], 500);  
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

           $category = Category::findOrFail($id);
           $category->delete();
           return response()->json([], 204);
           
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error deleting category'], 500);  
        }
    }
}
