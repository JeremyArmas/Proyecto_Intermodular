<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Importante para poder registrar admins/empresas
        'avatar',
        'country',
        'birth_date',
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

    /**
     * Lista de deseos del usuario.
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * El carrito de compras actual del usuario.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
}