<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 30px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .header p {
            font-size: 16px;
            color: #777;
        }

        .details {
            margin-bottom: 30px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table td {
            padding: 8px;
            font-size: 14px;
            color: #555;
        }

        .details table td:first-child {
            font-weight: bold;
            width: 30%;
        }

        .details table td:last-child {
            text-align: left;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 30px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .thank-you {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }

        .signature span {
            display: block;
            margin-top: 30px;
            border-top: 1px solid #444;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kwitansi Pembayaran</h1>
            <p>Tanggal: {{ $payment->created_at->format('d-m-Y') }}</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td>Nama Customer</td>
                    <td>: {{ $payment->invoice->purchaseOrder->deliveryRequest->pelanggan->nama_customer ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Jumlah Dibayar</td>
                    <td>: Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>: {{ $payment->method }}</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>: {{ $payment->description }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p class="thank-you">Terima kasih telah melakukan pembayaran.</p>
            <p>PT KATOLEC INDONESIA</p>
        </div>
    </div>
</body>
</html>
