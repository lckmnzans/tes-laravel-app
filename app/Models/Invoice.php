<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'amount',
        'status',
    ];

    // Relasi dengan PurchaseOrder
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}
}
