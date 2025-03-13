<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    protected $table = 'production_schedules';

    // Menentukan kolom yang dapat diisi secara massal (fillable)
    protected $fillable = [
        'purchase_order_id',
        'kode',  // Ganti batch_number dengan kode
        'schedule_date',
        'expected_finish_date',
        'prep_materials_completed_at',  // Ganti actual_start_date dengan prep_materials_completed_at
        'production_completed_at',     // Ganti actual_finish_date dengan production_completed_at
        'packaging_completed_at',      // Tambah kolom packaging_completed_at
        'quality_control_completed_at',// Tambah kolom quality_control_completed_at
        'shipping_completed_at',       // Tambah kolom shipping_completed_at
        'target_prep_materials',
        'target_production',
        'target_packaging',
        'target_quality_control',
        'target_shipping',
        'proses',
        'statusProduksi',
        'quantity_to_produce',
        'produced_quantity',
        'waste_quantity',
        'deskription',
    ];

    // Menentukan kolom yang harus dianggap sebagai tipe tanggal
    protected $dates = [
        'schedule_date',
        'expected_finish_date',
        'prep_materials_completed_at',  // Kolom baru yang ditambahkan
        'production_completed_at',     // Kolom baru yang ditambahkan
        'packaging_completed_at',      // Kolom baru yang ditambahkan
        'quality_control_completed_at',// Kolom baru yang ditambahkan
        'shipping_completed_at',       // Kolom baru yang ditambahkan
        'target_prep_materials',
        'target_production',
        'target_packaging',
        'target_quality_control',
        'target_shipping',
    ];
    

    // Relasi ke tabel PurchaseOrder
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    // Relasi ke tabel Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function PengeluaranBB()
    {
        return $this->hasOne(PengeluaranBB::class, 'production_schedule_id');
    }
    // Status Enum untuk status produksi
    const PROSES_PREP_MATERIALS = '1';
    const PROSES_PRODUCTION = '2';
    const PROSES_PACKAGING = '3';
    const PROSES_QUALITY_CONTROL = '4';
    const PROSES_SHIPPING = '5';
    const PROSES_SELESAI = '6';

    // Status Enum untuk status bahan baku
    const STATUSPRODUKSI_NOT_YET = 'belum';
    const STATUSPRODUKSI_DONE = 'sudah';
    const STATUSPRODUKSI_FINISHED = 'selesai';

    // Validasi untuk status
    public static function getProsesList()
{
    return [
        '1' => 'Prep Materials',
        '2' => 'Production',
        '3' => 'Packaging',
        '4' => 'Quality Control',
        '5' => 'Shipping',
        '6' => 'Selesai',
        '0' => 'Antrian'
    ];
}

    public static function getStatusProduksiList()
    {
        return [
            self::STATUSPRODUKSI_NOT_YET,
            self::STATUSPRODUKSI_DONE,
            self::STATUSPRODUKSI_FINISHED,
        ];
    }
}

