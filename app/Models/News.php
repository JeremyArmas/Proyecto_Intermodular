<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory; 
    protected $fillable = [ // Esto es para poder crear datos de prueba
        'title',
        'content',
        'image',
        'is_published',
    ];
}
