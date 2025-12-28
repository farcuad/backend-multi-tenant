<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
class ProductController extends Controller
{
    // C(R)UD: LISTAR TODOS LOS PRODUCTOS DE LA TIENDA
    public function index()
    {

        $this->authorize('viewAny', Product::class);
        $products = Product::orderBy('name')->get();
        return ProductResource::collection($products);
    }

    // (C)RUD: CREAR UN PRODUCTO
    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);
        $data = $request->validated();
        $user = Auth::user();
        $data['store_id'] = $user->store_id;
        $data['user_id'] = $user->id;
        if ($request->hasFile('image')) {
            $storeId = Auth::user()->store_id ?? 'default';
            $path = $request->file('image')->store('products/' . $storeId, 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);
        $product->image_url = $product->image ? Storage::url($product->image) : null;

        return response()->json([
            'message' => 'Producto Creado Correctamente',
            'data' => new ProductResource($product)
        ], 201);
    }

    // CR(U)D: ACTUALIZAR UN PRODUCTO
    // Usamos Inyección de Modelo (Product $product)
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Si el producto no es de su tienda, Laravel devuelve 404 (gracias al Scope).
        $data = $request->validated();
        $this->authorize('update', $product);
        if ($request->hasFile('image')) {
            // 1. Borrar la imagen antigua si existe
            if ($product->image) { // Usamos $product->image (nombre de la columna en DB)
                Storage::disk('public')->delete($product->image);
            }

            // 2. Subir la nueva imagen y obtener la ruta
            $storeId = Auth::user()->store_id ?? 'default';
            $path = $request->file('image')->store('products/' . $storeId, 'public');
            $data['image'] = $path;
        } elseif ($request->input('clear_image')) {
            // Lógica para borrar la imagen sin subir una nueva (ej: si el frontend envía 'clear_image'=true)
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $data['image'] = null;
            }
        }

        $product->update($data);

        return response()->json([
            'message' => 'Producto Actualizado Correctamente',
            'data' => new ProductResource($product)
        ], 200);
    }

    // CRU(D): ELIMINAR UN PRODUCTO
    // Usamos Inyección de Modelo (Product $product)
    public function destroy(Product $product)
    {
        // Si el producto no es de su tienda, Laravel devuelve 404.
        $this->authorize('delete', $product);
        if ($product->image) { // Usamos $product->image (nombre de la columna en DB)
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Producto Eliminado Correctamente'], 204); // 204 No Content
    }
}
