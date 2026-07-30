<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'status', 'amount', 'method', 'provider_reference', 'provider', 'paid_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(PaymentNotification::class);
    }
    
}
