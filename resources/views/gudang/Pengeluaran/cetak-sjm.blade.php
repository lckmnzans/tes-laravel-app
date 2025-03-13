<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Masuk (SJM)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px auto;
            width: 80%;
            border: 1px solid #000;
            padding: 20px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <h2>Surat Jalan Masuk (SJM)</h2>
            <h3>Pengeluaran Bahan Baku</h3>
        </div>

        <table>
            <tr>
                <td><strong>Nomor SJM:</strong></td>
                <td>{{ $jadwalProduksi->pengeluaranBB->kode_sjm }}</td>
            </tr>
            <tr>
                <td><strong>Nomor PO:</strong></td>
                <td>{{ $jadwalProduksi->purchaseOrder->kode_po }}</td>
            </tr>
            <tr>
                <td><strong>Nama Customer:</strong></td>
                <td>{{ $jadwalProduksi->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pengeluaran:</strong></td>
                <td>{{ $jadwalProduksi->pengeluaranBB->tanggal_pengeluaran }}</td>
            </tr>
        </table>

        <h4>Bahan Baku yang Dikeluarkan:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Bahan Baku</th>
                    <th>Jumlah Bahan Dikeluarkan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalProduksi->purchaseOrder->deliveryRequest->product as $product)
                    @foreach($product->bahanBaku as $bahan)
                        <tr>
                            <td>{{ $bahan->namaBahan }}</td>
                            <td>{{ $bahan->pivot->quantity }}</td>
                            <td>Cukup tersedia</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Catatan:</strong> Surat Jalan Masuk ini digunakan sebagai bukti pengeluaran bahan baku.</p>
            <p>Tanda Tangan:</p>
            <br><br>
            <p>(Nama Penerima, Jabatan)</p>
        </div>
    </div>
</body>
</html>
