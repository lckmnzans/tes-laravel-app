<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Nama tabel di database

    protected $fillable = [
        'produk',
        'jenis_produk',
        'seri_produk',
        'model_pcb',
        'part_number',
        'spesifikasi',
        'harga',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Mengisi kolom 'no' secara otomatis
            $product->no = Product::max('no') + 1; // Dapatkan nilai 'no' tertinggi, lalu tambahkan 1
        });
    }

    public function BahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'product_bahan_baku', 'product_id', 'bahan_baku_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Model Product.php
public function deliveryRequests()
{
    return $this->belongsToMany(DeliveryRequest::class, 'delivery_request_product')
                ->withPivot('quantity', 'total_price') // Menyertakan kolom tambahan dari tabel pivot
                ->withTimestamps();
}

}
