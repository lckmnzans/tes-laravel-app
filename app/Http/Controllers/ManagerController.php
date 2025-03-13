<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;


class ManagerController extends Controller
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
        


        return view('manager.dashboard', $data, ['processData' => $processData]);
    }

    public function getBahanBakuData() 
    {
        $bahanBaku = DB::table('bahan_bakus')
            ->select('namaBahan', 'stokBahan', 'stok_minimum')
            ->get();
    
        return response()->json($bahanBaku);
    }

    public function getDrAndPoData(Request $request)
{
    // Query untuk jumlah DR per hari
    $deliveryRequests = DB::table('delivery_requests')
        ->select(
            DB::raw("DATE(created_at) as tanggal"), // Mengelompokkan berdasarkan tanggal
            DB::raw("COUNT(*) as total_dr")
        )
        ->groupBy(DB::raw("DATE(created_at)"))
        ->orderBy('tanggal', 'asc')
        ->get();

    // Query untuk jumlah PO per hari
    $purchaseOrders = DB::table('purchase_orders')
        ->select(
            DB::raw("DATE(created_at) as tanggal"), // Mengelompokkan berdasarkan tanggal
            DB::raw("COUNT(*) as total_po")
        )
        ->groupBy(DB::raw("DATE(created_at)"))
        ->orderBy('tanggal', 'asc')
        ->get();

    // Gabungkan data DR dan PO berdasarkan tanggal
    $data = [];
    foreach ($deliveryRequests as $dr) {
        $data[$dr->tanggal] = [
            'tanggal' => $dr->tanggal,
            'total_dr' => $dr->total_dr,
            'total_po' => 0, // Default jika tidak ada PO
        ];
    }

    foreach ($purchaseOrders as $po) {
        if (isset($data[$po->tanggal])) {
            $data[$po->tanggal]['total_po'] = $po->total_po;
        } else {
            $data[$po->tanggal] = [
                'tanggal' => $po->tanggal,
                'total_dr' => 0, // Default jika tidak ada DR
                'total_po' => $po->total_po,
            ];
        }
    }

    // Ubah menjadi array yang bisa diolah oleh frontend
    $result = array_values($data);

    // Return response ke view atau API
    return response()->json([
        'status' => 'success',
        'data' => $result,
    ]);
}



}
