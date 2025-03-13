<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderBB extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_bbs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode',
        'supplier_id',         // ID supplier
        'tanggal_po',          // Tanggal pembuatan PO
        'status_order',        // Status PO
        'tanggal_pengiriman',  // Tanggal pengiriman
        'dokumen_invoice',
        'no_invoice',
        'dokumen_sjm',
        'surat_jalan',
        'rate',                // Kurs nilai tukar
        'foot_note',           // Catatan tambahan
        'no_daftar',           // Nomor daftar dokumen
        'no_aju',              // Nomor pengajuan dokumen
        'no_pembayaran',
        'tanggal_daftar',      // Tanggal daftar dokumen
        'total_amount',        // Jumlah total transaksi
          
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_po' => 'date',
        'tanggal_pengiriman' => 'date',
        'tanggal_daftar' => 'date',
        'tanggal_invoice' => 'date',
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Define the relationship with the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderBBItem::class, 'purchase_order_bb_id', 'id');
    }
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }
    
    public function penerimaanBB()
{
    return $this->hasMany(PenerimaanBB::class, 'purchase_order_bb_id');
}

}
