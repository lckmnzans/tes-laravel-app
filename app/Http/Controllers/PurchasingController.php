<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Supplier;

class PurchasingController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_customers' => Pelanggan::count(),
            'total_suppliers' => Supplier::count(),
            'total_products' => Product::count(),
            'total_materials' => BahanBaku::count(),
        ];
   
        return view('purchasing.dashboard', $data);
    }
}
