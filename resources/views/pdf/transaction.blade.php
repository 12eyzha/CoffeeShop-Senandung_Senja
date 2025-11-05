<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $transaction->transaction_code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #d4af37;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Monospace';
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>☕ COFFEE SHOP SENANDUNG SENJA</h1>
        <p>Podomoro Tenjo, Tigaraksa</p>
        <p>Telp: (021) 1234-5678</p>
    </div>

    <div class="invoice-info">
        <div>
            <strong>No. Invoice:</strong> {{ $transaction->transaction_code }}<br>
            <strong>Kasir:</strong> {{ $transaction->user->name }}<br>
            <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Tidak perlu json_decode karena sudah dicast di model
                $items = $transaction->items;
                $counter = 1;
            @endphp
            @foreach($items as $item)
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <h3>Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h3>
        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($transaction->payment_method) }}</p>
    </div>

    <div class="barcode">
        *** {{ $transaction->transaction_code }} ***
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        <p>www.senandungsenja.com</p>
    </div>
</body>
</html>
