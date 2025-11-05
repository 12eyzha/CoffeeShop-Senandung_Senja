<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
</head>
<body>
    <h2 style="text-align: center; font-weight: bold;">📊 LAPORAN PENJUALAN</h2>
    <p style="text-align: center;">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    <br>

    {{-- 🔹 Ringkasan Statistik --}}
    <table style="border-collapse: collapse; width: 60%; margin-bottom: 25px;">
        <tr>
            <th style="text-align: left;">Total Transaksi</th>
            <td>{{ $summary['total_transactions'] ?? 0 }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Total Item Terjual</th>
            <td>{{ $summary['total_items_sold'] ?? 0 }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Total Pendapatan</th>
            <td>Rp {{ number_format($summary['total_income'] ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- 🔹 Daftar Produk Terjual --}}
    <h3 style="margin-bottom: 5px;">🛒 Produk Terjual</h3>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #4472C4; color: white; text-align: center;">
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Jumlah Terjual</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $product['name'] }}</td>
                    <td style="text-align: center;">{{ $product['category'] }}</td>
                    <td style="text-align: center;">{{ $product['quantity'] }}</td>
                    <td style="text-align: right;">Rp {{ number_format($product['total'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br><br>

    {{-- 🔹 Daftar Transaksi --}}
    <h3 style="margin-bottom: 5px;">📄 Detail Transaksi</h3>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #4472C4; color: white; text-align: center;">
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Item Dibeli</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $trx->code ?? '-' }}</td>
                    <td style="text-align: center;">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach($trx->items as $item)
                            {{ $item['name'] ?? '-' }} (x{{ $item['quantity'] ?? 0 }})<br>
                        @endforeach
                    </td>
                    <td style="text-align: right;">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
