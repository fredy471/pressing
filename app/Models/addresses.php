<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class addresses extends Model
{
    protected $fillable = ['user_id', 'full_adress'];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}
