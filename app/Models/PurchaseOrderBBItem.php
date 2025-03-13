<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderBBItem extends Model
{
    use HasFactory;

    // Nama tabel yang terkait
    protected $table = 'purchase_order_bb_items';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'purchase_order_bb_id', 
        'purchase_request_id', 
        'bahan_baku_id', 
        'jumlah_order', 
        'harga_per_unit', 
        'total_harga',
        'kode_hs',
        'deskripsi',
        'satuan',
        'jumlah_kemasan',
        'jenis_kemasan',
        'cif',
    ];

    /**
     * Relasi ke PO induk
     */
    public function purchaseOrderBB()
    {
        return $this->belongsTo(PurchaseOrderBB::class, 'purchase_order_bb_id');
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    
     //Fungsi untuk menghitung total harga berdasarkan jumlah dan harga per unit
     
    public function calculateTotalHarga()
    {
        $this->total_harga = $this->jumlah_order * $this->harga_per_unit;
        $this->save();
    }
}
