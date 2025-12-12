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
            'current_stock' => $this->stock,
            'product_id' => $this->id,
            'min_stock_level' => $this->min_stock,
        ];
    }
}
