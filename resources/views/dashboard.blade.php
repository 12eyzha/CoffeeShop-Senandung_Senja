@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">

    <!-- Header Dashboard -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-amber-900">Dashboard</h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">Selamat datang, <strong>{{ Auth::user()->name }}</strong></span>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md shadow transition-all duration-300">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h3>
                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-receipt text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Transaksi</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $todayTransactions }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                <i class="fas fa-coffee text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Menu Tersedia</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $availableMenus }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-star text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Kategori Terpopuler</h3>
                <p class="text-2xl font-semibold text-gray-900">Coffee</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Populer -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Menu Populer</h2>
            </div>
            <div class="p-6 space-y-6">
                @foreach([
                    ['Brown Sugar Matcha', 'Matcha lembut dan manis, cocok untuk kamu yang ingin rasa calming tapi fresh.', 15000],
                    ['Espresso Original', 'Kopi espresso murni dengan rasa bold dan kuat, dibuat dari biji pilihan.', 18000],
                    ['Latte Art Special', 'Espresso dengan steamed milk creamy dan latte art cantik.', 25000],
                ] as $menu)
                <div class="flex items-start space-x-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
                    <div class="flex-shrink-0 w-16 h-16 bg-amber-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-coffee text-amber-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 text-lg">{{ $menu[0] }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $menu[1] }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-lg font-bold text-amber-700">Rp {{ number_format($menu[2], 0, ',', '.') }}</span>
                            <div class="flex items-center space-x-2">
                                <button class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300">
                                    <i class="fas fa-minus text-xs text-gray-700"></i>
                                </button>
                                <span class="w-8 text-center font-medium">1</span>
                                <button class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center hover:bg-amber-700">
                                    <i class="fas fa-plus text-xs text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Data Master</h2>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ url('/transactions') }}" class="flex items-center p-3 bg-amber-50 rounded-lg border border-amber-200 hover:bg-amber-100 transition-colors">
                        <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cash-register text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Buat Invoice Baru</h3>
                            <p class="text-sm text-gray-600">Transaksi penjualan baru</p>
                        </div>
                    </a>

                    <a href="{{ url('/transactions/history') }}" class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Riwayat Transaksi</h3>
                            <p class="text-sm text-gray-600">Lihat semua transaksi</p>
                        </div>
                    </a>

                    <a href="{{ url('/reports/daily') }}" class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Laporan Penjualan</h3>
                            <p class="text-sm text-gray-600">Analisis penjualan</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $transaction->transaction_code }}</h4>
                            <p class="text-sm text-gray-600">{{ $transaction->created_at->format('H:i') }} • {{ $transaction->payment_method }}</p>
                        </div>
                        <span class="font-bold text-amber-700">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-amber-50 { background-color: #fffbeb; }
.bg-amber-200 { background-color: #fde68a; }
.border-amber-200 { border-color: #fde68a; }
.text-amber-700 { color: #b45309; }
</style>
@endsection
