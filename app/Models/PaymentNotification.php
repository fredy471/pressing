<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentNotification extends Model
{
    protected $fillable = ['payment_id', 'raw_payload', 'processed']; 
    
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
