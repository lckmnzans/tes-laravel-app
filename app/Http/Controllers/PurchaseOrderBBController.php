<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderBB;
use App\Models\PurchaseOrderBBItem;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\SupplierContract;
use App\Models\PenerimaanBB;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderBBController extends Controller
{
    public function create(Request $request)
{
    $supplierId = $request->input('supplier');
    $selectedRequests = $request->input('selected_requests', []);

    if (!is_array($selectedRequests)) {
        $selectedRequests = [$selectedRequests]; 
    }

    $selectedRequests = array_map('intval', $selectedRequests);

    // Cek apakah data PR ada dalam database
    $purchaseRequests = PurchaseRequest::whereIn('id', $selectedRequests)
        ->whereHas('bahanBaku.contracts', function ($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })
        ->with('bahanBaku.contracts')
        ->get();

    if ($purchaseRequests->isEmpty()) {
        return redirect()->route('gudang.pr.index')->with('error', 'Data tidak valid atau sudah diproses.');
    }

    $supplier = Supplier::find($supplierId);

    if (!$supplier) {
        return redirect()->route('gudang.pr.index')->with('error', 'Supplier tidak ditemukan.');
    }

    //ambil currency dari tabel supplier_contract
    $firstContract = $purchaseRequests->first()->bahanBaku->contracts->first();
    $defaultCurrency = $firstContract ? $firstContract->currency : "IDR"; 

    $defaultTanggalDaftar = now()->toDateString(); // Format: YYYY-MM-DD

    // Mendapatkan ID terakhir dari tabel Pobb
    $latestPobb = PurchaseOrderBB::latest('id')->first(); // Ambil data POBB terakhir berdasarkan ID
    $lastId = $latestPobb ? $latestPobb->id : 0; // Ambil ID terakhir, atau 0 jika tidak ada data sebelumnya

    // Menambahkan 1 pada ID terakhir untuk mendapatkan nomor baru
    $nextId = $lastId + 1;

    // Format no_aju, misalnya prefix "xxxxx" dan ID yang ditambah 1
    $noAju = '10000' . str_pad($nextId, 2, '0', STR_PAD_LEFT); // Misalnya jadi 'xxxxx01', 'xxxxx02', dll.

    return view('purchasing.pobb.create', compact(
        'purchaseRequests',
        'supplier',
        'defaultTanggalDaftar', 
        'defaultCurrency',
        'noAju' // Pass the generated no_aju
    ));
}



public function store(Request $request)
{
    // Debugging input data
    Log::info('Data request:', $request->all());

    // Validasi input
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'selected_requests' => 'required|array',
        'selected_requests.*' => 'exists:purchase_requests,id',
        'no_aju' => 'required|string',
        'tanggal_daftar' => 'required|date',
        'currency' => 'required|string',
        'foot_note' => 'nullable|string',
        'no_invoice' => 'required|string|max:255',
        'invoice_dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'kode_hs' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
        'satuan' => 'nullable|string|max:50',
        'jumlah_kemasan' => 'nullable|integer|min:1',
        'jenis_kemasan' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        // Simpan file invoice jika diunggah
        $invoicePath = null;
        if ($request->hasFile('invoice_dokumen')) {
            $invoicePath = $request->file('invoice_dokumen')->store('invoices', 'public');
            Log::info("File invoice berhasil disimpan: $invoicePath");
        }

        // Cek apakah PR yang dipilih ditemukan
        $prList = PurchaseRequest::whereIn('id', $request->selected_requests)->get();
        if ($prList->isEmpty()) {
            throw new \Exception("PR tidak ditemukan di database.");
        }

        Log::info("PR ditemukan:", $prList->toArray());

        // Simpan data POBB
        $pobb = PurchaseOrderBB::create([
            'kode' => null, // Akan diupdate setelah ID dibuat
            'supplier_id' => $request->supplier_id,
            'tanggal_po' => now(),
            'status_order' => '1',
            'currency' => $request->currency,
            'foot_note' => $request->foot_note,
            'no_aju' => $request->no_aju,
            'tanggal_daftar' => $request->tanggal_daftar,
            'total_amount' => 0, // Akan diupdate setelah item ditambahkan
            'no_invoice' => $request->no_invoice,
            'dokumen_invoice' => $invoicePath,
        ]);

        // Update kode POBB
        $pobb->update([
            'kode' => 'POBB' . str_pad($pobb->id, 4, '0', STR_PAD_LEFT),
        ]);

        $totalAmount = 0;

        // Proses setiap PR yang dipilih
        foreach ($prList as $pr) {
            // Cari kontrak supplier
            $contract = DB::table('contract_bahan_baku')
                ->join('supplier_contracts', 'contract_bahan_baku.contract_id', '=', 'supplier_contracts.id')
                ->where('supplier_contracts.supplier_id', $request->supplier_id)
                ->where('contract_bahan_baku.bahan_baku_id', $pr->bahan_baku_id)
                ->select('contract_bahan_baku.*', 'supplier_contracts.currency')
                ->first();

            if (!$contract) {
                throw new \Exception("Kontrak tidak ditemukan untuk Bahan {$pr->bahanBaku->kodeBahan}");
            }

            Log::info("Kontrak ditemukan: ID {$contract->contract_id}, Harga Per Unit: {$contract->harga_per_unit}");

            // Hitung total harga
            $totalHarga = $pr->jumlah * $contract->harga_per_unit;
            $totalAmount += $totalHarga;

            // Simpan ke POBB Item
            PurchaseOrderBBItem::create([
                'purchase_order_bb_id' => $pobb->id,
                'purchase_request_id' => $pr->id,
                'bahan_baku_id' => $pr->bahan_baku_id,
                'jumlah_order' => $pr->jumlah,
                'harga_per_unit' => $contract->harga_per_unit,
                'cif' => $contract->cif,
                'total_harga' => $totalHarga,
                'kode_hs' => $request->kode_hs,
                'deskripsi' => $request->deskripsi,
                'satuan' => $request->satuan,
                'jumlah_kemasan' => $request->jumlah_kemasan,
                'jenis_kemasan' => $request->jenis_kemasan,
            ]);

            // Update status PR
            $pr->update(['status' => 'selesai']);
        }

        // Update total amount di POBB
        $pobb->update(['total_amount' => $totalAmount]);

        Log::info("Total amount POBB diperbarui: $totalAmount");

        DB::commit();

        return redirect()->route('purchasing.pobb.index')->with('success', 'POBB berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error saat menyimpan POBB:', ['message' => $e->getMessage()]);

        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}


public function updateDate(Request $request, $id)
{
    // Validasi inputan
    $request->validate([
        'tanggal_pengiriman' => 'required|date',
        'no_pembayaran' => 'nullable|file|mimes:pdf,jpeg,png,jpg,docx|max:2048',
        'surat_jalan' => 'required|string|max:255',
        //'dokument_sjm' => 'nullable|file|mimes:pdf,jpeg,png,jpg,docx|max:2048',
    ]);

    // Temukan POBB berdasarkan ID
    $pobb = PurchaseOrderBB::findOrFail($id);

    // Menangani unggahan dokumen jika ada (Dokumen Pembayaran)
    $dokumenPath = null;
    if ($request->hasFile('no_pembayaran')) {
        $dokumen = $request->file('no_pembayaran');
        $dokumenPath = $dokumen->store('dokumen_sjm', 'public'); // Simpan di folder dokumen_sjm
    }

    // Perbarui data POBB
    try {
        // Perbarui data POBB
        $pobb->update([
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'no_pembayaran' => $dokumenPath,
            'surat_jalan' => $request->surat_jalan,
            //'dokument_sjm' => $dokumenPath,  // Simpan path dokumen jika ada
            'status_order' => '2',  // Ubah status menjadi 'Dikirim'
        ]);
    
        if ($pobb->status_order == '2') {
            // Membuat data penerimaan bahan baku untuk setiap item
            foreach ($pobb->items as $item) {
                $jumlahOrder = $item->jumlah_order; // Ambil jumlah_order dari PurchaseOrderBBItem
    
                Log::info("Jumlah Order: " . $jumlahOrder); // Debugging untuk melihat nilai jumlah_order
    
                // Menambahkan data penerimaan berdasarkan item yang ada di POBB
                PenerimaanBB::create([
                    'purchase_order_bb_id' => $pobb->id, // ID dari POBB yang sudah diupdate
                    'bahan_baku_id' => $item->bahan_baku_id, // ID bahan baku dari item terkait
                    'jumlah_order' => $jumlahOrder, // Jumlah yang diterima
                    'tanggal_terima' => now(), // Tanggal penerimaan otomatis di-set sekarang
                    'status' => '1', // Status penerimaan (misalnya, "pending" atau sesuai kebutuhan)
                ]);
            }
        }
    } catch (\Exception $e) {
        return redirect()->route('purchasing.pobb.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}    

public function buktibayar(Request $request, $id)
{
    // Validasi file yang diunggah
    $request->validate([
        'dokument_sjm' => 'required|file|mimes:pdf,jpg,png|max:2048', // Validasi file
    ]);

    try {
        // Temukan data POBB berdasarkan ID
        $pobb = PurchaseOrderBB::findOrFail($id);

        // Simpan dokumen ke storage
        $invoicePath = $request->file('dokument_sjm')->store('dokumen_sjm', 'public');

        // Log untuk memastikan file tersimpan di path yang benar
        Log::info('File berhasil disimpan di: ' . $invoicePath);  // Log untuk memastikan path file yang disimpan

        // Perbarui kolom dokument_sjm di tabel POBB
        $pobb->dokument_sjm = $invoicePath; // Assign directly
        $updateSuccess = $pobb->save();  // Save directly

        // Log untuk memastikan update berhasil
        if ($updateSuccess) {
            Log::info('Kolom dokument_sjm berhasil diupdate di database dengan path: ' . $invoicePath);
        } else {
            Log::error('Gagal mengupdate kolom dokument_sjm di database.');
        }

        // Kembalikan response sukses
        return response()->json(['status' => 'success', 'message' => 'Bukti bayar berhasil diunggah.']);
    } catch (\Exception $e) {
        // Jika terjadi error, kembalikan error response
        Log::error('Error saat menyimpan bukti bayar: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}


    public function uploadInvoice(Request $request, $id)
{
    // Validasi Input
    $request->validate([
        'surat_jalan' => 'required|string|max:50|unique:purchase_order_bbs,surat_jalan',
        'dokumen_invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'items' => 'required|array',
        'items.*.kode_hs' => 'required|string|max:50',
        'items.*.jenis_kemasan' => 'required|string|max:50',
        'items.*.jumlah_kemasan' => 'required|numeric|min:1',
        'items.*.satuan' => 'required|string|max:50',
        'items.*.deskripsi' => 'nullable|string|max:255',
    ]);

    // Cari POBB
    $pobb = PurchaseOrderBB::findOrFail($id);

    DB::beginTransaction();
    try {
        if (PurchaseOrderBB::where('surat_jalan', $request->surat_jalan)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Surat Jalan sudah digunakan pada POBB lain.',
            ], 422);
        }
        // Simpan Dokumen Invoice dan Nomor Surat Jalan
        if ($request->hasFile('dokumen_invoice')) {
            $filePath = $request->file('dokumen_invoice')->store('invoices', 'public');
            $pobb->update([
                'surat_jalan' => $request->surat_jalan,
                'dokumen_invoice' => $filePath,
            ]);
        }

        // Perbarui Data Item
        foreach ($request->input('items') as $itemId => $itemData) {
            $pobbItem = PurchaseOrderBBItem::findOrFail($itemId);
            $pobbItem->update([
                'kode_hs' => $itemData['kode_hs'],
                'jenis_kemasan' => $itemData['jenis_kemasan'],
                'jumlah_kemasan' => $itemData['jumlah_kemasan'],
                'satuan' => $itemData['satuan'],
                'deskripsi' => $itemData['deskripsi'],
            ]);
        }
        PenerimaanBB::create([
            'purchase_order_bb_id' => $pobb->id,
            'bahan_baku_id' => $pobbItem->bahan_baku_id,
            'status' => '1', // Status awal
            'tanggal_terima' => null, // Belum diterima
            'jumlah_terima' => 0, // Jumlah diterima diisi setelah verifikasi
            'jumlah_order' => $pobbItem->jumlah_order,
        ]);
        

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice dan data tambahan berhasil diunggah.',
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Upload Invoice Error: ', ['message' => $e->getMessage()]);
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
}


    public function getItems($id)
{
    $items = PurchaseOrderBBItem::where('purchase_order_bb_id', $id)
        ->with(['purchaseRequest.bahanBaku' => function ($query) {
            $query->select('id', 'namaBahan'); // Ambil hanya nama bahan
        }])
        ->get();

    return response()->json($items);
}


    public function index()
    {
        // Ambil data POBB untuk halaman index
        $purchaseOrderBBs = PurchaseOrderBB::with('items.bahanBaku', 'supplier', 'items.purchaseRequest.bahanBaku')
            ->orderByRaw('CASE WHEN status_order = 1 THEN 0 WHEN status_order = 3 THEN 2 ELSE 1 END')    
            ->orderBy('tanggal_po', 'desc')
            ->paginate(10);
            
        return view('purchasing.pobb.index', compact('purchaseOrderBBs'));
    }

    public function show($id)
{
    $pobb = PurchaseOrderBB::with(['supplier', 'items.purchaseRequest.bahanBaku'])->findOrFail($id);

    return view('purchasing.pobb.show', compact('pobb'));
}

}