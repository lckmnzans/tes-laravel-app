<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Hasfactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $stable = 'pelanggans';
    protected $fillable = [
        'nama_customer',
        'alamat',
        'no_hp',
        'email',
    ];
    
    public function deliveryRequests()
    {
        return $this->hasMany(DeliveryRequest::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pelanggan) {
            // Mengisi kolom 'no' secara otomatis
            $pelanggan->no = Pelanggan::max('no') + 1; // Dapatkan nilai 'no' tertinggi, lalu tambahkan 1
        });
    }
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'pelanggan_id');
    }

}
