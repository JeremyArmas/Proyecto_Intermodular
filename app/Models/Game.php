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
        'stock', 'cover_image', 'developer', 'platform_id', 'is_active',
        'release_date', 'trailer_url'
    ];

    /**
     * Extrae el ID del vídeo de YouTube a partir del link completo.
     * Soporta: youtube.com/watch?v=XXX y youtu.be/XXX
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->trailer_url) return null;

        $url = parse_url($this->trailer_url);

        // Formato corto: youtu.be/XXXXXXXXXX
        if (isset($url['host']) && str_contains($url['host'], 'youtu.be')) {
            return ltrim($url['path'] ?? '', '/');
        }

        // Formato largo: youtube.com/watch?v=XXXXXXXXXX
        parse_str($url['query'] ?? '', $params);
        return $params['v'] ?? null;
    }

    // Casting para asegurar que release_date se trate como fecha
    protected $casts = [
        'release_date' => 'date',
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