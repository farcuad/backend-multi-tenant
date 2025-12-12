<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\LowStockAlertResource;
use App\Http\Controllers\Controller;
class LowStockController extends Controller
{
    public function index()
    {
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        return LowStockAlertResource::collection($lowStockProducts);
    }
}
