<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Coffee Shop Senandung Senja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <nav class="bg-amber-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-coffee text-2xl"></i>
                    <h1 class="text-xl font-bold">Coffee Shop Senandung Senja</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    <span>Halo, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-amber-700 hover:bg-amber-800 px-4 py-2 rounded">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Navigation -->
    @auth
    <div class="bg-amber-800 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex space-x-8 py-3">
                <a href="{{ url('/dashboard') }}" class="hover:bg-amber-700 px-4 py-2 rounded {{ request()->is('dashboard') ? 'bg-amber-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ url('/transactions') }}" class="hover:bg-amber-700 px-4 py-2 rounded {{ request()->is('transactions') ? 'bg-amber-700' : '' }}">
                    <i class="fas fa-cash-register mr-2"></i>Transaksi
                </a>
                <a href="{{ url('/transactions/history') }}" class="hover:bg-amber-700 px-4 py-2 rounded {{ request()->is('transactions/history') ? 'bg-amber-700' : '' }}">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </a>
                <a href="{{ url('/reports/daily') }}" class="hover:bg-amber-700 px-4 py-2 rounded {{ request()->is('reports/*') ? 'bg-amber-700' : '' }}">
                    <i class="fas fa-chart-bar mr-2"></i>Laporan
                </a>
            </div>
        </div>
    </div>
    @endauth

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>