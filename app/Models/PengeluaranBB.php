<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranBB extends Model
{
    use HasFactory;
    protected $table = 'pengeluaranbb';
    protected $fillable = [
        'production_schedule_id',
        'kode_sjm',
        'tanggal_pengeluaran',
        'keterangan',
    ];

    public function productionSchedule()
    {
        return $this->belongsTo(ProductionSchedule::class, 'production_schedule_id');
    }
}
