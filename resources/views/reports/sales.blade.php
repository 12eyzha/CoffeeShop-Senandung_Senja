@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold text-amber-900 mb-6">Laporan Penjualan</h1>

    {{-- 🔹 Tombol Export Excel --}}
    <div class="flex justify-end mb-4">
        <a href="{{ route('reports.export.excel', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]) }}"
           class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition text-sm">
           <i class="fas fa-file-excel mr-2"></i>Export Excel
        </a>
    </div>

    <!-- 🔹 Filter Tanggal -->
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label class="block text-sm text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                   class="border rounded px-3 py-2 focus:ring-amber-700 focus:border-amber-700">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                   class="border rounded px-3 py-2 focus:ring-amber-700 focus:border-amber-700">
        </div>
        <button class="bg-amber-900 text-white px-4 py-2 rounded hover:bg-amber-800 transition">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>

    <!-- 🔹 Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Transaksi</p>
            <p class="text-2xl font-bold text-amber-900">{{ $summary['total_transactions'] }}</p>
        </div>
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Pendapatan</p>
            <p class="text-2xl font-bold text-amber-900">
                Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Item Terjual</p>
            <p class="text-2xl font-bold text-amber-900">{{ $summary['total_items_sold'] }}</p>
        </div>
    </div>

    <!-- 🔹 Grafik Penjualan -->
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <h2 class="text-lg font-semibold text-amber-900 mb-3">Grafik Penjualan per Produk</h2>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    <!-- 🔹 Tabel Produk Terjual -->
    <h2 class="text-lg font-semibold text-gray-900 mb-2">Detail Penjualan per Produk</h2>
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full border border-gray-300 text-sm text-gray-700">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-3 py-2 text-left">No</th>
                    <th class="px-3 py-2 text-left">Nama Menu</th>
                    <th class="px-3 py-2 text-left">Kategori</th>
                    <th class="px-3 py-2 text-center">Jumlah Terjual</th>
                    <th class="px-3 py-2 text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productSales as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2">{{ $item['name'] }}</td>
                    <td class="px-3 py-2">{{ $item['category'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $item['quantity'] }}</td>
                    <td class="px-3 py-2 text-right">
                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔹 Riwayat Transaksi -->
    <h2 class="text-lg font-semibold text-gray-900 mb-2">Riwayat Transaksi</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Kode</th>
                    <th class="px-3 py-2 text-left">Tanggal</th>
                    <th class="px-3 py-2 text-left">Kasir</th>
                    <th class="px-3 py-2 text-right">Total</th>
                    <th class="px-3 py-2 text-center">Metode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2">
                        {{ $trx->transaction_code ?? 'TRX-' . str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-3 py-2">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-3 py-2">{{ $trx->user->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-right">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-1 rounded text-white 
                            {{ $trx->payment_method === 'cash' ? 'bg-green-600' : 'bg-blue-600' }}">
                            {{ strtoupper($trx->payment_method) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxSales = document.getElementById('salesChart');
new Chart(ctxSales, {
    type: 'bar',
    data: {
        labels: @json($productSales->pluck('name')),
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: @json($productSales->pluck('total')),
            backgroundColor: 'rgba(203, 138, 92, 0.8)',
            borderColor: '#C47B42',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush
