<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class EximController extends Controller
{
     public function dashboard()
     {
      $data = [
         'total_customers' => Pelanggan::count(),
         'total_suppliers' => Supplier::count(),
         'total_products' => Product::count(),
         'total_materials' => BahanBaku::count(),
     ];

     return view('exim.dashboard', $data);
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

     public function getDataBulan(Request $request)
     {
         // Query untuk jumlah DR per bulan
         $deliveryRequests = DB::table('delivery_requests')
             ->select(
                 DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                 DB::raw("COUNT(*) as total_dr")
             )
             ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
             ->orderBy('bulan', 'asc')
             ->get();
 
         // Query untuk jumlah PO per bulan
         $purchaseOrders = DB::table('purchase_orders')
             ->select(
                 DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                 DB::raw("COUNT(*) as total_po")
             )
             ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
             ->orderBy('bulan', 'asc')
             ->get();
 
         // Gabungkan data DR dan PO berdasarkan bulan
         $data = [];
         foreach ($deliveryRequests as $dr) {
             $data[$dr->bulan] = [
                 'bulan' => $dr->bulan,
                 'total_dr' => $dr->total_dr,
                 'total_po' => 0, // Default jika tidak ada PO
             ];
         }
 
         foreach ($purchaseOrders as $po) {
             if (isset($data[$po->bulan])) {
                 $data[$po->bulan]['total_po'] = $po->total_po;
             } else {
                 $data[$po->bulan] = [
                     'bulan' => $po->bulan,
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
 
