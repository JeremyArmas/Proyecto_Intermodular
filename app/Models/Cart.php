<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    /**
     * Elementos dentro del carrito.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Usuario al que pertenece el carrito (si está logueado).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcula el total de productos en el carrito.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Calcula el precio total del carrito.
     */
    public function getTotalPriceAttribute()
    {
        $user = Auth::user();
        return $this->items()->get()->sum(function ($item) use ($user) {
            return $item->game->getPriceForUser($user) * $item->quantity;
        });
    }
}
