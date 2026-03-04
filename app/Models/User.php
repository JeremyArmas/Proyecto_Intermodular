<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Importante para poder registrar admins/empresas
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación con el perfil de empresa
    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    // Funciones de ayuda
    public function isAdmin() { 
        return $this->role === 'admin'; 
    }
    public function isCompany() { 
        return $this->role === 'company'; 
    }
    public function isClient() { 
        return $this->role === 'client'; 
    }

    // Relación con Pedidos
    public function orders() {
        return $this->hasMany(Order::class);
    }
}