<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\LowStockAlertResource;
use App\Http\Controllers\Controller;
class LowStockController extends Controller
{
    public function index()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->orderBy('stock', 'asc')->get();
        return LowStockAlertResource::collection($lowStockProducts);
    }
}
