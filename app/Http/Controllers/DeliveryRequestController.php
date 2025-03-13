<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\PurchaseOrder;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Payment;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DeliveryRequestController extends Controller
{
    // Menampilkan semua Delivery Request
    public function index()
{
    // Ambil hanya DR yang belum memiliki PO
    $deliveryRequests = DeliveryRequest::with('pelanggan', 'product')
        ->whereDoesntHave('purchaseOrder') // Hanya DR yang belum memiliki PO
        ->orderByRaw("FIELD(status_dr, 'disetujui') DESC") // Urutkan berdasarkan status, disetujui di atas
        ->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu terlama (asc)
        ->paginate(10);

    return view('exim.deliveryrequest.index', compact('deliveryRequests'));
}

    public function create()
    {
        // Ambil daftar pelanggan dan produk
        $pelanggans = Pelanggan::all();  // Ambil data pelanggan
        $products = Product::all();      // Ambil data produk

        return view('exim.deliveryrequest.create', compact('pelanggans', 'products'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'pelanggan_id' => 'required|exists:pelanggans,id',
        'products' => 'required|array',  // Pastikan array produk diterima
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    // Buat Delivery Request baru
    $lastDeliveryRequest = DeliveryRequest::orderBy('id', 'desc')->first();
    $lastNoDR = $lastDeliveryRequest ? $lastDeliveryRequest->no_dr : '0000';
    $newNoDR = '0' . str_pad(substr($lastNoDR, 3) + 1, 4, '0', STR_PAD_LEFT);

    $deliveryRequest = new DeliveryRequest();
    $deliveryRequest->no_dr = $newNoDR;
    $deliveryRequest->pelanggan_id = $request->pelanggan_id;
    $deliveryRequest->status_dr = 'tertunda';
    $deliveryRequest->status_po = 'tertunda';
    $deliveryRequest->status_invoice = 'tertunda';
    $deliveryRequest->total_harga = 0;
    $deliveryRequest->save(); // Simpan DR terlebih dahulu

    $totalHarga = 0;

    // Menyimpan data produk dan menghitung total harga
    foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']);
        
        // Periksa jika produk tidak ditemukan
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        // Hitung total harga per produk
        $totalPrice = $product->harga * $productData['quantity'];

        // Menyimpan data pivot ke tabel pivot (misalnya delivery_request_product)
        $deliveryRequest->product()->attach($productData['product_id'], [
            'quantity' => $productData['quantity'],
            'total_price' => $totalPrice
        ]);

        // Tambahkan total harga per produk ke total keseluruhan
        $totalHarga += $totalPrice;
    }

    // Update total_harga pada delivery_request
    $deliveryRequest->total_harga = $totalHarga;
    $deliveryRequest->save();  // Simpan perubahan

    // Kembalikan response berhasil
    return redirect()->route('exim.deliveryrequest.index');  // Pastikan ini sesuai dengan route yang ada
}



    // Mengubah status DR menjadi Approved
    public function approve($id)
{
    Log::info('Approve called for DR ID: ' . $id);
    // Mengambil data DeliveryRequest berdasarkan ID
    $deliveryRequest = DeliveryRequest::findOrFail($id);
    Log::info('Delivery Request found: ', $deliveryRequest->toArray());
    // Mengubah status DeliveryRequest menjadi "disetujui"
    $deliveryRequest->update(['status_dr' => 'disetujui']);
    Log::info('Status updated to: ' . $deliveryRequest->status_dr);
    // Hitung total harga berdasarkan produk dalam DR
    $totalHarga = 0;

    if ($deliveryRequest->product) {
        // Loop melalui produk dalam DeliveryRequest untuk menghitung total harga
        foreach ($deliveryRequest->product as $product) {
            $totalHarga += $product->harga * $product->pivot->quantity; // Hitung total harga per produk
        }
    }

    // Update total_harga pada DeliveryRequest
    $deliveryRequest->update(['total_harga' => $totalHarga]);

    // Redirect ke halaman index DR untuk manager dengan pesan sukses
    return redirect()->route('manager.persetujuandr')
        ->with('success', 'Delivery Request berhasil disetujui!');
}

        // Buat Purchase Order otomatis setelah DR disetujui
      //  $purchaseOrder = PurchaseOrder::create([
      //      'delivery_request_id' => $deliveryRequest->id,
      //      'total_amount' => $totalHarga, // Memastikan total amount terisi dengan benar
      //      'status' => 'sudah dibuat',
      //  ]);
        // Update kode_po dengan nilai no_dr yang sudah ada, hanya mengganti awalan 'DR-' menjadi 'PO-'
      //  $purchaseOrder->kode_po = 'PO-' . substr($deliveryRequest->no_dr, 3); // Mengganti 'DR-' dengan 'PO-'
      //  $purchaseOrder->save();  // Menyimpan perubahan pada kode_po

        // Membuat Invoice otomatis setelah PO dibuat
        //$invoice = Invoice::create([
          //  'purchase_order_id' => $purchaseOrder->id,
           // 'amount' => $totalHarga,
           // 'status' => 'sudah disetujui',
        //]);

        


        public function indexForManager()
        {
            // Ambil DR tertunda
            $deliveryRequests = DeliveryRequest::with('pelanggan', 'product', 'purchaseOrder')
                ->where('status_dr', 'tertunda')
                ->paginate(10, ['*'], 'pending');

            // Ambil DR disetujui tetapi belum dibuat PO
            $approvedRequests = DeliveryRequest::with('pelanggan', 'product', 'purchaseOrder')
                ->where('status_dr', 'disetujui')
                ->orderByRaw('(CASE WHEN NOT EXISTS (SELECT 1 FROM purchase_orders WHERE purchase_orders.delivery_request_id = delivery_requests.id) THEN 0 ELSE 1 END) ASC') // Data tanpa PO di atas
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'approved');
                
            $productionData = ProductionSchedule::selectRaw('proses, COUNT(*) as count')
                ->groupBy('proses')
                ->get()
                ->keyBy('proses'); // Mengubah menjadi key-value untuk akses cepat

            // Ambil data proses produksi 2
            $todayProductionCount = ProductionSchedule::where('proses', '2')
                ->whereDate('updated_at', now()->format('Y-m-d'))
                ->count();

            // Set daily production limit
            $dailyProductionLimit = 2;

            // Check if today's production count exceeds or meets the limit
            $isProductionSlotFull = $todayProductionCount >= $dailyProductionLimit;
                $statusMap = [
                    '1' => 'Prep materials',
                    '2' => 'Production',
                    '3' => 'Packaging',
                    '4' => 'Quality control',
                    '5' => 'Shipping',
                    '6' => 'Selesai', // Jika ada proses lainnya
                    '0' => 'Antrian Produksi'
                ];
        // Hitung jumlah PO yang sudah dibuat
        $totalPurchaseOrders = PurchaseOrder::where('status', 'sudah dibuat')->count();

            return view('manager.persetujuandr', [
                'deliveryRequests' => $deliveryRequests,
                'approvedRequests' => $approvedRequests,
                'productionData' => $productionData,
                'statusMap' => $statusMap,
                'totalPurchaseOrders' => $totalPurchaseOrders,
                'isProductionSlotFull' => $isProductionSlotFull, // Pass the slot full status to the view
                'dailyProductionLimit' => $dailyProductionLimit,
            ]);
        }

        

        public function indexPO()
        {
            // Ambil semua PurchaseOrder dengan status 'sudah dibuat' dan eager load pelanggan serta produk
            $purchaseOrders = PurchaseOrder::with(['deliveryRequest.product', 'deliveryRequest.pelanggan', 'product.bahanBaku'])
            ->orderByRaw("FIELD(status, 'tertunda') DESC")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            

            // Kirim data ke view
            return view('exim.purchaseorder.index', compact('purchaseOrders'));
        }



    public function createPurchaseOrder(Request $request, $id)
    {
        // Cari Delivery Request berdasarkan ID
        $deliveryRequest = DeliveryRequest::findOrFail($id);

        // Pastikan status DR adalah 'disetujui'
        if ($deliveryRequest->status_dr !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Delivery Request belum disetujui.',
            ], 400);
        }

        // Mulai transaksi untuk memastikan atomik
        DB::beginTransaction();

        try {
            // Hitung total harga dari produk
            $totalAmount = $deliveryRequest->product->sum(function ($product) {
                return $product->harga * $product->pivot->quantity;
            });

            // Buat Purchase Order
            $purchaseOrder = PurchaseOrder::create([
                'delivery_request_id' => $deliveryRequest->id,
                'total_amount' => $totalAmount,
                'status' => 'tertunda',
            ]);

            // Update kode_po dengan mengganti 'DR-' menjadi 'PO-'
            $purchaseOrder->kode_po = 'P0' . substr($deliveryRequest->no_dr, 3); // Mengganti 'DR-' dengan 'PO-'
            $purchaseOrder->save(); // Simpan perubahan

            // Buat Invoice otomatis
            $invoice = Invoice::create([
                'purchase_order_id' => $purchaseOrder->id,
                'amount' => $totalAmount,
                'status' => 'belum lunas',
            ]);

            $deliveryRequest->update([
                'status_po' => 'sudah dibuat',
                'status_invoice' => 'sudah dibuat',
            ]);

            // Commit transaksi
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO dan Invoice berhasil dibuat.',
                'data' => [
                    'purchase_order_id' => $purchaseOrder->id,
                    'invoice_url' => route('exim.deliveryrequest.show', $purchaseOrder->id),
                ],
            ]);
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat PO dan Invoice.',
            ], 500);
        }
    }

    public function show($id)
    {
        // Ambil data PurchaseOrder berdasarkan ID
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Ambil data invoice terkait PO
        $invoice = $purchaseOrder->invoice;

        // Logika penghitungan pajak
        $taxRate = 0.11; // Contoh: Pajak 10%
        $taxAmount = $purchaseOrder->total_amount * $taxRate; // Pajak
        $totalWithTax = $purchaseOrder->total_amount + $taxAmount; // Total setelah pajak

        // Kirim data ke view
        return view('exim.deliveryrequest.show', compact('purchaseOrder', 'invoice', 'taxAmount', 'totalWithTax'));
    }


        // Membayar Invoice
        public function payInvoice(Request $request, $Id)
{
    // Validasi input
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'method' => 'required|string',
        'dokumen' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Validasi file bukti bayar
    ]);

    // Ambil invoice berdasarkan ID
    $invoice = Invoice::with('payments')->findOrFail($Id);

    // Ambil data PurchaseOrder terkait
    $purchaseOrder = $invoice->purchaseOrder;

    // Total tagihan yang harus dibayar
    $totalAmount = $purchaseOrder->total_amount;

    // Jumlah pembayaran yang sudah dilakukan
    $totalPayments = $invoice->payments->sum('amount');
    $remainingAmount = $totalAmount - $totalPayments;

    // Validasi jumlah pembayaran
    if ($request->input('amount') > $remainingAmount) {
        return back()->withErrors(['amount' => 'Jumlah pembayaran tidak bisa melebihi sisa tagihan.'])->withInput();
    }

    // Tentukan status invoice
    $newTotalPayments = $totalPayments + $request->input('amount');
    if ($newTotalPayments >= $totalAmount) {
        $invoice->status = 'lunas';
        $paymentDescription = 'Lunas';
    } else {
        $invoice->status = 'termin';
        $paymentDescription = 'Termin Ke- ' . ($invoice->payments->count() + 1);
    }

    // Pastikan folder `payments` ada
    if (!Storage::disk('public')->exists('payments')) {
        Storage::disk('public')->makeDirectory('payments');
    }

    // Simpan file bukti bayar
    $dokumen = $request->file('dokumen')->store('payments', 'public');

    
    // Simpan pembayaran baru
    $payment = new Payment();
    $payment->invoice_id = $invoice->id;
    $payment->amount = $request->input('amount');
    $payment->method = $request->input('method');
    $payment->description = $paymentDescription;
    $payment->dokumen = $dokumen; // Path file
    $payment->save();

    // Simpan perubahan status invoice
    $invoice->save();
