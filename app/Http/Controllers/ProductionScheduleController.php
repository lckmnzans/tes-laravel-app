<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\PurchaseOrder;
use App\Models\PengeluaranBB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;


class ProductionScheduleController extends Controller
{
    public function index()
    {
        // Mengambil semua PO dengan status 'approved' yang sudah disetujui oleh divisi Exim
        $purchaseOrders = PurchaseOrder::with(['deliveryRequest.product', 'deliveryRequest.pelanggan', 'product.bahanBaku','productionSchedule'])
        ->orderByRaw("FIELD(status, 'tertunda') DESC")    
        ->orderBy('created_at', 'desc')
            ->get();

        // Tambahkan status produksi berdasarkan jadwal produksi
        $purchaseOrders = $purchaseOrders->map(function ($po) {
            $po->statusProduksi = $po->productionSchedule ? 'Sudah Dijadwalkan' : 'Belum Dibuat';
            $po->actionButton = $po->productionSchedule
                ? '<a href="' . route('ppic.permintaanpesanan.index', $po->id) . '" class="px-4 py-2 bg-green-500 text-white rounded-md">Lihat Detail</a>'
                : '<a href="' . route('ppic.permintaanpesanan.create', $po->id) . '" class="px-4 py-2 bg-blue-500 text-white rounded-md">Buat Jadwal</a>';
            return $po;
        });

        return view('ppic.permintaanpesanan.index', compact('purchaseOrders'));
    }

