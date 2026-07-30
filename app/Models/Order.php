<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['service_id','agent_id', 'user_id', 'tracking_code','status', 'delivery_mode', 'total_amount', 'deposit_deadline', 'deposited_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function orderitems()
    {
        return $this->hasMany(Order::class);
    }

    public function service ()
    {
        return $this->belongsTo(Service::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

}
