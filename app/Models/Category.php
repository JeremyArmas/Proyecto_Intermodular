<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Permite usar Factories para insertar datos de prueba desde Seeders
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}