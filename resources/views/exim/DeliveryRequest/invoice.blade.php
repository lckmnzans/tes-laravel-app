<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header .left-content {
            text-align: left;
        }
        .header h2 {
            margin: 10px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 12px;
            line-height: 1.2;
        }
        .table th {
            background-color: #f4f4f4;
        }

        /* Tabel untuk kolom dalam */
        .inner-table td {
            border: none; /* Menghilangkan border dalam tabel */
            padding: 4px 8px;
            font-size: 12px;
            line-height: 1.2;
        }

        /* Border hanya pada bagian luar untuk informasi invoice dan customer */
        .outer-border {
            border-top: 2px solid black; /* Border atas */
            border-left: 2px solid black; /* Border kiri */
            border-right: 2px solid black; /* Border kanan */
        }

        /* Untuk menambah jarak antar kolom */
        td {
            padding-right: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section p {
            margin: 5px 0;
        }

        .info-section h3 {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .signature p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* Style untuk bank account section */
        .bank-info {
            border-top: 1px solid gray;
            border-left: 1px solid gray;
            border-right: 1px solid gray;
            border-bottom: 1px solid gray;
            padding-left: 5px;
            padding-top: 5px;
            padding-right: 5px;
            padding-bottom: 5px;
            margin-bottom: 5px; /* Reduced margin */
            font-size: 10px;  /* Reduced font size */
            line-height: 1; /* Slightly tighter line height */
        }

        .bank-info strong {
            font-weight: bold;
        }

        /* Padding tambahan untuk nomor rekening */
        .account-number {
            padding-left: 10px;
            padding-top: 2px; /* Reduced padding */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="left-content">
                <h2>P.T. KATOLEC INDONESIA</h2>
                <p>Factory: EJIP Industrial Park Plot 8F Cikarang Selatan, Bekasi 17550, Jawa Barat, Indonesia</p>
                <p>Phone: +62-21-897-0093 | Fax: +62-21-897-0088</p>
                <p>Email: katolec@perusahaan.com</p>
            </div>
        </div>

        <!-- Informasi Invoice dan Informasi Customer (Sejajar menggunakan table) -->
        <table class="table">
            <tr>
                <!-- Informasi Invoice -->
                <td style="width: 48%; vertical-align: top;" class="outer-border">
                    <table class="inner-table">
                        <tr>
                            <td><strong>TO</strong></td>
                            <td>: {{ $invoice->purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? 'PT ABC' }}</td>
                        </tr>
                        <tr>
                            <td><strong></strong></td>
                            <td> {{$invoice->purchaseOrder->deliveryRequest->pelanggan->alamat ?? 'Alamat Tidak Diketahui' }}, {{$invoice->purchaseOrder->deliveryRequest->pelanggan->no_hp ?? 'Nomor HP Tidak Diketahui' }}</td>
                        </tr>
                    </table>
                </td>

                <!-- Informasi Customer -->
                <td style="width: 48%; vertical-align: top;" class="outer-border">
                    <table class="inner-table">
                        <tr>
                            <td style="color: white;"><strong>NOMOR</strong></td>
                            <td><strong>Nomor Invoice</strong></td>
                            <td>: {{ $invoice->kode_invoice ?? 'INV-001' }}</td>
                        </tr>
                        <tr>
                            <td><strong></strong></td>
                            <td><strong>Tanggal </strong></td>
                            <td>: {{ $invoice->created_at->format('d-m-Y') ?? '28-11-2024' }}</td>
                        </tr>
                        <tr>
                            <td><strong></strong></td>
                            <td><strong>Nomor PO</strong></td>
                            <td>: {{ $invoice->purchaseOrder->kode_po ?? 'PO-001' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Tabel Detail Invoice -->
        
        <table class="table">
            <thead>
                <tr>
                    <th>Part Number</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga per Unit</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->purchaseOrder->deliveryRequest->product as $product)
                <tr>
                    <td>{{ $product->part_number }}</td>
                    <td>{{ $product->produk }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($product->pivot->quantity * $product->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Invoice -->
        <table class="table">
            <tr>
                <td>Total Harga</td>
                <td>Rp {{ number_format($invoice->purchaseOrder->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak (11%)</td>
                <td>Rp {{ number_format($invoice->purchaseOrder->total_amount * 0.1, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran</td>
                <td>Rp {{ number_format($invoice->purchaseOrder->total_amount * 1.1, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total (USD)</td>
                <td>
                    ${{ number_format(($invoice->purchaseOrder->total_amount * 1.1) / 15000, 2) }}
                </td>
            </tr>
        </table>        

        <!-- Status Pembayaran -->
        <p><strong>Status Pembayaran:</strong> {{ ucwords($invoice->status) ?? 'Belum Lunas' }}</p>

        <!-- Riwayat Pembayaran -->
        @if($invoice->payments->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal Pembayaran</th>
                    <th>Jumlah Dibayar</th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->method }}</td>
                    <td>{{ $payment->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Bank Account Section -->
        <div class="bank-info">
            <p><strong>Bank Account PT KATOLEC INDONESIA</strong></p>
            <p><strong>1. MUFG Bank Ltd (Jakarta Branch)</strong></p>
            <p class="account-number"><strong>A/C No. (USD):</strong> 5200-308888</p>
            <p class="account-number"><strong>A/C No. (IDR):</strong> 8100-100912</p>
            <p><strong>2. PT. BANK MEIHO INDONESIA (Jakarta Branch)</strong></p>
            <p class="account-number"><strong>A/C No. (USD):</strong> 3202910201</p>
            <p class="account-number"><strong>A/C No. (IDR):</strong> 3202910191</p>
        </div>
        
    </div>
</body>
</html>
