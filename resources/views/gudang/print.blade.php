<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventori</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Inventori</h2>
    <p><strong>Dari Tanggal:</strong> {{ $startDate }}</p>
    <p><strong>Sampai Tanggal:</strong> {{ $endDate }}</p>
    <p><strong>Kategori:</strong> {{ $category }}</p>

    @if ($category === 'penerimaan' || $category === 'Semua')
        <h3>Penerimaan Bahan Baku</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Baku</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Jumlah</th>
                    <th>Lokasi Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penerimaan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($item->bahanBaku)->namaBahan ?? 'Data tidak tersedia' }}</td>
                        <td>{{ $item->tanggal_terima }}</td>
                        <td>{{ $item->jumlah_terima }}</td>
                        <td>{{ $item->lokasi_stok ?? 'Lokasi tidak tersedia' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($category === 'pengeluaran' || $category === 'Semua')
        <h3>Pengeluaran Bahan Baku</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bahan Baku</th>
                    <th>Tanggal Pengeluaran</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengeluaran as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <ul>
                                @foreach ($item->productionSchedule->purchaseOrder->deliveryRequest->product as $product)
                                    @foreach ($product->bahanBaku as $bahan)
                                        <li>{{ $bahan->namaBahan }}: {{ $bahan->pivot->quantity }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        <td>{{ $item->tanggal_pengeluaran }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
