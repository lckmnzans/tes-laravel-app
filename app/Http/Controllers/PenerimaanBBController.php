<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderBB;
use App\Models\PenerimaanBB;
use App\Models\PurchaseOrderBBItem;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerimaanBBController extends Controller
{
    /**
     * Menampilkan daftar penerimaan bahan baku yang sudah diterima atau selesai
     */
    public function index()
    {
        $purchaseOrderBBs = PurchaseOrderBB::with('items.bahanBaku', 'supplier', 'items.purchaseRequest.bahanBaku')
                    ->where('status_order', '2') // Menambahkan kondisi status
                    ->get();

        $penerimaanbbs = PenerimaanBB::with(['purchaseOrderBB', 'bahanBaku'])
            ->orderByRaw("FIELD(status, '1', '2', '3')") // Prioritaskan status
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->get();

        return view('gudang.penerimaan.index', compact('penerimaanbbs', 'purchaseOrderBBs' ));
    }


    public function show($id)
{
    $penerimaanbb = PenerimaanBB::with(['bahanBaku', 'purchaseOrderBB.supplier'])->findOrFail($id);

    return view('gudang.penerimaan.detail', [
        'penerimaanbb' => $penerimaanbb,
        'pobb' => $penerimaanbb->purchaseOrderBB,
    ]);
}
public function storeVerification(Request $request, $id)
{
    // Validasi input form termasuk file bukti
    $request->validate([
        'tanggal_terima' => 'required|date',
        'jumlah_terima' => 'required|integer|min:1',
        'reject' => 'nullable|integer|min:0',
        'lokasi_stok' => 'nullable|string|max:255',
        'status_barang' => 'required|string|in:Diterima Sebagian,Diterima Lengkap',
        'catatan' => 'nullable|string|max:500',
        'bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
    ]);

    DB::beginTransaction();

    try {
        $penerimaanbb = PenerimaanBB::find($id);

        if (!$penerimaanbb) {
            return redirect()->route('gudang.penerimaan.index')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        $pobb = $penerimaanbb->purchaseOrderBB;
        $bahanBaku = $penerimaanbb->bahanBaku;

        // Proses upload file bukti jika ada
        $buktiPath = $penerimaanbb->bukti;  // Ambil path file yang lama

        if ($request->hasFile('bukti')) {
            Log::info('File bukti ditemukan.');
            $file = $request->file('bukti');
            
            // Debug informasi file
            Log::info('Nama file asli: ' . $file->getClientOriginalName());
            Log::info('Ekstensi file: ' . $file->getClientOriginalExtension());
            Log::info('Ukuran file: ' . $file->getSize() . ' bytes');
        
            try {
                // Cek apakah file lama ada dan hapus jika ada
                if ($buktiPath && Storage::exists('public/' . $buktiPath)) {
                    Storage::delete('public/' . $buktiPath);
                    Log::info('File lama dihapus: ' . $buktiPath);
                }
        
                // Tentukan nama file baru
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Simpan file di folder 'public/dokumen_sjm'
                $filePath = $file->storeAs('public/dokumen_sjm', $fileName);
        
                // Simpan path file baru ke database
                $buktiPath = 'dokumen_sjm/' . $fileName;
        
                // Update path file di database
                $penerimaanbb->bukti = $buktiPath;
                $penerimaanbb->save();
                
                Log::info('File baru berhasil disimpan di: ' . $buktiPath);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan file: ' . $e->getMessage());
                throw $e;  // Menghentikan eksekusi jika terjadi error
            }
        }

        // Update data penerimaan
        $penerimaanbb->update([
            'tanggal_terima' => $request->tanggal_terima,
            'jumlah_terima' => $request->jumlah_terima,
            'reject' => $request->reject,
            'lokasi_stok' => $request->lokasi_stok,
            'catatan' => $request->catatan,
            'status' => '2', // Status "Diterima"
            'bukti' => $buktiPath,
        ]);

        Log::info('Data penerimaan berhasil diperbarui.');

        // Tambah stok bahan baku
        $bahanBaku->increment('stokBahan', $request->jumlah_terima);
        Log::info('Stok bahan baku bertambah.', [
            'bahan_baku_id' => $bahanBaku->id,
            'stok_akhir' => $bahanBaku->stokBahan,
        ]);

        // Cek apakah semua bahan baku sudah diterima
        $totalOrder = $pobb->items->sum('jumlah_order');
        $totalReceived = PenerimaanBB::where('purchase_order_bb_id', $pobb->id)->sum('jumlah_terima');

        Log::info('Perbandingan jumlah order dan penerimaan.', [
            'total_order' => $totalOrder,
            'total_received' => $totalReceived,
        ]);

        if ($totalOrder == $totalReceived) {
            $pobb->update(['status_order' => '3']); // Selesai
            Log::info('Semua bahan baku diterima. Status POBB diubah menjadi selesai.', [
                'purchase_order_bb_id' => $pobb->id,
            ]);
        }

        DB::commit();
        Log::info('Transaksi berhasil, redirect ke index.');

        return redirect()->route('gudang.penerimaan.index')->with('success', 'Penerimaan bahan baku berhasil diverifikasi.');
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error saat verifikasi penerimaan bahan baku: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e,
        ]);
        return redirect()->route('gudang.penerimaan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    /**
     * Menampilkan detail penerimaan bahan baku
     */


}






