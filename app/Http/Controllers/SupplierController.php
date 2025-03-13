<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierContract;
use App\Models\BahanBaku;
use App\Models\PurchaseOrderBB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    // Menampilkan daftar data supplier
    public function index(Request $request)
{
    // Ambil query pencarian jika ada
    $search = $request->input('search');

    // Ambil data supplier dengan filter pencarian jika ada
    $suppliers = Supplier::when($search, function ($query, $search) {
        return $query->where('nama_perusahaan', 'like', '%' . $search . '%');
    })->paginate(10);

    return view('purchasing.supplier.index', compact('suppliers', 'search'));
}


    // Menampilkan form untuk membuat data supplier baru
    public function create()
    {
        return view('purchasing.supplier.create');
    }

    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_supplier' => 'nullable|string|max:20|unique:suppliers,kode_supplier',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'negara' => 'nullable|string|max:100',
            'contact_person' => 'required|string|max:255',
            'no_cp' => 'required|string|max:20',
            'no_tlp' => 'required|string|max:20',
            'npwp' => 'nullable|string|max:50',
            'email' => 'required|email|unique:suppliers,email',
            'catatan' => 'nullable|string',
        ]);
        
        Supplier::create([
            'kode_supplier' => $request->input('kode_supplier'),
            'nama_perusahaan' => $request->input('nama_perusahaan'),
            'alamat' => $request->input('alamat'),
            'negara' => $request->input('negara'),
            'contact_person' => $request->input('contact_person'),
            'no_cp' => $request->input('no_cp'),
            'no_tlp' => $request->input('no_tlp'),
            'npwp' => $request->input('npwp'),
            'email' => $request->input('email'),
            'catatan' => $request->input('catatan'),
        ]);
        
        return redirect()->route('purchasing.supplier.index')->with('status', 'Supplier Berhasil Dibuat');

    }

    // Menampilkan data supplier tertentu
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('purchasing.supplier.show', compact('supplier'));
    }

    // Menampilkan form untuk mengedit data supplier
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('purchasing.supplier.edit', compact('supplier'));
    }

    // Memperbarui data supplier yang ada
    public function update(Request $request, $id)
{
    // Temukan data supplier berdasarkan ID
    $supplier = Supplier::findOrFail($id);

    // Validasi input
    $request->validate([
        'kode_supplier' => 'nullable|string|max:20|unique:suppliers,kode_supplier,' . $supplier->id,
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'negara' => 'nullable|string|max:100',
        'contact_person' => 'required|string|max:255',
        'no_cp' => 'required|string|max:20',
        'no_tlp' => 'required|string|max:20',
        'npwp' => 'nullable|string|max:50',
        'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
        'catatan' => 'nullable|string',
    ]);

    // Update data
    $supplier->update([
        'kode_supplier' => $request->input('kode_supplier'),
        'nama_perusahaan' => $request->input('nama_perusahaan'),
        'alamat' => $request->input('alamat'),
        'negara' => $request->input('negara'),
        'contact_person' => $request->input('contact_person'),
        'no_cp' => $request->input('no_cp'),
        'no_tlp' => $request->input('no_tlp'),
        'npwp' => $request->input('npwp'),
        'email' => $request->input('email'),
        'catatan' => $request->input('catatan'),
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('purchasing.supplier.index')->with('status', 'Supplier berhasil diperbarui!');
    }


    // Menghapus data supplier
    public function destroy($id)
{
    // Temukan supplier berdasarkan ID
    $supplier = Supplier::findOrFail($id);

    // Hapus supplier
    $supplier->delete();

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('purchasing.supplier.index')->with('status', 'Supplier Berhasil Dihapus');
}

public function contractsIndex()
{
    // Ambil supplier dengan kontrak terkait
    $suppliers = Supplier::with('contracts')->get();

    // Kirim data ke view
    return view('purchasing.supplier.contract_index', compact('suppliers'));
}




public function createContract()
{
    $suppliers = Supplier::all(); // Ambil semua supplier
    $bahanBakus = BahanBaku::all(); // Ambil semua bahan baku
    return view('purchasing.supplier.contract_create', compact('suppliers', 'bahanBakus'));

}



public function storeContract(Request $request)
{
    // Validasi input
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'currency' => 'required|string|max:3',
        'bahan_baku' => 'required|array',
        'bahan_baku.*.id' => 'required|exists:bahan_bakus,id',
        'bahan_baku.*.harga_per_unit' => 'required|numeric|min:0',
        'bahan_baku.*.cif' => 'required|numeric|min:0',
        'bahan_baku.*.min_order' => 'required|integer|min:1',
        'method' => 'required|string',
        'dokument' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    try {
        // Proses upload file
        if (!$request->hasFile('dokument')) {
            return back()->withErrors('Dokumen tidak ditemukan. Silakan unggah ulang.');
        }
        $filePath = $request->file('dokument')->store('contracts', 'public');
        Log::info('Dokumen disimpan di:', ['filePath' => $filePath]);

        // Buat kontrak
        $contract = SupplierContract::create([
            'supplier_id' => $request->supplier_id,
            'currency' => $request->currency,
            'method' => $request->method,
            'dokument' => $filePath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => '1',
        ]);
        Log::info('Kontrak berhasil dibuat:', $contract->toArray());

        // Simpan data bahan baku
        $dataPivot = [];
        foreach ($request->bahan_baku as $bahanBaku) {
            $dataPivot[$bahanBaku['id']] = [
                'harga_per_unit' => $bahanBaku['harga_per_unit'],
                'cif' => $bahanBaku['cif'],
                'min_order' => $bahanBaku['min_order'],
            ];
        }

        // Attach data pivot
        $contract->bahanBakus()->attach($dataPivot);

    return redirect()->route('purchasing.supplier.contract_index')->with('status', 'Kontrak berhasil ditambahkan!');
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan kontrak: ' . $e->getMessage());
        return back()->withErrors('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
    }
}


public function destroyContract($id)
{
    // Temukan supplier berdasarkan ID
    $contract = SupplierContract::findOrFail($id);

    // Hapus supplier
    $contract->delete();

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('purchasing.supplier.contract_index')->with('status', 'Supplier Berhasil Dihapus');
}

public function contractDetail($id)
{
    // Ambil supplier beserta kontrak dan bahan baku terkait
    $supplier = Supplier::with(['contracts.bahanBakus'])->findOrFail($id);

    // Debug untuk memastikan bahan baku ter-load
  

    // Ambil data Purchase Order BB yang terkait dengan supplier
    $purchaseOrderBBs = PurchaseOrderBB::where('supplier_id', $id)->get();

    // Kirim data ke view
    return view('purchasing.supplier.contract_detail', compact('supplier', 'purchaseOrderBBs'));
}






}
