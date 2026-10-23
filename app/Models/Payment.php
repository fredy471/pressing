<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'amount', 'currency', 'method', 'provider_reference', 'status', 'paid_at'];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'status' => PaymentStatus::class,
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
}
