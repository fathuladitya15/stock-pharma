<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Suppliers;
use App\Models\PurchaseOrderItem;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'user_id',
        'supplier_id',
        'order_date',
        'status',
        'note'
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
