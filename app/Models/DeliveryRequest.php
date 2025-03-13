<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryRequest extends Model
{
    use HasFactory;
    protected $fillable = [
            'no_dr',
            'pelanggan_id',
            'total_harga',
            'status_dr',
            'status_po',
            'status_invoice',
        ];


public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke tabel Product
public function product()
{
    return $this->belongsToMany(Product::class, 'delivery_request_product', 'delivery_request_id', 'product_id')
                ->withPivot('quantity', 'total_price') // Menyertakan kolom tambahan dari tabel pivot
                ->withTimestamps();
}

public function purchaseOrder()
{
    return $this->hasOne(PurchaseOrder::class,'delivery_request_id');
}
    // Menghitung total harga berdasarkan produk dan jumlah
    public function calculateTotalHarga()
    {
        $totalHarga = 0;
        foreach ($this->products as $product) {
            $totalHarga += $product->pivot->total_price; // Total harga untuk setiap produk
        }

        return $totalHarga;
    }

}
