<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'game_id', 'quantity'];

    /**
     * El carrito al que pertenece este item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * El juego asociado a este item.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Calcula el subtotal de este item para el usuario actual.
     */
    public function getSubtotalAttribute()
    {
        $user = auth()->user();
        return $this->game->getPriceForUser($user) * $this->quantity;
    }
}
