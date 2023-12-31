<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';


    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function sellers()
    {
        return $this->hasMany(CompanyOrderItem::class, 'item_id');
    }

}
