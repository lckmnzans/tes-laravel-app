<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;


class GudangController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_customers' => Pelanggan::count(),
            'total_suppliers' => Supplier::count(),
            'total_products' => Product::count(),
            'total_materials' => BahanBaku::count(),
        ];
   
        return view('gudang.dashboard', $data);
    }

    public function getBahanBakuData() 
    {
        $bahanBaku = DB::table('bahan_bakus')
            ->select('namaBahan', 'stokBahan', 'stok_minimum')
            ->get();
    
        return response()->json($bahanBaku);
    }
    
}
