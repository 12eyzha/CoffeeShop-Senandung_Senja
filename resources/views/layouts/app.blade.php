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
      transition: width 0.3s ease;
      overflow: hidden;
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

    .menu-item {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      color: white;
      text-decoration: none;
      border-radius: 10px;
      transition: background-color 0.3s ease;
    }

    .menu-item:hover {
      background-color: #C47B42;
    }

    .menu-item i {
      font-size: 18px;
      min-width: 30px;
      text-align: center;
    }

    .menu-item span {
      opacity: 0;
      white-space: nowrap;
      transition: opacity 0.3s ease, margin-left 0.3s ease;
    }

    .sidebar.open .menu-item span {
      opacity: 1;
      margin-left: 12px;
    }

    /* === SUBMENU === */
    .submenu {
      max-height: 0;
      overflow: hidden;
      background-color: #825540;
      border-left: 3px solid #C47B42;
      transition: max-height 0.3s ease, opacity 0.3s ease;
      opacity: 0;
    }

    .submenu.open {
      max-height: 500px;
      opacity: 1;
    }

    .submenu a {
      display: block;
      padding: 10px 55px;
      font-size: 14px;
      color: #fff;
      text-decoration: none;
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

    /* === KONTEN === */
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
  <div class="sidebar" id="sidebar">
    <div class="toggle-btn" onclick="toggleSidebar()">☰</div>

    <a href="{{ route('dashboard') }}" class="menu-item"><i class="fas fa-home"></i><span>Dashboard</span></a>
    <a href="{{ route('transactions.index') }}" class="menu-item"><i class="fas fa-cash-register"></i><span>Transaksi</span></a>
    <a href="{{ route('transactions.history') }}" class="menu-item"><i class="fas fa-history"></i><span>Riwayat</span></a>

    <div class="menu-item" onclick="toggleSubmenu('reportSubmenu','reportArrow')" style="cursor:pointer;">
      <i class="fas fa-chart-bar"></i><span>Laporan</span>
      <i id="reportArrow" class="fas fa-chevron-right arrow"></i>
    </div>
    <div class="submenu" id="reportSubmenu">
      <a href="{{ route('reports.daily') }}">Laporan Harian</a>
      <a href="{{ route('reports.weekly') }}">Laporan Mingguan</a>
      <a href="{{ route('reports.sales') }}">Laporan Penjualan</a>
    </div>

    <div class="menu-item" onclick="toggleSubmenu('masterSubmenu','masterArrow')" style="cursor:pointer;">
      <i class="fas fa-database"></i><span>Data Master</span>
      <i id="masterArrow" class="fas fa-chevron-right arrow"></i>
    </div>
    <div class="submenu" id="masterSubmenu">
      <a href="{{ route('employees.index') }}">Data Karyawan</a>
      <a href="{{ route('customers.index') }}">Data Pelanggan</a>
      <a href="{{ route('menus.index') }}">Data Menu</a>
    </div>

    <a href="#" class="menu-item"><i class="fas fa-cog"></i><span>Setting</span></a>

    <form method="POST" action="{{ route('logout') }}" class="m-4 mt-auto">
      @csrf
      <button type="submit"
        class="w-full flex items-center justify-center gap-3 bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-md transition-all duration-300">
        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
      </button>
    </form>
  </div>

  <div class="content">
    <h1 class="text-2xl font-bold text-amber-900 mb-4">@yield('title')</h1>
    @yield('content')
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("open");
    }

    function toggleSubmenu(id, arrowId) {
      const submenu = document.getElementById(id);
      const arrow = document.getElementById(arrowId);
      submenu.classList.toggle("open");
      arrow.classList.toggle("rotate");
    }
  </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  @stack('scripts')
</body>
</html>
