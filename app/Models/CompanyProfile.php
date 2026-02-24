<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    // Permite usar Factories para insertar datos de prueba desde Seeders
    use HasFactory;

    protected $fillable = ['user_id', 'company_name', 'tax_id', 'phone', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}