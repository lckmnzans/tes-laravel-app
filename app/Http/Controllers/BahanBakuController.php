<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    // Menampilkan daftar bahan baku
    public function index(Request $request)
{
    $searchTerm = $request->get('searchUser'); // Ambil nilai pencarian dari input

    // Perbarui query untuk pencarian dengan kondisi yang sesuai
    $bahan_bakus = BahanBaku::when($searchTerm, function ($query) use ($searchTerm) {
        return $query->where('kodeBahan', 'like', "%{$searchTerm}%")
                     ->orWhere('namaBahan', 'like', "%{$searchTerm}%")
                     ->orWhere('jenis_tpb', 'like', "%{$searchTerm}%")
                     ->orWhere('satuan', 'like', "%{$searchTerm}%");
    })->paginate(10); // Menampilkan 10 data per halaman (bisa disesuaikan)

    // Mendapatkan semua bahan baku untuk daftar pilihan
    $bahanBakuList = BahanBaku::all();

    // Ambil bahan baku yang stoknya di bawah atau mendekati minimum
    $lowStockMaterials = BahanBaku::whereColumn('stokBahan', '<=', 'stok_minimum')->get();

    return view('gudang.bahanbaku.index', compact('bahan_bakus', 'bahanBakuList', 'lowStockMaterials'));
}


    // Menampilkan daftar bahan baku yang mendekati stok minimum
    public function stokMinimum()
{
    // Ambil bahan baku dengan stok di bawah atau sama dengan batas minimum
    $lowStockMaterials = BahanBaku::whereColumn('stokBahan', '<=', 'stok_minimum')
    ->get();


    // Tambahkan status PR (sudah diajukan atau belum)
    $lowStockMaterials = $lowStockMaterials->map(function ($bahan) {
        $bahan->status_pr = PurchaseRequest::where('bahan_baku_id', $bahan->id)
            ->where('status', '!=', 'selesai') // Status PR selain selesai
            ->exists() ? 'Sudah Diajukan' : 'Belum Diajukan';

        return $bahan;
    });

    return view('gudang.bahanbaku.stokminimum', compact('lowStockMaterials'));
}



    // Menampilkan form untuk membuat bahan baku baru
    public function create()
    {
        return view('gudang.bahanbaku.create');
    }

    // Menyimpan bahan baku baru ke database
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'kodeBahan' => 'required|string|max:255|unique:bahan_bakus,kodeBahan',
        'namaBahan' => 'required|string|max:255',
        'stokBahan' => 'required|integer|min:0',
        'satuan' => 'required|string|max:255',  // Validasi untuk satuan (string)
        'stok_minimum' => 'required|integer|min:0',  // Validasi stok minimum
        'jenis_tpb' => 'nullable|string|max:255',  // Validasi untuk jenis_tpb
    ]);

    try {
        // Menyimpan data ke dalam database
        BahanBaku::create($request->all());

        return redirect()->route('gudang.bahanbaku.index')->with('status', 'Bahan Baku Berhasil Ditambahkan');
    } catch (\Exception $e) {
        // Menangani error jika gagal menyimpan
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}



    // Menampilkan detail bahan baku tertentu beserta produk terkait
    public function show($id)
    {
        $bahan_baku = BahanBaku::with('products')->findOrFail($id);
        return view('gudang.bahanbaku.show', compact('bahan_baku'));
    }

    // Menampilkan form untuk mengedit bahan baku
    public function edit($id)
{
    $bahan_baku = BahanBaku::findOrFail($id);
    return view('gudang.bahanbaku.edit', compact('bahan_baku'));
}

public function update(Request $request, $id)
{
    $bahan_baku = BahanBaku::findOrFail($id);

    // Aturan validasi
    $request->validate([
        'kodeBahan' => 'required|string|max:255',
        'namaBahan' => 'required|string|max:255',
        'stokBahan' => 'required|integer|min:0',
        'satuan' => 'required|string|max:255',  // Ganti hargaBahan dengan satuan
        'stok_minimum' => 'required|integer|min:0',
        'jenis_tpb' => 'nullable|string|max:255', // Validasi untuk jenis_tpb
    ]);

    // Memperbarui data bahan baku
    $bahan_baku->update([
        'kodeBahan' => $request->kodeBahan,
        'namaBahan' => $request->namaBahan,
        'stokBahan' => $request->stokBahan,
        'satuan' => $request->satuan,  // Ganti hargaBahan dengan satuan
        'stok_minimum' => $request->stok_minimum,
        'jenis_tpb' => $request->jenis_tpb,  // Update jenis_tpb
    ]);

    return redirect()->route('gudang.bahanbaku.index')->with('status', 'Bahan Baku Berhasil Diperbarui');
}


    // Menghapus bahan baku
    public function destroy($id)
    {
        $bahan_baku = BahanBaku::findOrFail($id);
        $bahan_baku->products()->detach(); // Hapus relasi dengan produk di tabel pivot
        $bahan_baku->delete();

        return redirect()->route('gudang.bahanbaku.index')->with('status', 'Bahan Baku Berhasil Dihapus');
    }

    //public function createPRForm()
//{
    // Ambil bahan baku dengan stok di bawah minimum
  //  $lowStockMaterials = BahanBaku::whereColumn('stokBahan', '<=', 'stok_minimum')
  //      ->whereDoesntHave('purchaseRequests', function ($query) {
  //          $query->where('status', '!=', 'selesai'); // PR yang belum selesai
  //      })
  //      ->get();
//
  //  return view('gudang.pr.createpr', compact('lowStockMaterials'));
//}




    //public function createPR(Request $request)
    //{
      //  $materialIds = $request->input('material_id');

        //foreach ($materialIds as $id) {
          //  $material = BahanBaku::find($id);

            // Buat PR baru untuk bahan baku yang stoknya mendekati minimum
            //PurchaseRequest::create([
              //  'bahan_baku_id' => $material->id,
                //'jumlah_request' => 100, // Jumlah permintaan bisa disesuaikan sesuai kebutuhan
                //'status' => 'pending',
            //]);
        //}

        //return redirect()->route('gudang.bahanbaku.stok_minimum')->with('status', 'Purchase Request berhasil dibuat.');
    //}


}
