<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;


    protected $table = 'sales_details';


    public function sales()
    {
        return $this->belongsTo(Sales::class,'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
