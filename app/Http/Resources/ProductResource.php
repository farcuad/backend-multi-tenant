<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class ProductResource extends JsonResource
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
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'description' => $this->description,
            
            //  Incluimos estos campos para verificación en pruebas, como solicitaste
            'store_id' => $this->store_id, 
            'user_id' => $this->user_id,
            
            // Generamos la URL de la imagen si existe.
            'image_url' => $this->image ? Storage::url($this->image) : null,
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
