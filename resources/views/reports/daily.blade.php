@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-semibold text-amber-900 mb-4">📅 Laporan Penjualan Harian</h2>

    {{-- 🔹 Tombol Export Excel --}}
    <div class="flex justify-end mb-4">
        <a href="{{ route('reports.export.daily') }}"
           class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition text-sm">
           <i class="fas fa-file-excel mr-2"></i>Export Excel
        </a>
    </div>

    {{-- 🔹 Ringkasan --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-amber-100 rounded-lg p-4 text-center">
            <h3 class="text-sm text-gray-700">Total Transaksi</h3>
            <p class="text-2xl font-bold text-amber-900">{{ $summary['total_transactions'] }}</p>
        </div>
        <div class="bg-amber-100 rounded-lg p-4 text-center">
            <h3 class="text-sm text-gray-700">Total Pendapatan</h3>
            <p class="text-2xl font-bold text-green-700">
                Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-amber-100 rounded-lg p-4 text-center">
            <h3 class="text-sm text-gray-700">Tanggal</h3>
            <p class="text-2xl font-bold text-amber-900">{{ \Carbon\Carbon::parse($today)->format('d M Y') }}</p>
        </div>
    </div>

    {{-- 🔹 Daftar Transaksi --}}
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Kode Transaksi</th>
                    <th class="px-4 py-2 text-left">Kasir</th>
                    <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                    <th class="px-4 py-2 text-right">Total</th>
                    <th class="px-4 py-2 text-center">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                <tr class="border-b hover:bg-amber-50">
                    <td class="px-4 py-2">{{ $trx->transaction_code }}</td>
                    <td class="px-4 py-2">{{ $trx->user->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($trx->payment_method) }}</td>
                    <td class="px-4 py-2 text-right">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 text-center">{{ $trx->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-3">
                        Tidak ada transaksi hari ini 😴
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔹 Produk Terjual --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h2 class="text-lg font-semibold text-amber-900 mb-3">☕ Produk Terjual Hari Ini</h2>
        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Produk</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-b hover:bg-amber-50">
                    <td class="px-4 py-2">{{ $product['name'] }}</td>
                    <td class="px-4 py-2">{{ $product['category'] }}</td>
                    <td class="px-4 py-2 text-center">{{ $product['quantity'] }}</td>
                    <td class="px-4 py-2 text-right">
                        Rp {{ number_format($product['total'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-3">
                        Belum ada produk terjual hari ini 😴
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔹 Grafik Pendapatan Harian --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold text-amber-900 mb-3">📊 Grafik Pendapatan Hari Ini</h2>
        <canvas id="dailyChart" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxDaily = document.getElementById('dailyChart');
new Chart(ctxDaily, {
    type: 'bar',
    data: {
        labels: @json($transactions->pluck('created_at')->map(fn($d) => $d->format('H:i'))),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json($transactions->pluck('total_amount')),
            backgroundColor: 'rgba(203, 138, 92, 0.8)',
            borderColor: '#6B3E26',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
