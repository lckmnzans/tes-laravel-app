<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $products = Product::with('bahanBaku')->paginate(10);
        $bahanBakuList = BahanBaku::all();
        return view('gudang.product.index', compact('products', 'bahanBakuList'));
    }

    // Menampilkan form untuk membuat produk baru
    public function create()
    {
        $bahanBakuList = BahanBaku::all(); // Mendapatkan semua bahan baku untuk ditampilkan di form
        return view('gudang.product.create', compact('bahanBakuList'));
    }

    // Menyimpan produk baru dan menghubungkannya dengan bahan baku
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'produk' => 'required|string|max:255',
            'jenis_produk' => 'required|string|max:255',
            'seri_produk' => 'required|string|max:255',
            'model_pcb' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'bahan_baku' => 'array', // Validasi array bahan baku
            'bahan_baku.*.id' => 'exists:bahan_bakus,id',
            'bahan_baku.*.quantity' => 'required|integer|min:1',
        ]);

        // Membuat produk baru
        $product = Product::create($request->only([
            'produk', 
            'jenis_produk', 
            'seri_produk', 
            'model_pcb', 
            'part_number', 
            'spesifikasi', 
            'harga'
        ]));

        // Menghubungkan produk dengan bahan baku beserta jumlahnya
        $bahanBakuData = [];
        foreach ($request->bahan_baku as $bahan) {
            $bahanBakuData[$bahan['id']] = ['quantity' => $bahan['quantity']];
        }
        $product->bahanBaku()->attach($bahanBakuData);

        return redirect()->route('gudang.product.index')->with('status', 'Produk Berhasil Dibuat dengan Bahan Baku');
    }

    // Menampilkan form untuk mengedit produk dan bahan bakunya
    public function edit($id)
    {
        $product = Product::with('bahanBaku')->findOrFail($id);
        $bahanBakuList = BahanBaku::all();
        return view('gudang.product.edit', compact('product', 'bahanBakuList'));
    }

    // Memperbarui data produk dan bahan bakunya
    public function update(Request $request, $id)
{
    //dd($request->all());
    $product = Product::findOrFail($id);
    
    $request->validate([
        'produk' => 'required|string|max:255',
        'jenis_produk' => 'required|string|max:255',
        'seri_produk' => 'required|string|max:255',
        'model_pcb' => 'required|string|max:255',
        'part_number' => 'required|string|max:50|unique:products,part_number,' . $product->id,
        'spesifikasi' => 'nullable|string',
        'harga' => 'required|numeric|min:0',
        'bahan_baku' => 'nullable|array',
        'bahan_baku.*.id' => 'nullable|exists:bahan_bakus,id',
        'bahan_baku.*.quantity' => 'nullable|integer|min:1',

    ]);
    

    // Update data produk
    $product->update($request->only([
        'produk', 'jenis_produk', 'seri_produk', 'model_pcb', 'part_number', 'spesifikasi', 'harga'
    ]));

    // Update data bahan baku terkait dengan produk ini
    $bahanBakuData = [];
    if ($request->has('bahan_baku')) {
        foreach ($request->bahan_baku as $bahan) {
            if (isset($bahan['id']) && isset($bahan['quantity'])) {
                $bahanBakuData[$bahan['id']] = ['quantity' => $bahan['quantity']];
            }
        }
    }
    $product->bahanBaku()->sync($bahanBakuData);

    return redirect()->route('gudang.product.index')->with('status', 'Produk Berhasil Diperbarui dengan Bahan Baku');
}

    // Menghapus produk beserta relasi bahan bakunya
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->bahanBaku()->detach(); // Hapus relasi bahan baku terlebih dahulu
        $product->delete();

        return redirect()->route('gudang.product.index')->with('status', 'Produk Berhasil Dihapus');
    }
}
