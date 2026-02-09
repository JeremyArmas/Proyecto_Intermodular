<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    use HasFactory;

    protected $table = "usuarios";
    protected $fillable = ['nombre', 'email', 'contraseña', 'es_admin'];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected $casts = [
        'email_verificado' => 'datetime',
        'es_admin' => 'boolean',
    ];

    /*public function post() : HasMany{
        return $this->hasMany(Post::class, 'usuario_id');
    }
    */

}
