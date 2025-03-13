<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jadwal Produksi</title>
    <style>
        /* Font & Style */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
            margin: 0;
            padding: 5px 0;
            font-size: 18px;
        }

        h3 {
            margin: 0;
            padding: 5px 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .header-table td {
            border: none;
            padding: 5px 10px;
        }

        .signature-table td {
            height: 50px;
            text-align: center;
            border: none;
        }

        .no-border {
            border: none !important;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .table-section {
            margin-bottom: 10px;
        }

        .small-text {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <h1>Jadwal Produksi</h1>
    <h2>PT Katolec Indonesia</h2>

    <!-- Informasi Umum -->
    <table class="header-table">
        <tr>
            <td><strong>Kode Produksi:</strong> {{ $jadwalProduksi->kode }}</td>
            <td class="text-right"><strong>Tanggal:</strong> {{ now()->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Nomor PO:</strong> {{ $jadwalProduksi->purchaseOrder->kode_po }}</td>
            <td class="text-right"><strong>Customer:</strong> {{ $jadwalProduksi->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
        </tr>
    </table>

    <!-- Detail Produk -->
    <h3>Detail Produk</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwalProduksi->purchaseOrder->deliveryRequest->product as $product)
            <tr>
                <td>{{ $product->produk }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ $jadwalProduksi->description ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detail Bahan Baku -->
    <h3>Detail Bahan Baku</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Bahan Baku</th>
                <th>Jumlah Dibutuhkan</th>
                <th>Stok Tersedia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwalProduksi->purchaseOrder->deliveryRequest->product as $product)
                @foreach($product->bahanBaku as $bahan)
                <tr>
                    <td>{{ $bahan->namaBahan }}</td>
                    <td>{{ $bahan->pivot->quantity }}</td>
                    <td>{{ $bahan->stokBahan }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Detail Proses -->
    <h3>Detail Proses Produksi</h3>
    <table>
        <thead>
            <tr>
                <th>Proses</th>
                <th>Target Penyelesaian</th>
                <th>Tanggal Selesai</th>
                <th>Paraf Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Prep Materials</td>
                <td>{{ \Carbon\Carbon::parse($jadwalProduksi->target_prep_materials)->format('d-m-Y') }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Production</td>
                <td>{{ \Carbon\Carbon::parse($jadwalProduksi->target_production)->format('d-m-Y') }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Packaging</td>
                <td>{{ \Carbon\Carbon::parse($jadwalProduksi->target_packaging)->format('d-m-Y') }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Quality Control</td>
                <td>{{ \Carbon\Carbon::parse($jadwalProduksi->target_quality_control)->format('d-m-Y') }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td>{{ \Carbon\Carbon::parse($jadwalProduksi->target_shipping)->format('d-m-Y') }}</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan 
    <h3>Penanggung Jawab</h3>
    <table class="signature-table">
        <tr>
            <td>Disiapkan Oleh</td>
            <td>Disetujui Oleh</td>
            <td>Diketahui Oleh</td>
        </tr>
        <tr>
            <td>________________</td>
            <td>________________</td>
            <td>________________</td>
        </tr>
        <tr>
            <td>(PPIC)</td>
            <td>(Manajer Produksi)</td>
            <td>(Manajer Operasional)</td>
        </tr>
    </table>-->
</body>
</html>
