<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Produksi</h2>
        <p><strong>Dari Tanggal:</strong> {{ $request->start_date }}</p>
        <p><strong>Sampai Tanggal:</strong> {{ $request->end_date }}</p>
        <p><strong>Kategori:</strong> {{ $request->status }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal PO</th>
                <th>Customer</th>
                <th>Model PCB</th>
                <th>Jumlah Order</th>
                <th>Durasi Produksi</th>
                <th>Status Produksi</th>
                <th>Progress (%)</th>
                <th>Catatan Penting</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productionSchedules as $index => $schedule)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $schedule->purchaseOrder->created_at->format('d/m/Y') }}</td>
                    <td>{{ $schedule->purchaseOrder->deliveryRequest->pelanggan->nama_customer }}</td>
                    <td>
                        @foreach ($schedule->purchaseOrder->deliveryRequest->product as $product)
                            {{ $product->produk }}
                        @endforeach
                    </td>
                    <td>{{ $schedule->quantity_to_produce }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($schedule->expected_finish_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ $schedule->proses }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        @switch($schedule->proses)
                            @case('prep materials')
                                20%
                                @break
                            @case('production')
                                50%
                                @break
                            @case('packaging')
                                70%
                                @break
                            @case('quality control')
                                80%
                                @break
                            @case('shipping')
                                90%
                                @break
                            @case('selesai')
                                100%
                                @break
                            @default
                                0%
                        @endswitch
                    </td>
                    <td>{{ $schedule->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
