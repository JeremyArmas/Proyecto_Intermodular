<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // Permite usar Factories para insertar datos de prueba desde Seeders
    use HasFactory;

    // Campos que permitimos rellenar masivamente
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'b2b_price', 
        'stock', 'cover_image', 'developer', 'platform_id', 'is_active'
    ];

    // RelaciÃ³n: Un juego pertenece a una plataforma
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    // RelaciÃ³n: Un juego tiene muchas categorÃ­as
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    // FunciÃ³n para obtener el precio segÃºn quiÃ©n lo mire
    public function getPriceForUser($user)
    {
        if ($user && $user->isCompany() && $this->b2b_price) {
            return $this->b2b_price;
        }
        return $this->price;
    }
}