// Buat PDF kwitansi
$pdf = Pdf::loadView('exim.deliveryrequest.receipt', compact('payment'));

// Simpan kwitansi di server (opsional)
$receiptPath = 'receipts/receipt-' . $payment->id . '.pdf';
Storage::disk('public')->put($receiptPath, $pdf->output());

// Redirect ke halaman detail dengan opsi unduh kwitansi
return redirect()->route('exim.deliveryrequest.show', $purchaseOrder->id)
                 ->with('success', 'Pembayaran berhasil diperbarui. Kwitansi telah dibuat.')
                 ->with('receipt_url', asset('storage/' . $receiptPath));
}

public function updatePayment(Request $request)
{
    // Validasi input
    $request->validate([
        'payment_id' => 'required|exists:payments,id', // Pastikan ID pembayaran valid
        'amount' => 'required|numeric|min:1',
        'method' => 'required|string',
        'description' => 'nullable|string|max:255',
    ]);

    // Ambil data pembayaran berdasarkan ID
    $payment = Payment::findOrFail($request->input('payment_id'));

    // Perbarui data yang diinginkan
    $payment->amount = $request->input('amount');
    $payment->method = $request->input('method');
    $payment->description = $request->input('description', $payment->description); // Jika kosong, gunakan nilai lama
    $payment->save();

    // Redirect ke halaman sebelumnya dengan pesan sukses
    return redirect()->back()->with('success', 'Data pembayaran berhasil diperbarui.');
}

public function deletePayment($id)
{
    $payment = Payment::findOrFail($id);
    
    // Hapus file bukti bayar jika ada
    if ($payment->dokumen && Storage::disk('public')->exists($payment->dokumen)) {
        Storage::disk('public')->delete($payment->dokumen);
    }

    // Hapus data pembayaran
    $payment->delete();

    return redirect()->back()->with('success', 'Pembayaran berhasil dihapus.');
}

public function downloadReceipt($id)
{
    // Ambil data pembayaran berdasarkan ID
    $payment = Payment::with('invoice.purchaseOrder.deliveryRequest.pelanggan')->findOrFail($id);

    // Buat PDF dari template kwitansi
    $pdf = Pdf::loadView('exim.deliveryrequest.receipt', compact('payment'));

    // Unduh PDF
    return $pdf->download('kwitansi-' . $payment->id . '.pdf');
}


        
        public function print($id)
{
    $invoice = Invoice::with(['payments', 'purchaseOrder.deliveryRequest.pelanggan', 'purchaseOrder.deliveryRequest.product'])
        ->findOrFail($id);

    $pdf = Pdf::loadView('exim.deliveryrequest.invoice', compact('invoice'));
    return $pdf->stream('invoice_' . $id . '.pdf');
}
    }
