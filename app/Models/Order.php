<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'status', 
        'total_amount', 
        'shipping_address', 
        'order_type',
        'stripe_session_id',
        'tracking_status',
        'delivered_confirmed_at',
    ];

    protected $casts = [ //Aseguramos que la fecha se almacene como datetime. Sin el cast, Laravel lo guardaría como string y no podríamos usar funciones de fecha.
        'delivered_confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
