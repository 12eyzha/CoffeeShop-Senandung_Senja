@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Menu List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Menu Tersedia</h2>

            <!-- Search, Filter & Sort -->
            <div class="flex flex-wrap gap-2 mb-4 items-center">
                <input id="searchInput" type="text" placeholder="Cari menu..." 
                       class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-amber-600 focus:border-amber-600">
                
                <select id="sortSelect" 
                        class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-amber-600 focus:border-amber-600">
                    <option value="">Urutkan</option>
                    <option value="asc">Harga Termurah</option>
                    <option value="desc">Harga Termahal</option>
                    <option value="az">Nama A–Z</option>
                    <option value="za">Nama Z–A</option>
                </select>

                <div id="categoryFilter" class="flex flex-wrap gap-2"></div>
            </div>

            <!-- Menu Table -->
            <div class="overflow-x-auto relative">
                <div id="menuLoader" class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center hidden">
                    <i class="fas fa-spinner fa-spin text-amber-800 text-2xl"></i>
                </div>

                <table class="min-w-full border border-gray-300 text-sm text-gray-700" id="menuGrid">
                    <thead class="bg-amber-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Nama Menu</th>
                            <th class="px-3 py-2 text-left">Kategori</th>
                            <th class="px-3 py-2 text-left">Harga</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin text-amber-600 text-lg mr-2"></i>
                                Memuat menu...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="flex justify-center mt-4 space-x-2"></div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pesanan Saat Ini</h2>
            
            <div class="space-y-3 mb-4 max-h-96 overflow-y-auto" id="cartItems">
                <p class="text-gray-500 text-center py-8">Belum ada pesanan</p>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-lg font-bold text-amber-900" id="totalAmount">Rp 0</span>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select id="paymentMethod" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-amber-500 focus:border-amber-500">
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <button id="processPayment" 
                        class="w-full bg-amber-900 hover:bg-amber-800 text-white py-3 px-4 rounded-md font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fas fa-credit-card mr-2"></i>Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
let menus = [];
let categories = [];
let currentPage = 1;
let selectedCategory = 'all';
let searchQuery = '';
let sortType = '';

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadMenus();

    document.getElementById('searchInput').addEventListener('input', debounce(() => {
        searchQuery = document.getElementById('searchInput').value;
        loadMenus(1);
    }, 400));

    document.getElementById('sortSelect').addEventListener('change', function() {
        sortType = this.value;
        loadMenus(1);
    });
});

// === MENU ===
async function loadMenus(page = 1) {
    const loader = document.getElementById('menuLoader');
    loader.classList.remove('hidden');

    try {
        const res = await fetch(`/api/menus?page=${page}&category=${selectedCategory}&search=${searchQuery}`);
        const data = await res.json();
        menus = data.data ?? data;

        // sorting client-side
        if (sortType === 'asc') menus.sort((a, b) => a.price - b.price);
        if (sortType === 'desc') menus.sort((a, b) => b.price - a.price);
        if (sortType === 'az') menus.sort((a, b) => a.name.localeCompare(b.name));
        if (sortType === 'za') menus.sort((a, b) => b.name.localeCompare(a.name));

        displayMenus(menus);
        if (data.last_page) renderPagination(data);
    } catch (err) {
        console.error('Error:', err);
        document.querySelector('#menuGrid tbody').innerHTML = `<tr><td colspan="5" class="text-center text-red-500 py-4">Gagal memuat menu</td></tr>`;
    } finally {
        loader.classList.add('hidden');
    }
}

