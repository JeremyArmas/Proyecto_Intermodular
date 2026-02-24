<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    // Permite usar Factories para insertar datos de prueba desde Seeders
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}