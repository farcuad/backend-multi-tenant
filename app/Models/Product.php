<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'cost',
        'image',
        'min_stock',
        'store_id',
        'user_id',
    ];
    protected static function booted()
    {
        static::addGlobalScope('store_filter', function (Builder $builder) {
            
            // 2. Comprobamos si hay un usuario autenticado EN ESTE MOMENTO de la petición
            if (Auth::check()) {
                $user = Auth::user();
                
                // Si el usuario tiene store_id, aplicamos el filtro multi-tenancy.
                if (!is_null($user->store_id)) {
                    $builder->where('store_id', $user->store_id);
                } else {
                    // Si el usuario está autenticado pero no tiene tienda, no debería ver productos.
                    $builder->where('store_id', -1); 
                }
            } else {
                // Si no hay usuario autenticado (ej: consulta pública), forzamos a que no encuentre nada
                // a menos que se trate de un endpoint público (pero este es privado).
                $builder->where('store_id', -1); 
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