    public function create($id)
{
    // Ambil Purchase Order
    $purchaseOrder = PurchaseOrder::with(['deliveryRequest.product'])->findOrFail($id);
    $product = $purchaseOrder->deliveryRequest->product;

    // Hitung tanggal expected finish date berdasarkan jumlah produk
    $expectedFinishDate = now()->addMonth(); // Default 1 bulan dari sekarang
    if ($purchaseOrder->jumlah_produk >= 1000) {
        $expectedFinishDate = now()->addMonths(2); // Jika lebih dari 1000, tambahkan 2 bulan
    }

    // Ambil bahan baku untuk setiap produk
    $bahanBaku = $product->map(function ($product) use ($purchaseOrder) {
        return $product->bahanBaku->map(function ($bahan) use ($product) {
            $jumlahDibutuhkan = $bahan->pivot->quantity * $product->pivot->quantity;
            return (object) [
                'nama' => $bahan->namaBahan,
                'stok_tersedia' => $bahan->stokBahan,
                'jumlah_dibutuhkan' => $jumlahDibutuhkan,
                'keterangan' => $bahan->stokBahan >= $jumlahDibutuhkan
                    ? 'Cukup tersedia'
                    : 'Stok tidak mencukupi',
            ];
        });
    })->collapse();

    // Hitung tanggal tiap proses
    $targetPrepMaterials = $expectedFinishDate->copy()->subDays(25);
    $targetProduction = $expectedFinishDate->copy()->subDays(8);
    $targetPackaging = $expectedFinishDate->copy()->subDays(6);
    $targetQualityControl = $expectedFinishDate->copy()->subDays(4);
    $targetShipping = $expectedFinishDate; // Sama dengan tanggal finish

    // Proses produksi
    $allProcesses = [
        '1' => 'Prep Material',
        '2' => 'Production',
        '3' => 'Packaging',
        '4' => 'Quality Control',
        '5' => 'Shipping',
    ];

    // Total data di tabel production_schedule
    $totalCount = DB::table('production_schedules')->count();

    // Hitung jumlah data di setiap tahap proses
    $processCounts = DB::table('production_schedules')
        ->select('proses', DB::raw('COUNT(*) as count'))
        ->groupBy('proses')
        ->get()
        ->keyBy('proses');

    // Data terkait setiap proses
    $relatedData = [];
    foreach ($allProcesses as $key => $name) {
        $relatedData[$key] = DB::table('production_schedules')
            ->where('proses', $key)
            ->get(['kode', 'proses']); // Ambil semua data sesuai proses
    }

    // Gabungkan data dengan daftar proses
    $processData = collect($allProcesses)->map(function ($name, $process) use ($processCounts, $totalCount) {
        $count = $processCounts->has($process) ? $processCounts[$process]->count : 0;

        // Tetapkan kategori warna berdasarkan proses
        $categoryColors = [
            '1' => 'success',
            '2' => 'danger',
            '3' => 'warning',
            '4' => 'primary',
            '5' => 'info',
        ];
        $category = $categoryColors[$process] ?? 'primary';

        return [
            'stage' => $name,
            'percentage' => $totalCount > 0 ? round(($count / $totalCount) * 100, 2) : 0,
            'count' => $count,
            'category' => $category,
            'process_key' => $process, // Simpan key proses untuk query di modal
        ];
    });

    // Hitung persentase hanya untuk proses yang dipilih (contoh: proses 1)
    $processKey = request()->input('process_key', '1'); // Sesuaikan dengan proses yang dipilih
    $productionData = DB::table('production_schedules')
        ->select([
            'id',
            'kode',
            'schedule_date',
            'expected_finish_date',
            'target_prep_materials',
            'target_production',
            'target_packaging',
            'target_quality_control',
            'target_shipping',
            'proses',
        ])
        ->get();

        $filteredProcessData = [];
foreach ($allProcesses as $processKey => $processName) {
    $filteredProcessData[$processKey] = $productionData->filter(function ($item) use ($processKey) {
        // Filter data berdasarkan proses
        return $item->proses == $processKey;
    })->map(function ($item) use ($processKey) {
        $scheduleDate = Carbon::parse($item->schedule_date);
        $expectedFinishDate = Carbon::parse($item->expected_finish_date);
        $totalDuration = $scheduleDate->diffInSeconds($expectedFinishDate);

        // Hitung persentase hanya untuk proses tertentu
        $processDurations = [];
        switch ($processKey) {
            case '1': // Prep Material
                $processDurations['Prep Material'] = $item->target_prep_materials
                    ? $scheduleDate->diffInSeconds(Carbon::parse($item->target_prep_materials)) / $totalDuration * 100
                    : 0;
                break;
            case '2': // Production
                $processDurations['Production'] = $item->target_production
                    ? Carbon::parse($item->target_prep_materials)->diffInSeconds(Carbon::parse($item->target_production)) / $totalDuration * 100
                    : 0;
                break;
            case '3': // Packaging
                $processDurations['Packaging'] = $item->target_packaging
                    ? Carbon::parse($item->target_production)->diffInSeconds(Carbon::parse($item->target_packaging)) / $totalDuration * 100
                    : 0;
                break;
            case '4': // Quality Control
                $processDurations['Quality Control'] = $item->target_quality_control
                    ? Carbon::parse($item->target_packaging)->diffInSeconds(Carbon::parse($item->target_quality_control)) / $totalDuration * 100
                    : 0;
                break;
            case '5': // Shipping
                $processDurations['Shipping'] = $item->target_shipping
                    ? Carbon::parse($item->target_quality_control)->diffInSeconds(Carbon::parse($item->target_shipping)) / $totalDuration * 100
                    : 0;
                break;
        }

        return [
            'kode' => $item->kode,
            'durations' => $processDurations,
        ];
    });
}

        


    // Kirim data ke view
    return view('ppic.permintaanpesanan.create', [
        'purchaseOrder' => $purchaseOrder,
        'product' => $product,
        'bahanBaku' => $bahanBaku,
        'expectedFinishDate' => $expectedFinishDate->format('Y-m-d'),
        'targetPrepMaterials' => $targetPrepMaterials->format('Y-m-d'),
        'targetProduction' => $targetProduction->format('Y-m-d'),
        'targetPackaging' => $targetPackaging->format('Y-m-d'),
        'targetQualityControl' => $targetQualityControl->format('Y-m-d'),
        'targetShipping' => $targetShipping->format('Y-m-d'),
        'processData' => $processData,
        'relatedData' => $relatedData,
        'filteredProcessData' => $filteredProcessData, // Hanya untuk proses yang dipilih
    ]);
}


