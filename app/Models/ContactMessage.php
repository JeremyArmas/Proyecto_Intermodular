<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactMessage extends Model


/*Representa un mensaje de contacto enviado por un cliente*/

{
    use HasFactory;
    protected $fillable = [ // Campos que se pueden asignar
        'name',
        'email',
        'subject',
        'message',
        'admin_id',
        'locked_at',
        'status',
    ];

    // Para que al acceder a locked_at lo trate como un objeto Carbon (fecha/hora) y no como un string
    protected $casts = [
        'locked_at' => 'datetime',
    ];

    // Relación con el admin que atiende el ticket (opcional, puede ser null si aún no lo ha atendido ningún admin)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
