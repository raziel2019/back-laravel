<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate([
            'code'        => 'required|string|unique:categories,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo'       => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id'
        ]);

        $category = Category::create($validatedData);

        return response()->json([
            'message'  => 'Categoría creada correctamente',
            'category' => $category
        ], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'code'        => 'required|string|unique:categories,code,' . $category->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo'       => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id'
        ]);

        $category->update($validatedData);

        return response()->json([
            'message'  => 'Categoría actualizada correctamente',
            'category' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente'
        ], 200);
    }
}
