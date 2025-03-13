<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    protected $table = 'bahan_bakus';

    protected $fillable = [
        'kodeBahan',     // Kode bahan baku
        'namaBahan',     // Nama bahan baku
        'stokBahan',     // Stok bahan baku
        'satuan',        // Harga bahan baku, sebelumnya hargaBahan
        'stok_minimum',  // Stok minimum
        'jenis_tpb',     // Jenis TPB (sebelumnya kolom 'no')
    ];

    
   
    // Relasi many-to-many dengan model Product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_bahan_baku')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'bahan_baku_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function contracts()
{
    return $this->belongsToMany(SupplierContract::class, 'contract_bahan_baku', 'bahan_baku_id', 'contract_id')
                ->withPivot('min_order', 'harga_per_unit', 'cif') // Tambahkan kolom pivot jika ada
                ->withTimestamps(); // Jika tabel pivot menggunakan timestamp
}


}
