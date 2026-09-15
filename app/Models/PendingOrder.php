<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingOrder extends Model
{
    protected $fillable = ['reference', 'user_id', 'payload', 'total_amount', 'fedapay_transaction_id', 'statut', 'order_id'];

    protected $casts = ['payload' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
