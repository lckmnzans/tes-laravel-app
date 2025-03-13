<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Hasfactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';

    protected $fillable = [
        'kode_supplier',     // Kode unik supplier
        'nama_perusahaan',   // Nama perusahaan supplier
        'alamat',            // Alamat perusahaan
        'negara',            // Negara asal supplier
        'contact_person',    // Nama orang yang dapat dihubungi
        'no_cp',             // Nomor telepon contact person
        'no_tlp',            // Nomor telepon perusahaan
        'npwp',              // Nomor NPWP supplier
        'email',             // Email supplier
        'catatan',           // Catatan tambahan
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrderBB::class, 'supplier_id', 'id');
    }

    public function contracts()
{
    return $this->hasMany(SupplierContract::class, 'supplier_id', 'id');
}


public function bahanBakus()
{
    return $this->hasManyThrough(
        BahanBaku::class,
        SupplierContract::class,
        'supplier_id',    // Foreign key di supplier_contracts
        'kodeBahan',      // Primary key di bahan_bakus
        'id',             // Local key di suppliers
        'kode_barang'     // Foreign key di supplier_contracts yang mengacu ke bahan_bakus
    );
}



}
