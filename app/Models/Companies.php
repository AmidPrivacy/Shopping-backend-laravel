<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;



    public function products() {
        return $this->belongsToMany(Products::class, 'product_company_relations', 'company_id', 'product_id')->withPivot(['price', 'percentage']);
    }

}
