<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'clothes_type_id', 'service_id', 'quantity', 'unit_price', 'subtotal'];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function clothesType()
    {
        return $this->belongsTo(ClothesType::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