    // Helper untuk label proses
    

public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'purchase_order_id' => 'required|integer|exists:purchase_orders,id',
        'quantity_to_produce' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'target_prep_materials' => 'required|date|after_or_equal:today',
        'target_production' => 'required|date|after:target_prep_materials',
        'target_packaging' => 'required|date|after:target_production',
        'target_quality_control' => 'required|date|after:target_packaging',
        'target_shipping' => 'required|date|after:target_quality_control',
        'expected_finish_date' => 'required|date|after_or_equal:today',
    ]);

    // Generate kode produksi otomatis
    $kodeProduksi = 'PROD-' . now()->format('Ymd') . $validated['purchase_order_id'];

    // Tanggal mulai produksi otomatis
    $scheduleDate = now();

    // Tanggal-tanggal proses dari input atau fallback otomatis
    $targetPrepMaterials = $validated['target_prep_materials'] ?? $scheduleDate->copy()->addDays(2)->format('Y-m-d');
    $targetProduction = $validated['target_production'] ?? $scheduleDate->copy()->addDays(10)->format('Y-m-d');
    $targetPackaging = $validated['target_packaging'] ?? $scheduleDate->copy()->addDays(15)->format('Y-m-d');
    $targetQualityControl = $validated['target_quality_control'] ?? $scheduleDate->copy()->addDays(20)->format('Y-m-d');
    $targetShipping = $validated['target_shipping'] ?? $scheduleDate->copy()->addDays(25)->format('Y-m-d');

    // Simpan data ke tabel production_schedules
    $productionSchedule = ProductionSchedule::create([
        'purchase_order_id' => $validated['purchase_order_id'],
        'schedule_date' => $scheduleDate,
        'expected_finish_date' => $validated['expected_finish_date'],
        'kode' => $kodeProduksi,
        'quantity_to_produce' => $validated['quantity_to_produce'],
        'prep_materials_completed_at' => null,
        'production_completed_at' => null,
        'packaging_completed_at' => null,
        'quality_control_completed_at' => null,
        'shipping_completed_at' => null,
        'proses' => '1',
        'target_prep_materials' => $targetPrepMaterials,
        'target_production' => $targetProduction,
        'target_packaging' => $targetPackaging,
        'target_quality_control' => $targetQualityControl,
        'target_shipping' => $targetShipping,
        'statusProduksi' => 'belum',
        'description' => $validated['description'],
    ]);

    // Update status PO menjadi 'sudah dibuat'
    $purchaseOrder = PurchaseOrder::findOrFail($validated['purchase_order_id']);
    $purchaseOrder->update([
        'status' => 'sudah dibuat',
    ]);

    return redirect()->route('ppic.permintaanpesanan.index')->with('success', 'Jadwal produksi berhasil disimpan!');
}



    public function show($id)
    {
        // Ambil data jadwal produksi berdasarkan ID
        $jadwalProduksi = ProductionSchedule::with('purchaseOrder.deliveryRequest.product.bahanBaku')->find($id);

        // Kirim data ke view
        return view('ppic.permintaanpesanan.show', compact('jadwalProduksi'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionSchedule $productionSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionSchedule $productionSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionSchedule $productionSchedule)
    {
        //
    }
    public function indexProduction(Request $request)
{
    // Ambil status dari parameter URL, default ke "Semua"
    $status = $request->query('status', 'Semua');
    $dailyProductionLimit = 2;

    // Ambil semua proses antrian
    $queuedSchedules = ProductionSchedule::where('proses', '0')
        ->orderBy('schedule_date', 'asc') // Prioritaskan schedule_date terlama
        ->get();

    $todayProductionCount = ProductionSchedule::where('proses', '2')->count();

    // Memindahkan antrian ke produksi jika slot tersedia
    foreach ($queuedSchedules as $queue) {
        if ($todayProductionCount < $dailyProductionLimit) {
            $queue->proses = '2'; // Ubah ke produksi
            $queue->save();

            $todayProductionCount++;
        } else {
            break; // Hentikan jika batas produksi tercapai
        }
    }

    // Query untuk mengambil data production schedules dengan relasi
    $query = ProductionSchedule::with([
        'purchaseOrder.deliveryRequest.pelanggan',
        'purchaseOrder.deliveryRequest.product'
    ]);

    // Filter berdasarkan status produksi jika bukan 'Semua'
    if ($status !== 'Semua') {
        if ($status === '1') {
            // Sertakan status '0' (antrian) dalam kategori "Prep Materials"
            $query->whereIn('proses', ['0', '1']);
        } else {
            $query->where('proses', $status);
        }
    }

    // Urutkan berdasarkan proses: pastikan proses antrian tampil paling atas
    $query->orderByRaw("CASE WHEN proses = 0 THEN 1 
                           WHEN proses = 1 THEN 2 
                           ELSE 3 END")
      ->orderBy('schedule_date', 'desc'); // Mengurutkan berdasarkan tanggal mulai

    // Eksekusi query dan ambil data
    $productionSchedules = $query->get();

    // Hitung total produksi harian pada tahap production
    $todayProductionCount = ProductionSchedule::where('proses', '2')
        ->whereDate('updated_at', now()->format('Y-m-d'))
        ->count();

    return view('ppic.production.index', [
        'productionSchedules' => $productionSchedules,
        'selectedStatus' => $status,
        'todayProductionCount' => $todayProductionCount,
        'dailyProductionLimit' => $dailyProductionLimit,
    ]);
}


    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'operator') {
            return redirect()->route('unauthorized')->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        // Ambil data jadwal produksi berdasarkan ID
        $schedule = ProductionSchedule::with('purchaseOrder.deliveryRequest.product.bahanBaku')->findOrFail($id);
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'sjm_document' => $schedule->proses === '1' ? 'required|mimes:pdf,jpg,png|max:2048' : 'nullable',
            'produced_quantity' => $schedule->proses === '4' ? 'required|integer|min:0' : 'nullable',
            'waste_quantity' => $schedule->proses === '4' ? 'required|integer|min:0' : 'nullable',
        ]);
        // Simpan atau timpa dokumen ke folder storage
        $fileName = $schedule->kode . '-jadwal-produksi.' . $request->file('document')->extension();
        $filePath = $request->file('document')->storeAs('public/documents', $fileName);
        $schedule->document = $filePath;
        // Simpan dokumen SJM jika proses adalah "prep materials"
        if ($schedule->proses === '1' && $request->hasFile('sjm_document')) {
            $sjmFileName = $schedule->kode . '-dokumen-sjm.' . $request->file('sjm_document')->extension();
            $sjmFilePath = $request->file('sjm_document')->storeAs('public/sjm_documents', $sjmFileName);
            $schedule->sjm_document = $sjmFilePath;
        }
        // Jika proses adalah "quality control", simpan data tambahan
        if ($schedule->proses === '4') {
            $totalQuantity = $validated['produced_quantity'] + $validated['waste_quantity'];
            if ($totalQuantity > $schedule->quantity_to_produce) {
                return redirect()->back()->with('error', 'Jumlah produk yang dihasilkan dan cacat tidak boleh melebihi jumlah produksi.');
            }
        }
        // proses shipping
        if ($schedule->proses === '5') {
            $schedule->shipping_completed_at = now();
            $schedule->document = $filePath;
        }

        // Batas maksimal jumlah produksi harian
        $dailyProductionLimit = 2;

        // Periksa total produksi pada proses "production" hari ini
        if ($schedule->proses === '1') {
            $todayProductionCount = ProductionSchedule::where('proses', '2')->count();
            if ($todayProductionCount >= $dailyProductionLimit) {
                // Set ke status antrian
                $schedule->prep_materials_completed_at = now();
                $schedule->proses = '0'; // Status antrian
                $schedule->save();
    
                return redirect()->route('ppic.production.index')->with('success', 'Proses selesai dengan status antrian.');
            } else {
                // Langsung ke produksi jika slot tersedia
                $schedule->prep_materials_completed_at = now();
                $schedule->proses = '2'; // Status production
                $schedule->save();
    
                return redirect()->route('ppic.production.index')->with('success', 'Proses selesai dan masuk ke produksi.');
            }
        }
        // Daftar urutan status produksi
        $statusList = ['1', '2', '3', '4', '5', '6'];

        // Cari index status saat ini
        $currentIndex = array_search($schedule->proses, $statusList);

        // Jika status belum di posisi terakhir, ubah ke status berikutnya
        if ($currentIndex !== false && $currentIndex < count($statusList) - 1) {
            $nextStatus = $statusList[$currentIndex + 1];

            // Simpan tanggal selesai untuk proses saat ini
            switch ($schedule->proses) {
                case '1':
                    $schedule->prep_materials_completed_at = now();
                    break;
                case '2':
                    $schedule->production_completed_at = now();
                    break;
                case '3':
                    $schedule->packaging_completed_at = now();
                    break;
                case '4':
                    $schedule->quality_control_completed_at = now();
                    break;
                case '5':
                    $schedule->shipping_completed_at = now();
                    break;
                    
            }

            // Jika status berpindah ke "production", lakukan pengurangan stok
            if ($nextStatus === '2') {
                foreach ($schedule->purchaseOrder->deliveryRequest->product as $product) {
                    foreach ($product->bahanBaku as $bahanBaku) {
                        // Hitung jumlah bahan baku yang dibutuhkan
                        $jumlahDibutuhkan = $bahanBaku->pivot->quantity * $schedule->quantity_to_produce;

                        // Periksa apakah stok mencukupi
                        if ($bahanBaku->stokBahan >= $jumlahDibutuhkan) {
                            // Kurangi stok bahan baku
                            $bahanBaku->stokBahan -= $jumlahDibutuhkan;
                            $bahanBaku->save();
                        } else {
                            // Jika stok tidak mencukupi, kembalikan dengan error
                            return redirect()->back()->with('error', 'Stok bahan baku tidak mencukupi!');
                        }
                    }
                }
            }

            // Perbarui status produksi ke tahap berikutnya
            $schedule->proses = $nextStatus;
            // Perbarui dokumen
            $schedule->document = $filePath;
            $schedule->save();

            return redirect()->route('ppic.production.index')->with('success', 'Status produksi berhasil diperbarui.');
        }

        return redirect()->route('ppic.production.index')->with('warning', 'Status produksi sudah mencapai tahap terakhir.');
    }
    public function rollbackStatus(Request $request, $id)
{
    if (auth()->user()->role !== 'operator') {
        return redirect()->route('unauthorized')->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
    }

    $schedule = ProductionSchedule::findOrFail($id);

    // Daftar urutan status produksi
    $statusMapping = [
        '1' => 'prep_materials',
        '2' => 'production',
        '3' => 'packaging',
        '4' => 'quality_control',
        '5' => 'shipping',
        '6' => 'selesai'
    ];

    $currentProses = $schedule->proses;

    if (array_key_exists($currentProses, $statusMapping)) {
        // Cari index proses saat ini dalam mapping
        $statusKeys = array_keys($statusMapping);
        $currentIndex = array_search($currentProses, $statusKeys);

        if ($currentIndex > 0) {
            $previousProses = $statusKeys[$currentIndex - 1]; // Status sebelumnya

            // Kosongkan kolom waktu selesai untuk proses saat ini hingga proses terakhir
            for ($i = $currentIndex; $i < count($statusKeys); $i++) {
                $statusColumn = $statusMapping[$statusKeys[$i]] . '_completed_at';
                if (Schema::hasColumn('production_schedules', $statusColumn)) {
                    $schedule->{$statusColumn} = null;
                }
            }

            // Rollback data jika proses saat ini memiliki tindakan khusus
            switch ($currentProses) {
                case '3': // packaging
                    $schedule->produced_quantity = 0;
                    $schedule->waste_quantity = 0;
                    break;
                case '4': // quality_control
                    // Logika rollback lainnya (jika ada)
                    break;
            }

            // Update proses ke status sebelumnya
            $schedule->proses = $previousProses;

            $schedule->save();

            return redirect()->route('ppic.production.index')->with('success', 'Status produksi berhasil dikembalikan ke tahap sebelumnya.');
        }

        return redirect()->route('ppic.production.index')->with('warning', 'Proses sudah berada di tahap pertama.');
    }

    return redirect()->route('ppic.production.index')->with('error', 'Proses tidak valid.');
}

