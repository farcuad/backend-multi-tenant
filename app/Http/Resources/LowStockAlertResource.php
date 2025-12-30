<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LowStockAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Stock bajo para el producto ' . $this->name,
            'product_id' => $this->id,
            'current_stock' => $this->stock,
            'min_stock_level' => $this->min_stock,
        ];
    }
}
