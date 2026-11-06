<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothesType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category', 'is_active'];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(ServiceClothesPrice::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
