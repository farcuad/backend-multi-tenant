<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id, // Para fines de verificación interna
            'total' => number_format($this->total, 2, '.', ''), // Formateo profesional de moneda
            
            // Información del vendedor
            'seller' => [
                'id' => $this->whenLoaded('user', $this->user->id),
                'name' => $this->whenLoaded('user', $this->user->name),
            ],

            // Detalle de los productos vendidos
            'details' => $this->whenLoaded('products', function () {
                // Usamos una colección anónima para manejar la tabla pivot.
                return $this->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'unit_price' => number_format($product->pivot->price ?? 0, 2, '.', ''),
                        'quantity' => $product->pivot->quantity ?? 0,
                        'line_total' => number_format($product->pivot->total ?? 0, 2, '.', ''),
                    ];
                });
            }),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
