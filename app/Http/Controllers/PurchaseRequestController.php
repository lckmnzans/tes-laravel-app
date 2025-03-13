<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderBB;
use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    /**
     * Menampilkan daftar PR yang statusnya pending untuk disetujui oleh Divisi Purchasing
     */
    public function index(Request $request)
{
    // Ambil daftar supplier untuk dropdown
    $suppliers = Supplier::all();

    // Ambil ID supplier yang dipilih dari request
    $supplierId = $request->input('supplier');

    // Query awal untuk mengambil Purchase Request beserta bahan baku
    $query = PurchaseRequest::with(['bahanBaku', 'bahanBaku.contracts.supplier']);

    // Jika ada supplier yang dipilih, filter bahan baku berdasarkan kontrak dengan supplier tersebut
    if ($supplierId) {
        $query->whereHas('bahanBaku.contracts', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        });
    }

    // Ambil data dengan pagination
    $purchaseRequests = $query->orderBy('created_at', 'desc')->paginate(10);

    // Kirim data ke view
    return view('gudang.pr.index', compact('purchaseRequests', 'suppliers'));
}



    /**
     * Menampilkan form untuk membuat Purchase Request berdasarkan bahan baku yang stoknya minimum
     */
    public function create()
{
    // Ambil bahan baku yang stoknya di bawah minimum dan tidak memiliki PR yang belum selesai
    $lowStockMaterials = BahanBaku::whereColumn('stokBahan', '<=', 'stok_minimum')
        ->where(function ($query) {
            // Pastikan bahan baku yang memiliki PR yang belum selesai tidak diambil
            $query->whereDoesntHave('purchaseRequests', function ($subQuery) {
                $subQuery->where('status', '!=', 'selesai'); // Hanya yang statusnya selesai yang diizinkan
            });
        })
        ->get();

    return view('gudang.pr.createpr', compact('lowStockMaterials'));
}




    /**
     * Menyimpan Purchase Request yang baru dibuat oleh Gudang
     */
    public function store(Request $request)
    {
        $bahanBakuIds = $request->input('bahan_baku_id');
        $jumlahPermintaan = $request->input('jumlah');

        foreach ($bahanBakuIds as $id) {
            PurchaseRequest::create([
                'bahan_baku_id' => $id,
                'jumlah' => $jumlahPermintaan[$id],
                'status' => 'sudah',
            ]);
            // Update status bahan baku menjadi "Sudah Diajukan"
            $material = BahanBaku::findOrFail($id);
            $material->update(['status' => 'Sudah Diajukan']);
        }

        return redirect()->route('gudang.bahanbaku.stokminimum')->with('status', 'Purchase Request berhasil diajukan.');
    }

    /**
     * Menyetujui Purchase Request di Divisi Purchasing dan membuat Purchase Order (POBB)
     */
    public function approve($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->update(['status' => 'approved']);

        // Buat PO baru dari PR yang disetujui
        PurchaseOrderBB::create([
            'purchase_request_id' => $purchaseRequest->id,
            'bahan_baku_id' => $purchaseRequest->bahan_baku_id,
            'jumlah_order' => $purchaseRequest->jumlah,
        ]);

        $bahanBaku = BahanBaku::findOrFail($purchaseRequest->bahan_baku_id);
        $bahanBaku->update(['status' => 'selesai']);

        return redirect()->route('purchasing.pobb.index')->with('status', 'Purchase Request disetujui dan Purchase Order (POBB) dibuat.');
    }
}
