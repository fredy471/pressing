<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceClothesPrice extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'clothe_type_id', 'price'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function clothesType()
    {
        return $this->belongsTo(ClothesType::class);
    }
}
