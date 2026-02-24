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
        'order_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
