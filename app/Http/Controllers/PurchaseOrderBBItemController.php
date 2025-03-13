<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderBB;
use App\Models\PurchaseOrderBBItem;

use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderBBItemController extends Controller
{
    // Menampilkan daftar item dalam sebuah Purchase Order
    public function index($purchaseOrderBBId)
    {
        $purchaseOrderBB = PurchaseOrderBB::with('items.bahanBaku', 'items.supplier')->findOrFail($purchaseOrderBBId);
        return view('purchasing.purchase_order_items.index', compact('purchaseOrderBB'));
    }

    // Menampilkan form untuk menambahkan item baru ke Purchase Order
    public function create($purchaseOrderBBId)
    {
        $purchaseOrderBB = PurchaseOrderBB::findOrFail($purchaseOrderBBId);
        $bahanBakus = BahanBaku::all(); // Mengambil semua bahan baku untuk opsi
        $suppliers = Supplier::all(); // Mengambil semua supplier untuk opsi

        return view('purchasing.purchase_order_items.create', compact('purchaseOrderBB', 'bahanBakus', 'suppliers'));
    }

    // Menyimpan item baru ke Purchase Order
    public function store(Request $request, $purchaseOrderBBId)
    {
        $purchaseOrderBB = PurchaseOrderBB::findOrFail($purchaseOrderBBId);

        // Validasi data item baru
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah_order' => 'required|integer|min:1',
            'harga_per_unit' => 'required|numeric|min:0',
        ]);

        // Membuat item baru dalam PO
        $item = new PurchaseOrderBBItem([
            'purchase_order_id' => $purchaseOrderBB->id,
            'bahan_baku_id' => $request->input('bahan_baku_id'),
            'supplier_id' => $request->input('supplier_id'),
            'jumlah_order' => $request->input('jumlah_order'),
            'harga_per_unit' => $request->input('harga_per_unit'),
        ]);
        
        // Menghitung total harga item
        $item->calculateTotalHarga();
        $item->save();

        return redirect()->route('purchase_order_items.index', $purchaseOrderBB->id)->with('status', 'Item berhasil ditambahkan ke Purchase Order.');
    }

    // Menampilkan form untuk mengedit item dalam Purchase Order
    public function edit($purchaseOrderBBId, $itemId)
    {
        $purchaseOrder = PurchaseOrderBB::findOrFail($purchaseOrderBBId);
        $item = PurchaseOrderBBItem::findOrFail($itemId);
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();

        return view('purchasing.purchase_order_items.edit', compact('purchaseOrderBB', 'item', 'bahanBakus', 'suppliers'));
    }

    // Memperbarui item dalam Purchase Order
    public function update(Request $request, $purchaseOrderBBId, $itemId)
    {
        $item = PurchaseOrderBBItem::findOrFail($itemId);

        // Validasi data yang diperbarui
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah_order' => 'required|integer|min:1',
            'harga_per_unit' => 'required|numeric|min:0',
        ]);

        // Memperbarui data item
        $item->bahan_baku_id = $request->input('bahan_baku_id');
        $item->supplier_id = $request->input('supplier_id');
        $item->jumlah_order = $request->input('jumlah_order');
        $item->harga_per_unit = $request->input('harga_per_unit');

        // Menghitung ulang total harga item
        $item->calculateTotalHarga();
        $item->save();

        return redirect()->route('purchase_order_items.index', $purchaseOrderBBId)->with('status', 'Item berhasil diperbarui.');
    }

    // Menghapus item dari Purchase Order
    public function destroy($purchaseOrderBBId, $itemId)
    {
        $item = PurchaseOrderBBItem::findOrFail($itemId);
        $item->delete();

        return redirect()->route('purchase_order_items.index', $purchaseOrderBBId)->with('status', 'Item berhasil dihapus dari Purchase Order.');
    }
}
