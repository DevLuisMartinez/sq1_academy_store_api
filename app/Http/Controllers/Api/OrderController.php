<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::with('orderItems')->get();
        return response()->json($order, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $order = Order::create($request->all());

            if($request->has('order_items')) {
                foreach ($request->order_items as $item) {
                   $order->orderItems()->create($item);
                }
            }
            return response()->json($order->load('orderItems'), 201);
        } catch (\Throwable $th) {
            \Log::error($th);
            return response()->json(['error' => 'Error creating order'], 500);  
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::with('orderItems')->find($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->update($request->all());
    
            // Actualización de los items de la orden
            if ($request->has('order_items')) {
                // Primero eliminamos los items existentes
                $order->orderItems()->delete();
    
                // Luego creamos los nuevos items
                foreach ($request->order_items as $item) {
                    $order->orderItems()->create($item);
                }
            }
    
            return response()->json($order->load('orderItems'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error updating order'], 500);  
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->orderItems()->delete();
            $order->delete();
    
            return response()->json([], 204);
         } catch (ModelNotFoundException $e) {
             return response()->json(['error' => 'Order not found'], 404);
         } catch (\Throwable $th) {
             return response()->json(['error' => 'Error deleting order'], 500);  
         }
    }
}
