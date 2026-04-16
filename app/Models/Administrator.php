<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
    ];

    // Funciones de ayuda para vistas
    public function isAdmin() { return true; }
    public function isCompany() { return false; }
    public function isClient() { return false; }
    public function getCartAttribute() { return null; }

    /**
     * Relación con permisos.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Verifica si el administrador tiene un permiso específico.
     */
    public function hasPermission($permissionSlug)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }
}
