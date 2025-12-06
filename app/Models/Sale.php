<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SaleDetails;
use App\Models\Product;
class Sale extends Model
{
    protected $fillable = [
        'store_id',
        'user_id',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details() 
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, SaleDetails::class)
                    ->withPivot('quantity', 'price');
    }
}
