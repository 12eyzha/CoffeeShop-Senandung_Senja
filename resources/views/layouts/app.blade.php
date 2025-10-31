<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Coffee Shop Senandung Senja</title>

    {{-- Font & Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background-color: #f8f8f8;
        }

        /* === SIDEBAR === */
        .sidebar {
            height: 100vh;
            width: 80px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #6B3E26 0%, #4E2A18 100%);
            transition: width 0.3s ease, box-shadow 0.3s ease;
            overflow-x: hidden;
            color: #fff;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar.open {
            width: 240px;
        }

        .toggle-btn {
            font-size: 22px;
            margin: 18px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .toggle-btn:hover {
            transform: rotate(90deg);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            flex-grow: 1;
        }

        .sidebar ul li {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: background-color 0.3s, transform 0.2s ease;
        }

        .sidebar ul li:hover {
            background-color: #C47B42;
            transform: translateX(5px);
            box-shadow: inset 0 0 5px rgba(255,255,255,0.15);
        }

        .sidebar ul li i {
            font-size: 18px;
            min-width: 30px;
            text-align: center;
        }

        .sidebar ul li span {
            opacity: 0;
            white-space: nowrap;
            transition: opacity 0.3s ease, margin-left 0.3s ease;
        }

        .sidebar.open ul li span {
            opacity: 1;
            margin-left: 12px;
        }

        /* === SUBMENU === */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, opacity 0.4s ease;
            opacity: 0;
            background-color: #825540;
            border-left: 3px solid #C47B42;
        }

        .submenu.open {
            max-height: 300px;
            opacity: 1;
        }

        .submenu a {
            display: block;
            padding: 10px 55px;
            font-size: 14px;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .submenu a:hover {
            background-color: #C47B42;
        }

        .arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .arrow.rotate {
            transform: rotate(90deg);
        }

        /* === KONTEN UTAMA === */
        .content {
            margin-left: 80px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.open ~ .content {
            margin-left: 240px;
        }
    </style>
</head>

<body>
    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
        <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" style="text-decoration:none; color:white; display:flex; align-items:center; width:100%;">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transactions.index') }}" style="text-decoration:none; color:white; display:flex; align-items:center; width:100%;">
                    <i class="fas fa-cash-register"></i><span>Transaksi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transactions.history') }}" style="text-decoration:none; color:white; display:flex; align-items:center; width:100%;">
                    <i class="fas fa-history"></i><span>Riwayat</span>
                </a>
            </li>

            <li>
                <a href="{{ route('reports.daily') }}" style="text-decoration:none; color:white; display:flex; align-items:center; width:100%;">
                    <i class="fas fa-chart-bar"></i><span>Laporan</span>
                </a>
            </li>

            {{-- Data Master --}}
            <li onclick="toggleSubmenu()" style="cursor:pointer; display:flex; align-items:center; width:100%;">
                <i class="fas fa-database"></i><span>Data Master</span>
                <i id="arrowIcon" class="fas fa-chevron-right arrow"></i>
            </li>

            <ul class="submenu" id="submenu">
                <li><a href="{{ route('employees.index') }}">Data Karyawan</a></li>
                <li><a href="{{ route('customers.index') }}">Data Pelanggan</a></li>
                <li><a href="#">Data Menu</a></li>
            </ul>

            <li>
                <a href="#" style="text-decoration:none; color:white; display:flex; align-items:center; width:100%;">
                    <i class="fas fa-cog"></i><span>Setting</span>
                </a>
            </li>
        </ul>

        {{-- ✅ Tombol Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="m-4 mt-auto">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-3 bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-md transition-all duration-300">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    {{-- Konten Halaman --}}
    <div class="content">
        <h1 class="text-2xl font-bold text-amber-900 mb-4">@yield('title')</h1>
        @yield('content')
    </div>

    {{-- Script --}}
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }

        function toggleSubmenu() {
            const submenu = document.getElementById("submenu");
            const arrow = document.getElementById("arrowIcon");
            submenu.classList.toggle("open");
            arrow.classList.toggle("rotate");
        }
    </script>

    @stack('scripts')
</body>
</html>
