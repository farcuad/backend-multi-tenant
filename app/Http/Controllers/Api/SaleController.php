<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Product;
use App\Http\Requests\StoreSaleRequest; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
class SaleController extends Controller
{
    public function store(StoreSaleRequest $request)
    {
        // 🔑 1. AUTORIZACIÓN: Asegurar que el usuario tiene permiso para crear ventas
        // Esto asume que tienes un SalePolicy con un método 'create'.
        $this->authorize('create', Sale::class);

        try 
        {
            DB::beginTransaction();

            $totalVenta = 0;
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'store_id' => Auth::user()->store_id,
                'total' => 0,
            ]);

            foreach ($request->products as $item) {
                
                // 🔑 2. SEGURIDAD/VALIDACIÓN: El Global Scope asegura que solo se busquen productos de la tienda del usuario.
                $product = Product::find($item['id']);

                // Si el producto no existe O no pertenece a la tienda del usuario, $product es null.
                if (is_null($product)) {
                    throw new \Exception("El producto con ID {$item['id']} no se encontró o no pertenece a tu tienda.");
                }

                $quantity = $item['quantity'];
                $lineTotal = $product->price * $quantity;

                // 3. VALIDACIÓN DE STOCK
                if ($product->stock < $quantity) {
                    throw new \Exception("Stock insuficiente para el producto: " . $product->name . ". Stock disponible: " . $product->stock);
                }

                // 4. DECREMENTAR STOCK (Dentro de la transacción)
                $product->decrement('stock', $quantity);

                // 5. ACUMULAR TOTAL Y ADJUNTAR AL PIVOT
                $totalVenta += $lineTotal;

                $sale->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $lineTotal, 
                ]);
            }

            // 6. ACTUALIZAR TOTAL DE LA VENTA
            $sale->update(['total' => $totalVenta]);
            DB::commit();

            return response()->json([
                'message' => 'Venta registrada con éxito',
                'sale_id' => $sale->id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar la venta',
                'error' => $e->getMessage(),
            ], 400); // Usamos 400 Bad Request si el error es de lógica de negocio (stock, ID incorrecto)
        }
    }
}