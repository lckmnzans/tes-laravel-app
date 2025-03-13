<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\ProductionSchedule;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;


class PPICController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_customers' => Pelanggan::count(),
            'total_suppliers' => Supplier::count(),
            'total_products' => Product::count(),
            'total_materials' => BahanBaku::count(),
        ];

        $allProcesses = [
            '1' => 'Prep Materials',
            '2' => 'Production',
            '3' => 'Packaging',
            '4' => 'Quality Control',
            '5' => 'Shipping',
        ];
        
        // Ambil total data di tabel production_schedule
        $totalCount = DB::table('production_schedules')->count();
        
        // Hitung jumlah data di setiap tahap proses
        $processCounts = DB::table('production_schedules')
            ->select('proses', DB::raw('COUNT(*) as count'))
            ->groupBy('proses')
            ->get()
            ->keyBy('proses'); // Ubah menjadi array dengan kunci nama proses
        
        // Gabungkan data dengan daftar proses
        $processData = collect($allProcesses)->map(function ($processName, $processIndex) use ($processCounts, $totalCount) {
            $count = $processCounts->has($processIndex) ? $processCounts[$processIndex]->count : 0;
            return [
                'stage' => $processName, // Menggunakan nama deskriptif
                'percentage' => $totalCount > 0 ? round(($count / $totalCount) * 100, 2) : 0,
                'count' => $count, // Tambahkan jumlah data untuk referensi
            ];
        });
        


        return view('ppic.dashboard', $data, ['processData' => $processData]);
    }
}
