<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengiriman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="container mx-auto p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-indigo-600">Surat Pengiriman</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-xl font-semibold mb-4">Detail Pengiriman</p>
        <table class="min-w-full border-collapse table-auto mb-6">
            <tr class="border-b">
                <th class="px-4 py-2 text-left font-medium">Nomor Surat Jalan</th>
                <td class="px-4 py-2">{{ $schedule->pengeluaranBB->kode_sjm ?? 'Belum tersedia' }}</td>
            </tr>
            <tr class="border-b">
                <th class="px-4 py-2 text-left font-medium">Tanggal Pengiriman</th>
                <td class="px-4 py-2">
                    {{ $schedule->shipping_completed_at 
                        ? \Carbon\Carbon::parse($schedule->shipping_completed_at)->format('d F Y') 
                        : 'Tidak tersedia' }}
                </td>
            </tr>
            <tr class="border-b">
                <th class="px-4 py-2 text-left font-medium">Nama Customer</th>
                <td class="px-4 py-2">{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr class="border-b">
                <th class="px-4 py-2 text-left font-medium">Alamat Pengiriman</th>
                <td class="px-4 py-2">{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->alamat ?? 'Tidak tersedia' }}</td>
            </tr>
        </table>

        <p class="text-xl font-semibold mb-4">Rincian Produk</p>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left font-medium">Produk</th>
                    <th class="px-4 py-2 text-left font-medium">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedule->purchaseOrder->deliveryRequest->product as $product)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $product->produk }}</td>
                    <td class="px-4 py-2">{{ $product->pivot->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 text-center">
        <p class="text-lg">Terimakasih atas kerjasama Anda.</p>
    </div>

    <div class="flex justify-around mt-12">
        <div class="text-center">
            <p class="font-medium">Yang Menerima,</p>
            <div class="border-t-2 border-gray-800 w-40 mx-auto mt-2"></div>
            <p>(Nama Penerima)</p>
        </div>

        <div class="text-center">
            <p class="font-medium">Yang Mengirim,</p>
            <div class="border-t-2 border-gray-800 w-40 mx-auto mt-2"></div>
            <p>(Nama Pengirim)</p>
        </div>
    </div>
</div>

</body>
</html>
