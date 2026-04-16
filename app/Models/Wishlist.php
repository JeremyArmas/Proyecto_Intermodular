<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'administrator_id', 'game_id', 'is_read'];

    /**
     * Juego al que referencia este elemento de la lista de deseos.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Usuario al que pertenece la Wishlist (si está logueado).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Administrador al que pertenece la Wishlist (para pruebas).
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }

    

    
}
