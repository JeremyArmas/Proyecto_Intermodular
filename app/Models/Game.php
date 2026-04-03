<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // Campos que permitimos rellenar masivamente
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'b2b_price', 
        'stock', 'cover_image', 'developer', 'platform_id', 'is_active'
    ];

    // Relación: Un juego pertenece a una plataforma
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    // Relación: Un juego tiene muchas categorías
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    // Función para obtener el precio según quién lo mire
    public function getPriceForUser($user)
    {
        if ($user && $user->isCompany() && $this->b2b_price) {
            return $this->b2b_price;
        }
        return $this->price;
    }

    /**
     * Un juego puede estar en muchos carritos.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}