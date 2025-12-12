<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\SalesHistoryResource;
use App\Http\Controllers\Controller;
class SalesHistoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Sale::class);
        $sales = Sale::with([
            'user:id,name',
            'products' => function ($query) {
                $query->select('products.id', 'products.name')
                 ->withPivot('quantity', 'price', 'total');
            }
        ])
        ->latest()
        ->paginate(20);

        
        return SalesHistoryResource::collection($sales);
    }
}
