<?php

namespace App\Http\Controllers;

use App\Models\Inventori;
use App\Models\PenerimaanBB;
use App\Models\PengeluaranBB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoriController extends Controller
{
    // Menampilkan daftar inventori
    public function report(Request $request)
{
    // Ambil parameter filter dari request
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $category = $request->input('category');

    // Inisialisasi variabel kosong untuk penerimaan dan pengeluaran
    $penerimaan = collect(); // Default sebagai koleksi kosong
    $pengeluaran = collect();

    // Logika berdasarkan kategori
    if ($category === 'penerimaan' || $category === 'Semua') {
        $penerimaan = PenerimaanBB::with('bahanBaku')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
            })
            ->get();
    }

    if ($category === 'pengeluaran' || $category === 'Semua') {
        $pengeluaran = PengeluaranBB::with('productionSchedule.purchaseOrder.deliveryRequest.product.bahanBaku')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]);
            })
            ->get();
    }

    // Kirim data ke view
    return view('gudang.report', compact('penerimaan', 'pengeluaran', 'startDate', 'endDate', 'category'));
}

public function printReport(Request $request)
{
    // Ambil parameter filter dari request
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $category = $request->input('category');

    // Query data berdasarkan kategori
    $penerimaan = collect(); // Kosong jika bukan kategori penerimaan
    $pengeluaran = collect();

    if ($category === 'penerimaan' || $category === 'Semua') {
        $penerimaan = PenerimaanBB::with('bahanBaku')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_terima', [$startDate, $endDate]);
            })
            ->get();
    }

    if ($category === 'pengeluaran' || $category === 'Semua') {
        $pengeluaran = PengeluaranBB::with('productionSchedule.purchaseOrder.deliveryRequest.product.bahanBaku')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]);
            })
            ->get();
    }

    // Jika ingin membuat PDF menggunakan DomPDF
    $pdf = PDF::loadView('gudang.print', [
        'penerimaan' => $penerimaan,
        'pengeluaran' => $pengeluaran,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'category' => $category,
    ]);

    // Mengembalikan stream PDF
    return $pdf->stream('laporan-inventori.pdf');
}


}
