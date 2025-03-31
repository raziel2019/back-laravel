<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with('products')->get();
        
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_date' => 'required|date',
            'products'   => 'required|array',
            'products.*' => 'exists:products,id',
            'units'      => 'required|integer|min:1',
            'total'      => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create([
            'order_date' => $request->order_date,
            'total'      => $request->total
        ]);

        $order->products()->attach($request->products, ['units' => $request->units]);

        return response()->json([
            'message' => 'Pedido creado correctamente',
            'order'   => $order->load('products')
        ], 201);
    }
}
