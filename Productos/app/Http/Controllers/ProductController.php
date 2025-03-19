<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('categories')->get();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code'         => 'required|unique:products,code',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'photo'        => 'nullable', 
            'category_ids' => 'array', 
            'category_ids.*' => 'exists:categories,id',
        ]);

        $product = new Product();
        $product->code        = $validatedData['code'];
        $product->name        = $validatedData['name'];
        $product->description = $validatedData['description'] ?? null;
        $product->photo       = $validatedData['photo'] ?? null;
        $product->save();

        if (isset($validatedData['category_ids'])) {
            $product->categories()->sync($validatedData['category_ids']);
        }

        return response()->json([
            'message' => 'Producto creado correctamente',
            'product' => $product->load('categories')
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('categories')->findOrFail($id);
        return response()->json($product, 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $product = Product::findOrFail($id);

       $validatedData = $request->validate([
           'code'         => 'required|string|max:255|unique:products,code,'.$id,
           'name'         => 'required|string|max:255',
           'description'  => 'nullable|string',
           'photo'        => 'nullable',
           'category_ids' => 'array',
           'category_ids.*' => 'exists:categories,id',
       ]);

       $product->code        = $validatedData['code'];
       $product->name        = $validatedData['name'];
       $product->description = $validatedData['description'] ?? $product->description;
       $product->photo       = $validatedData['photo'] ?? $product->photo;
       $product->save();

       if (isset($validatedData['category_ids'])) {
           $product->categories()->sync($validatedData['category_ids']);
       }

       return response()->json([
           'message' => 'Producto actualizado correctamente',
           'product' => $product->load('categories')
       ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
