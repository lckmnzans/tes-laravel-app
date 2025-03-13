<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_request_id',
        'kode_po',
        'total_amount',
        'status',
    ];

    // Relasi dengan DeliveryRequest
    public function deliveryRequest()
    {
        return $this->belongsTo(DeliveryRequest::class, 'delivery_request_id', 'id');
    }

    public function productionSchedule()
    {
        return $this->hasOne(ProductionSchedule::class, 'purchase_order_id', 'id');
    }

    // PurchaseOrder.php
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'delivery_request_product', 'delivery_request_id', 'product_id')
                    ->withPivot('quantity', 'total_price')
                    ->withTimestamps();
        
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }


}
