<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = ['name', 'email', 'password', 'es_admin'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verificado' => 'datetime',
        'es_admin' => 'boolean',
    ];
}
