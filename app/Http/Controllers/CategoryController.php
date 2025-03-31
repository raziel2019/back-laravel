<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
        ->with('children.children.children')
        ->get();
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
            'photo'       => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'parent_id'   => 'nullable|exists:categories,id'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('categories', 'public');
        }

        $category = new Category();
        $category->code        = $validatedData['code'];
        $category->name        = $validatedData['name'];
        $category->description = $validatedData['description'] ?? null;
        $category->parent_id   = $validatedData['parent_id'] ?? null;
        $category->photo       = $photoPath; 
        $category->save();

        return response()->json([
            'message'  => 'Categoría creada correctamente',
            'category' => $category
        ], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'code'        => 'nullable|string|unique:categories,code,' . $category->id,
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('categories', 'public');
            $category->photo = $photoPath;
        }
 
        $category->code        = $validatedData['code'];
        $category->name        = $validatedData['name'];
        $category->description = $validatedData['description'] ?? $category->description;
        $category->parent_id   = $validatedData['parent_id'] ?? $category->parent_id;
        $category->save();
 
        return response()->json([
            'message' => 'Categoria actualizado correctamente',
            'category' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if(!empty($category->photo)){
            Storage::disk('public')->delete($category->photo);
        }
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente'
        ], 200);
    }
}
