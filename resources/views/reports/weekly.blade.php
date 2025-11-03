@extends('layouts.app')
@section('title', 'Laporan Mingguan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold text-amber-900 mb-4">
        Laporan Mingguan ({{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M Y') }})
    </h1>

    <!-- 🔹 Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Transaksi</p>
            <p class="text-2xl font-bold text-amber-900">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Pendapatan</p>
            <p class="text-2xl font-bold text-amber-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
            <p class="text-sm text-gray-600">Total Item Terjual</p>
            <p class="text-2xl font-bold text-amber-900">{{ $totalItemsSold }}</p>
        </div>
    </div>

    <!-- 🔹 Tabel Transaksi -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full border border-gray-300 text-sm text-gray-700">
            <thead class="bg-amber-900 text-white">
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
                    <td class="px-3 py-2">{{ $trx->transaction_code ?? 'TRX-' . str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-3 py-2">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-3 py-2">{{ $trx->user->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-1 rounded text-white 
                            {{ $trx->payment_method === 'cash' ? 'bg-green-600' : 'bg-blue-600' }}">
                            {{ strtoupper($trx->payment_method) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada transaksi dalam minggu ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔹 Grafik Pendapatan Mingguan -->
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold text-amber-900 mb-3">Grafik Pendapatan Mingguan</h2>
        <canvas id="weeklyChart" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxWeekly = document.getElementById('weeklyChart');
new Chart(ctxWeekly, {
    type: 'line',
    data: {
        labels: @json(
            $transactions
                ->groupBy(fn($trx) => $trx->created_at->format('l'))
                ->keys()
        ),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json(
                $transactions
                    ->groupBy(fn($trx) => $trx->created_at->format('l'))
                    ->map(fn($g) => $g->sum('total_amount'))
                    ->values()
            ),
            borderColor: '#C47B42',
            backgroundColor: 'rgba(203, 138, 92, 0.2)',
            fill: true,
            tension: 0.4,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
