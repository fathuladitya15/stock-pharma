<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoQ extends Model
{
    use HasFactory;

    protected $table  = 'poqs';

    protected $fillable = [
        'product_id' ,
        'average_demand',
        'demand_per_year',
        'ordering_cost',
        'unit_price',
        'holding_cost',
        'eoq',
        'poq',
        'calculated_by',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
