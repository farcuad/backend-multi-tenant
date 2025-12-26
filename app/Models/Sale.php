<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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

     protected static function booted()
    {
        static::addGlobalScope('store_filter', function (Builder $builder){
            if(Auth::check()) {
                $user = Auth::user();
                if(!is_null($user->store_id)) {
                    $builder->where('sales.store_id', $user->store_id);
                }else {
                    $builder->where('sales.store_id', -1);
                };
            }else {
                $builder->where('sales.store_id', -1);
            }
        });
    }

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
