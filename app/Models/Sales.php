<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;


    public function details()
    {
        return $this->hasMany(SalesDetail::class,'sale_id');
    }
}
