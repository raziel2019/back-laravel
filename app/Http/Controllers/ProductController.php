<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['categories', 'tariffs'])->get();
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
            'photo'        => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'tariffs' => 'nullable|array',
            'tariffs.*.start_date' => 'required|date',
            'tariffs.*.end_date'   => 'required|date|after_or_equal:tariffs.*.start_date',
            'tariffs.*.price'      => 'required|numeric|min:0'

        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        $product = Product::create([
            'code'        => $validatedData['code'],
            'name'        => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'photo'       => $photoPath,
        ]);

        if (isset($validatedData['category_ids'])) {
            $product->categories()->sync($validatedData['category_ids']);
        }

        if (isset($validatedData['tariffs'])) {
            foreach ($validatedData['tariffs'] as $tariffData) {
                $tariffData['product_id'] = $product->id;
                ProductTariff::create($tariffData);
            }
        }

        return response()->json([
            'message' => 'Producto creado correctamente',
            'product' => $product->load(['categories', 'tariffs'])
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['categories', 'tariffs'])->findOrFail($id);
        return response()->json($product, 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'code'         => 'required|unique:products,code,'.$product->id,
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'photo'        => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'tariffs'      => 'nullable|array',
            'tariffs.*.start_date' => 'required|date',
            'tariffs.*.end_date'   => 'required|date|after_or_equal:tariffs.*.start_date',
            'tariffs.*.price'      => 'required|numeric|min:0'
        ]);

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($product->photo);
            $photoPath = $request->file('photo')->store('products', 'public');
            $product->photo = $photoPath;
        }

        $product->update([
            'code'        => $validatedData['code'],
            'name'        => $validatedData['name'],
            'description' => $validatedData['description'] ?? $product->description,
        ]);

        if (isset($validatedData['category_ids'])) {
            $product->categories()->sync($validatedData['category_ids']);
        }

        if (isset($validatedData['tariffs'])) {
            $product->tariffs()->delete();
            foreach ($validatedData['tariffs'] as $tariffData) {
                $tariffData['product_id'] = $product->id;
                ProductTariff::create($tariffData);
            }
        }

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'product' => $product->load(['categories', 'tariffs'])
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if(!empty($product->photo)){
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
