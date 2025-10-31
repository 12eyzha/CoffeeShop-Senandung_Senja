@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Menu List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Menu Tersedia</h2>
            
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2 mb-4" id="categoryFilter">
                <!-- Tombol filter akan dibuat via JS -->
            </div>

            <!-- Menu Table -->
            <div class="overflow-x-auto">
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
        </div>
    </div>

    <!-- Cart Section -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pesanan Saat Ini</h2>
            
            <!-- Cart Items -->
            <div class="space-y-3 mb-4 max-h-96 overflow-y-auto" id="cartItems">
                <p class="text-gray-500 text-center py-8">Belum ada pesanan</p>
            </div>

            <!-- Total & Payment -->
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

// Load data saat halaman siap
document.addEventListener('DOMContentLoaded', function() {
    loadMenus();
    loadCategories();
});

// Load menu dari API
async function loadMenus() {
    try {
        const response = await fetch('/api/menus');
        menus = await response.json();
        displayMenus(menus);
    } catch (error) {
        console.error('Error loading menus:', error);
        document.querySelector('#menuGrid tbody').innerHTML = `
            <tr><td colspan="5" class="text-center text-red-500 py-4">Gagal memuat menu</td></tr>`;
    }
}

// Load kategori dari API
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        categories = await response.json();
        displayCategories(categories);
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Tampilkan tombol filter
function displayCategories(categories) {
    const filterContainer = document.getElementById('categoryFilter');
    filterContainer.innerHTML = '';

    // Tombol Semua
    const allBtn = document.createElement('button');
    allBtn.className = 'category-btn px-4 py-2 rounded-full bg-amber-900 text-white';
    allBtn.textContent = 'Semua';
    allBtn.setAttribute('data-category', 'all');
    allBtn.addEventListener('click', filterMenus);
    filterContainer.appendChild(allBtn);

    // Tombol kategori dari API
    categories.forEach(category => {
        const button = document.createElement('button');
        button.className = 'category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300';
        button.textContent = category.name;
        button.setAttribute('data-category', category.id);
        button.addEventListener('click', filterMenus);
        filterContainer.appendChild(button);
    });
}

// Fungsi filter menu
function filterMenus() {
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('bg-amber-900', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    this.classList.remove('bg-gray-200', 'text-gray-700');
    this.classList.add('bg-amber-900', 'text-white');

    const categoryId = this.getAttribute('data-category');
    if(categoryId === 'all') {
        displayMenus(menus);
    } else {
        const filteredMenus = menus.filter(menu => menu.category_id == categoryId);
        displayMenus(filteredMenus);
    }
}

// Tampilkan menu di tabel
function displayMenus(menusToShow) {
    const tbody = document.querySelector('#menuGrid tbody');
    tbody.innerHTML = '';

    if (menusToShow.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada menu tersedia</td></tr>`;
        return;
    }

    menusToShow.forEach((menu, index) => {
        tbody.innerHTML += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-3 py-2">${index + 1}</td>
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

// Add item ke cart
function addToCart(menuId) {
    const menu = menus.find(m => m.id === menuId);
    if (!menu) return;

    const existingItem = cart.find(item => item.id === menuId);
    if (existingItem) existingItem.quantity += 1;
    else cart.push({id: menu.id, name: menu.name, price: menu.price, quantity: 1});

    updateCartDisplay();
}

// Update cart display
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
                </button>
            `;
            cartItems.appendChild(cartItem);
        });

        processBtn.disabled = false;
    }

    totalAmount.textContent = `Rp ${total.toLocaleString()}`;
}

// Update quantity
function updateQuantity(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity <= 0) cart.splice(index, 1);
    updateCartDisplay();
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

// Process payment
document.getElementById('processPayment').addEventListener('click', async function() {
    if (cart.length === 0) return;

    const paymentMethod = document.getElementById('paymentMethod').value;
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    try {
        const response = await fetch('/api/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: cart,
                total_amount: total,
                payment_method: paymentMethod
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Transaksi berhasil! Kode: ' + result.transaction.transaction_code);
            cart = [];
            updateCartDisplay();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses transaksi');
    }
});
</script>
@endpush
