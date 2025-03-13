<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventori extends Model
{
    protected $table = 'inventoris';
    protected $fillable = [
        'nama_bahan', 
        'jenis_bahan', 
        'tanggal_penerimaan', 
        'supplier', 
        'jumlah', 
        'stok_tersedia', 
        'safety_stock', 
        'harga',  
        'lokasi_stok', 
        'no_penerimaan', 
        'catatan'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventori) {
            // Mengisi kolom 'no' secara otomatis
            $inventori->no = Inventori::max('no') + 1; // Dapatkan nilai 'no' tertinggi, lalu tambahkan 1
        });
    }

    // Mutator untuk jumlah dan total harga
    public function setJumlahAttribute($value)
    {
        $this->attributes['jumlah'] = $value;
        // Pastikan harga per unit sudah diisi sebelum menghitung total_harga
        $this->attributes['total_harga'] = isset($this->attributes['harga']) ? $value * $this->attributes['harga'] : 0;
    }

    public function setHargaAttribute($value)
    {
        $this->attributes['harga'] = $value;
        // Pastikan jumlah sudah diisi sebelum menghitung total_harga
        $this->attributes['total_harga'] = isset($this->attributes['jumlah']) ? $this->attributes['jumlah'] * $value : 0;
    }


}
