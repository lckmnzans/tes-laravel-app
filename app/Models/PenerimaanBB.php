<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBB extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi penamaan
    protected $table = 'penerimaanbb';

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'bahan_baku_id', 
        'purchase_order_bb_id', 
        'tanggal_terima', 
        'status', 
        'catatan', 
        'jumlah_terima', 
        'jumlah_order', 
        'lokasi_stok',
        'bukti',
    ];

    // Tentukan relasi dengan model BahanBaku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Tentukan relasi dengan model PurchaseOrderBb
    public function purchaseOrderBB()
    {
        return $this->belongsTo(PurchaseOrderBB::class, 'purchase_order_bb_id');
    }

public function productionSchedule()
{
    return $this->belongsTo(ProductionSchedule::class);
}

}