// === PAGINATION ===
function renderPagination(data) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';
    if (data.last_page <= 1) return;

    const makeBtn = (label, active, disabled, click) => {
        const b = document.createElement('button');
        b.textContent = label;
        b.className = `px-3 py-1 rounded transition-all ${active ? 'bg-amber-900 text-white' : 'bg-gray-200 hover:bg-gray-300'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
        if (!disabled) b.addEventListener('click', click);
        return b;
    };

    container.appendChild(makeBtn('‹', false, data.current_page === 1, () => loadMenus(data.current_page - 1)));
    for (let i = 1; i <= data.last_page; i++) container.appendChild(makeBtn(i, i === data.current_page, false, () => loadMenus(i)));
    container.appendChild(makeBtn('›', false, data.current_page === data.last_page, () => loadMenus(data.current_page + 1)));
}

// === CATEGORY FILTER ===
async function loadCategories() {
    const res = await fetch('/api/categories');
    categories = await res.json();
    displayCategories(categories);
}

function displayCategories(categories) {
    const container = document.getElementById('categoryFilter');
    container.innerHTML = '';
    container.appendChild(createCategoryButton('Semua', 'all', true));
    categories.forEach(cat => container.appendChild(createCategoryButton(cat.name, cat.id)));
}

function createCategoryButton(name, id, active = false) {
    const btn = document.createElement('button');
    btn.className = `category-btn px-4 py-2 rounded-full transition-all duration-200 
                     ${active ? 'bg-amber-900 text-white shadow-md scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}`;
    btn.textContent = name;
    btn.dataset.category = id;

    btn.addEventListener('click', function() {
        document.querySelectorAll('.category-btn').forEach(b => {
            b.classList.remove('bg-amber-900', 'text-white', 'shadow-md', 'scale-105');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        this.classList.add('bg-amber-900', 'text-white', 'shadow-md', 'scale-105');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        selectedCategory = id;
        loadMenus(1);
    });

    return btn;
}

// === DISPLAY MENU ===
function displayMenus(menusToShow) {
    const tbody = document.querySelector('#menuGrid tbody');
    tbody.innerHTML = '';
    if (!menusToShow.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada menu tersedia</td></tr>`;
        return;
    }

    menusToShow.forEach((menu, i) => {
        tbody.innerHTML += `
            <tr class="border-b hover:bg-gray-50 transition-all">
                <td class="px-3 py-2">${i + 1}</td>
                <td class="px-3 py-2">${menu.name}</td>
                <td class="px-3 py-2">${menu.category?.name || '-'}</td>
                <td class="px-3 py-2">Rp ${menu.price.toLocaleString()}</td>
                <td class="px-3 py-2 text-center">
                    <button onclick="addToCart(${menu.id})" 
                            class="bg-amber-900 hover:bg-amber-800 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-plus mr-1"></i>Tambah
                    </button>
                </td>
            </tr>`;
    });
}

// === CART ===
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function addToCart(menuId) {
    const menu = menus.find(m => m.id === menuId);
    if (!menu) return;
    const existing = cart.find(item => item.id === menuId);
    if (existing) existing.quantity++;
    else cart.push({id: menu.id, name: menu.name, price: menu.price, quantity: 1});
    updateCartDisplay();
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const totalAmount = document.getElementById('totalAmount');
    const processBtn = document.getElementById('processPayment');
    cartItems.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-gray-500 text-center py-8">Belum ada pesanan</p>';
        processBtn.disabled = true;
    } else {
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            const cartItem = document.createElement('div');
            cartItem.className = 'flex justify-between items-center bg-gray-50 p-3 rounded-lg';
            cartItem.innerHTML = `
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.name}</h4>
                    <div class="flex items-center space-x-2 mt-1">
                        <button onclick="updateQuantity(${index}, -1)" class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="text-sm font-medium">${item.quantity}</span>
                        <button onclick="updateQuantity(${index}, 1)" class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                        <span class="text-amber-900 font-medium ml-2">Rp ${itemTotal.toLocaleString()}</span>
                    </div>
                </div>
                <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 ml-2">
                    <i class="fas fa-trash"></i>
                </button>`;
            cartItems.appendChild(cartItem);
        });
        processBtn.disabled = false;
    }
    totalAmount.textContent = `Rp ${total.toLocaleString()}`;
}

function updateQuantity(i, change) {
    cart[i].quantity += change;
    if (cart[i].quantity <= 0) cart.splice(i, 1);
    updateCartDisplay();
}

function removeFromCart(i) {
    cart.splice(i, 1);
    updateCartDisplay();
}
</script>
@endpush
