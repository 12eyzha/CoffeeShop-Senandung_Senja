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
      overflow-x: hidden;
      overflow-y: auto;
      color: #fff;
      z-index: 100;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.15);
      padding-top: 20px;
    }

    .sidebar.open {
      width: 230px;
    }

    /* === TOGGLE BUTTON === */
    .toggle-btn {
      font-size: 22px;
      margin-left: 22px;
      margin-bottom: 20px;
      cursor: pointer;
      transition: transform 0.3s ease;
      color: #fff;
    }

    .toggle-btn:hover {
      transform: rotate(90deg);
    }

    /* === MENU ITEM === */
    .menu-item {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      border-radius: 10px;
      transition: background-color 0.3s ease;
      width: 100%;
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
      margin-left: 10px;
    }

    /* === SUBMENU === */
    .submenu {
      max-height: 0;
      overflow: hidden;
      background-color: #75442A;
      border-left: 3px solid #C47B42;
      transition:
        max-height 0.3s ease,
        opacity 0.3s ease,
        transform 0.3s ease;
      opacity: 0;
      transform: translateX(100%);
      width: 100%;
    }

    .sidebar.open .submenu {
      transform: translateX(0);
      width: 100%; /* full width pas sidebar terbuka */
    }

    .submenu.open {
      max-height: 500px;
      opacity: 1;
    }

    .submenu a {
      display: flex;
      align-items: center;
      padding: 6px 0 6px 35px;
      font-size: 14px;
      color: #fff;
      text-decoration: none;
      position: relative;
      width: 100%;
    }

    .submenu a::before {
      content: "•";
      position: absolute;
      left: 18px;
      font-size: 16px;
      color: #E8C39E;
    }

    .submenu a:hover {
      background-color: #8C5633;
      border-radius: 6px;
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
      margin-left: 230px;
    }
  </style>
</head>

<body>
  <div class="sidebar" id="sidebar">
    <!-- Toggle Button sejajar -->
    <div class="toggle-btn" id="toggleBtn">
      <i class="fas fa-bars"></i>
    </div>

    <!-- Menu utama -->
    <a href="{{ route('dashboard') }}" class="menu-item">
      <i class="fas fa-home"></i><span>Dashboard</span>
    </a>

    <a href="{{ route('transactions.index') }}" class="menu-item">
      <i class="fas fa-cash-register"></i><span>Transaksi</span>
    </a>

    <a href="{{ route('transactions.history') }}" class="menu-item">
      <i class="fas fa-history"></i><span>Riwayat</span>
    </a>

    <!-- Laporan -->
    <div class="menu-item" onclick="toggleSubmenu('reportSubmenu','reportArrow')" style="cursor:pointer;">
      <i class="fas fa-chart-bar"></i><span>Laporan</span>
      <i id="reportArrow" class="fas fa-chevron-right arrow"></i>
    </div>
    <div class="submenu" id="reportSubmenu">
      <a href="{{ route('reports.daily') }}">Laporan Harian</a>
      <a href="{{ route('reports.weekly') }}">Laporan Mingguan</a>
      <a href="{{ route('reports.sales') }}">Laporan Penjualan</a>
    </div>

    <!-- Data Master -->
    <div class="menu-item" onclick="toggleSubmenu('masterSubmenu','masterArrow')" style="cursor:pointer;">
      <i class="fas fa-database"></i><span>Data Master</span>
      <i id="masterArrow" class="fas fa-chevron-right arrow"></i>
    </div>
    <div class="submenu" id="masterSubmenu">
      <a href="{{ route('employees.index') }}">Data Karyawan</a>
      <a href="{{ route('customers.index') }}">Data Pelanggan</a>
      <a href="{{ route('menus.index') }}">Data Menu</a>
    </div>

    <a href="#" class="menu-item">
      <i class="fas fa-cog"></i><span>Setting</span>
    </a>
  </div>

  <div class="content">
    <h1 class="text-2xl font-bold text-amber-900 mb-4">@yield('title')</h1>
    @yield('content')
  </div>

  <script>
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleBtn");

    toggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      sidebar.classList.toggle("open");
    });

    function toggleSubmenu(id, arrowId) {
      const submenu = document.getElementById(id);
      const arrow = document.getElementById(arrowId);
      submenu.classList.toggle("open");
      arrow.classList.toggle("rotate");
    }

    // Klik di luar sidebar = auto tutup
    document.addEventListener("click", function (e) {
      if (!sidebar.contains(e.target) && sidebar.classList.contains("open")) {
        sidebar.classList.remove("open");
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')
</body>
</html>
