<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierContract extends Model
{
    use HasFactory;

    protected $table = 'supplier_contracts';

    protected $fillable = [
        'supplier_id',      // ID pemasok
        'start_date',       // Tanggal mulai kontrak
        'end_date',         // Tanggal akhir kontra        
        'method',           // Metode kontrak
        'status',           // Status kontrak (1, 2, 3)
        'due_day',          // Hari jatuh tempo
        'dokument',         // Dokumen kontrak
        'currency',         // Mata uang (contoh: USD, IDR)
    ];
    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'harga_per_unit' => 'decimal:2',
        'cif' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Define the relationship with the Supplier model.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function bahanBakus()
{
    return $this->belongsToMany(BahanBaku::class, 'contract_bahan_baku', 'contract_id', 'bahan_baku_id')
                ->withPivot('min_order', 'harga_per_unit', 'cif') // Tambahkan kolom pivot jika ada
                ->withTimestamps(); // Jika tabel pivot menggunakan timestamp
}



}
