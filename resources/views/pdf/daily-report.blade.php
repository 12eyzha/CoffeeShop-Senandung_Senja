<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian - {{ now()->format('d/m/Y') }}</title>
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
            font-size: 20px;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #d4af37;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #d4af37;
            color: white;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HARIAN PENJUALAN</h1>
        <h2>COFFEE SHOP SENANDUNG SENJA</h2>
        <p>Periode: {{ now()->format('d F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div>Total Transaksi</div>
            <div class="summary-value">{{ $totalTransactions }}</div>
        </div>
        <div class="summary-item">
            <div>Total Pendapatan</div>
            <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Kasir</th>
                <th>Items</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>
                    @php
                        $items = json_decode($transaction->items, true);
                        $itemNames = array_map(function($item) {
                            return $item['name'] . ' (x' . $item['quantity'] . ')';
                        }, $items);
                    @endphp
                    {{ implode(', ', $itemNames) }}
                </td>
                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td>{{ strtoupper($transaction->payment_method) }}</td>
                <td>{{ $transaction->created_at->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>www.senandungsenja.com</p>
    </div>
</body>
</html>