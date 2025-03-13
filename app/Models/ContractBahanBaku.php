<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractBahanBaku extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'contract_bahan_baku';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'contract_id', 
        'bahan_baku_id', 
        'harga_per_unit', 
        'cif', 
        'min_order'
    ];

    // Relasi ke SupplierContract
    public function contract()
    {
        return $this->belongsTo(SupplierContract::class, 'contract_id');
    }

    // Relasi ke BahanBaku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