public function docshipping($id)
{
    // Eager load purchaseOrder -> deliveryRequest -> product dengan relasi yang benar
    $schedule = ProductionSchedule::with(['purchaseOrder.deliveryRequest.product'])->findOrFail($id);

    // Ambil data yang diperlukan untuk surat pengiriman
    $deliveryNote = $schedule->pengeluaranBB->kode_sjm ?? 'Belum tersedia';
    $shippingDate = $schedule->shipping_completed_at 
                    ? \Carbon\Carbon::parse($schedule->shipping_completed_at)->format('Y-m-d') 
                    : now()->format('Y-m-d');
    $customerName = $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? 'Tidak tersedia';
    $shippingAddress = $schedule->purchaseOrder->deliveryRequest->pelanggan->alamat ?? 'Tidak tersedia';

    return view('ppic.production.docshipping', compact('schedule', 'deliveryNote', 'shippingDate', 'customerName', 'shippingAddress'));
}


    public function indexGudang()
    {
        // Ambil jadwal produksi dengan status 'prep materials'
        $jadwalProduksi = ProductionSchedule::with('purchaseOrder.deliveryRequest.product.bahanBaku')
            ->where('proses', '1')
            ->get();

        $riwayatSJM = PengeluaranBB::with(['productionSchedule.purchaseOrder.deliveryRequest.product.bahanBaku'])
            ->whereHas('productionSchedule', function ($query) {
                $query->where('proses', '!=', '1'); // Ambil data yang prosesnya bukan 'prep materials'
            })
            ->latest()
            ->get();

        return view('gudang.pengeluaran.index', compact('jadwalProduksi', 'riwayatSJM'));
    }

    public function createSJM(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:255',
        ]);
        // Ambil jadwal produksi
        $jadwalProduksi = ProductionSchedule::findOrFail($id);

        // Buat kode SJM
        $kodeSJM = 'SJ-' . now()->format('YmdHis') . '-' . $jadwalProduksi->id;

        // Simpan data ke tabel pengeluaranbb
        PengeluaranBB::create([
            'production_schedule_id' => $jadwalProduksi->id,
            'kode_sjm' => $kodeSJM,
            'tanggal_pengeluaran' => now(),
            'keterangan' =>  $request->keterangan,
        ]);

        return redirect()->route('gudang.pengeluaran.index')->with('success', 'SJM berhasil dicetak dan disimpan.');
    }

    public function cetakSJM($id)
    {
        $jadwalProduksi = ProductionSchedule::with(['pengeluaranBB', 'purchaseOrder.deliveryRequest.product.bahanBaku'])->findOrFail($id);

        $pdf = Pdf::loadView('gudang.pengeluaran.cetak-sjm', compact('jadwalProduksi'));
        return $pdf->stream('SJM-' . $jadwalProduksi->pengeluaranBB->kode_sjm . '.pdf');
    }

    public function cetakJadwal($id)
    {
        // Ambil data jadwal produksi
        $jadwalProduksi = ProductionSchedule::with('purchaseOrder.deliveryRequest.product.bahanBaku')->findOrFail($id);

        // Generate PDF (gunakan library seperti DomPDF atau SnappyPDF)
        $pdf = PDF::loadView('ppic.permintaanpesanan.print', compact('jadwalProduksi'));

        // Unduh PDF dengan nama file yang sesuai
        return $pdf->stream('jadwal-produksi-' . $jadwalProduksi->kode_produksi . '.pdf');
    }

    public function report(Request $request)
    {
        $productionSchedules = collect(); // Default kosong
    
        // Periksa apakah filter diterapkan
        if ($request->has('start_date') && $request->has('end_date') && $request->has('status')) {
            $query = ProductionSchedule::with([
                'purchaseOrder.deliveryRequest.pelanggan',
                'purchaseOrder.deliveryRequest.product'
            ]);
    
            // Filter berdasarkan tanggal
            if ($request->filled('start_date')) {
                $query->whereDate('schedule_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('schedule_date', '<=', $request->end_date);
            }
    
            // Filter berdasarkan kategori/status
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('proses', $request->status);
            }
    
            $productionSchedules = $query->get();
        }
    
        return view('ppic.production.report', compact('productionSchedules'));
    }
    
    public function printReport(Request $request)
{
    $query = ProductionSchedule::with([
        'purchaseOrder.deliveryRequest.pelanggan',
        'purchaseOrder.deliveryRequest.product'
    ]);

    // Filter berdasarkan tanggal
    if ($request->filled('start_date')) {
        $query->whereDate('schedule_date', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('schedule_date', '<=', $request->end_date);
    }

    // Filter berdasarkan kategori/status
    if ($request->filled('status') && $request->status !== 'Semua') {
        $query->where('proses', $request->status);
    }

    $productionSchedules = $query->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppic.production.printreport', compact('productionSchedules', 'request'));

    return $pdf->stream('laporan-produksi.pdf');
}


}
