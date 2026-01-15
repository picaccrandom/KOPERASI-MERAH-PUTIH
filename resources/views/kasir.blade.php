@extends('layouts.second')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-700 to-red-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold">SISTEM KASIR - KOPERASI MERAH PUTIH</h1>
                    <p class="text-sm opacity-90">Desa Mangsuri | YOGATECHSOLUTION V1.0</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">{{ now()->format('d/m/Y H:i') }} WIB</p>
                    <a href="/dashboard" class="text-xs underline hover:text-gray-200">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Products (2/3 width) -->
            <div class="lg:col-span-2">
                <!-- Category Tabs -->
                <div class="mb-6 bg-white rounded-lg shadow p-4">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button class="category-btn active px-4 py-2 rounded-full bg-red-600 text-white text-sm font-medium" data-category="all">Semua</button>
                        <button class="category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium" data-category="minuman">Minuman</button>
                        <button class="category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium" data-category="makanan">Makanan</button>
                        <button class="category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium" data-category="snack">Snack</button>
                        <button class="category-btn px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium" data-category="sembako">Sembako</button>
                    </div>
                    
                    <div class="relative">
                        <input type="text" id="search-product" placeholder="Cari produk atau kategori..." 
                               class="w-full px-4 py-3 pl-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-3 border-b">
                        <h2 class="font-bold text-gray-700">PILIH PRODUK</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4" id="products-grid">
                        <!-- Products will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Right: Cart & Payment (1/3 width) -->
            <div class="space-y-6">
                <!-- Cart Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b flex justify-between items-center">
                        <h2 class="font-bold text-gray-700">KERANJANG</h2>
                        <span id="cart-count" class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">0 item</span>
                    </div>
                    
                    <div class="p-4">
                        <!-- Empty Cart State -->
                        <div id="empty-cart" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Keranjang belanja kosong</p>
                            <p class="text-xs text-gray-400">Klik produk untuk menambahkan ke keranjang</p>
                        </div>
                        
                        <!-- Cart Items -->
                        <div id="cart-items" class="hidden">
                            <div class="space-y-3 max-h-64 overflow-y-auto pr-2" id="cart-items-list"></div>
                        </div>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="border-t px-4 py-3 bg-gray-50">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="subtotal" class="font-medium">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diskon Anggota</span>
                                <span id="discount" class="font-medium text-green-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="font-bold text-gray-700">TOTAL</span>
                                <span id="total" class="text-xl font-bold text-red-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b">
                        <h2 class="font-bold text-gray-700">PEMBAYARAN</h2>
                    </div>
                    
                    <div class="p-4 space-y-4">
                        <!-- NIK Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK Anggota (Opsional)</label>
                            <div class="flex gap-2">
                                <input type="text" id="nik-input" placeholder="Masukkan NIK untuk diskon" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <button id="check-nik" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                                    Cek
                                </button>
                            </div>
                            <p id="member-info" class="text-xs text-gray-500 mt-1 hidden"></p>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="tunai">Tunai</option>
                                <option value="debit">Kartu Debit</option>
                                <option value="credit">Kartu Kredit</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>

                        <!-- Amount Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" id="payment-amount" placeholder="0" 
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>

                        <!-- Change Display -->
                        <div id="change-display" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Kembalian</span>
                                <span id="change-amount" class="text-lg font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3">
                            <button id="print-receipt" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                CETAK STRUK
                            </button>
                            
                            <button id="complete-payment" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                PROSES PEMBAYARAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Card Template (Hidden) -->
<template id="product-template">
    <div class="product-card bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-200 cursor-pointer">
        <div class="p-3">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="product-name font-semibold text-gray-800 text-sm truncate"></h3>
                    <span class="product-category text-xs px-2 py-1 rounded bg-red-100 text-red-700"></span>
                </div>
                <span class="product-price font-bold text-red-600 text-sm"></span>
            </div>
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Stok:</span>
                    <span class="product-stock font-medium"></span>
                </div>
                <div class="text-xs text-gray-500">
                    Min: <span class="product-minimum font-medium"></span>
                </div>
            </div>
            <button class="add-to-cart-btn w-full mt-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded transition duration-200">
                + Tambah ke Keranjang
            </button>
        </div>
    </div>
</template>

<!-- Cart Item Template (Hidden) -->
<template id="cart-item-template">
    <div class="cart-item bg-gray-50 rounded-lg p-3">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h4 class="cart-item-name font-medium text-gray-800 text-sm"></h4>
                <span class="cart-item-category text-xs text-gray-500"></span>
            </div>
            <button class="remove-item text-gray-400 hover:text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <button class="quantity-decrease w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-700">-</button>
                <span class="quantity-value w-6 text-center font-medium"></span>
                <button class="quantity-increase w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-700">+</button>
            </div>
            <span class="cart-item-price font-bold text-red-600 text-sm"></span>
        </div>
    </div>
</template>

<script>
// Data Produk
const products = [
    { id: 1, name: "Es Teh Manis", category: "minuman", price: 5000, stock: 50, min: 5000 },
    { id: 2, name: "Jus Jeruk", category: "minuman", price: 8000, stock: 30, min: 8000 },
    { id: 3, name: "Nasi Gudeg", category: "makanan", price: 15000, stock: 20, min: 15000 },
    { id: 4, name: "Mie Ayam", category: "makanan", price: 12000, stock: 25, min: 12000 },
    { id: 5, name: "Kopi Hitam", category: "minuman", price: 3000, stock: 100, min: 3000 },
    { id: 6, name: "Roti Tawar", category: "sembako", price: 10000, stock: 15, min: 10000 },
    { id: 7, name: "Telur 1kg", category: "sembako", price: 25000, stock: 40, min: 25000 },
    { id: 8, name: "Keripik Kentang", category: "snack", price: 8000, stock: 60, min: 8000 },
    { id: 9, name: "Air Mineral 600ml", category: "minuman", price: 3000, stock: 120, min: 3000 },
    { id: 10, name: "Beras 5kg", category: "sembako", price: 65000, stock: 30, min: 65000 },
    { id: 11, name: "Minyak Goreng 2L", category: "sembako", price: 35000, stock: 25, min: 35000 },
    { id: 12, name: "Gula 1kg", category: "sembako", price: 15000, stock: 40, min: 15000 }
];

// Data Anggota (contoh)
const members = {
    "123456789012": { name: "Budi Santoso", discount: 10 },
    "234567890123": { name: "Siti Aminah", discount: 10 },
    "345678901234": { name: "Ahmad Rizki", discount: 15 }
};

// Keranjang
let cart = [];
let currentMember = null;
let memberDiscount = 0;

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    renderProducts();
    setupEventListeners();
});

// Setup Event Listeners
function setupEventListeners() {
    // Category buttons
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active', 'bg-red-600', 'text-white'));
            document.querySelectorAll('.category-btn').forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('active', 'bg-red-600', 'text-white');
            
            const category = this.dataset.category;
            filterProducts(category);
        });
    });

    // Search
    document.getElementById('search-product').addEventListener('input', function() {
        searchProducts(this.value);
    });

    // Check NIK
    document.getElementById('check-nik').addEventListener('click', checkMember);
    document.getElementById('nik-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') checkMember();
    });

    // Payment amount
    document.getElementById('payment-amount').addEventListener('input', calculateChange);

    // Buttons
    document.getElementById('print-receipt').addEventListener('click', printReceipt);
    document.getElementById('complete-payment').addEventListener('click', completePayment);
}

// Render produk
function renderProducts(filteredProducts = products) {
    const grid = document.getElementById('products-grid');
    const template = document.getElementById('product-template');
    
    grid.innerHTML = '';
    
    filteredProducts.forEach(product => {
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector('.product-card');
        
        card.querySelector('.product-name').textContent = product.name;
        card.querySelector('.product-category').textContent = product.category.toUpperCase();
        card.querySelector('.product-price').textContent = formatCurrency(product.price);
        card.querySelector('.product-stock').textContent = product.stock + ' tersedia';
        card.querySelector('.product-minimum').textContent = formatCurrency(product.min);
        
        const addBtn = card.querySelector('.add-to-cart-btn');
        addBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            addToCart(product.id);
        });
        
        card.addEventListener('click', () => addToCart(product.id));
        
        grid.appendChild(clone);
    });
}

// Filter produk
function filterProducts(category) {
    if (category === 'all') {
        renderProducts();
    } else {
        const filtered = products.filter(p => p.category === category);
        renderProducts(filtered);
    }
}

// Search produk
function searchProducts(query) {
    const filtered = products.filter(p => 
        p.name.toLowerCase().includes(query.toLowerCase()) || 
        p.category.toLowerCase().includes(query.toLowerCase())
    );
    renderProducts(filtered);
}

// Format currency
function formatCurrency(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

// Tambah ke keranjang
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    const existing = cart.find(item => item.id === productId);
    
    if (existing) {
        if (existing.quantity < product.stock) {
            existing.quantity++;
        } else {
            showAlert('Stok tidak cukup!', 'error');
            return;
        }
    } else {
        cart.push({
            ...product,
            quantity: 1,
            originalPrice: product.price,
            memberPrice: product.price * (1 - memberDiscount / 100)
        });
    }
    
    updateCart();
}

// Update keranjang
function updateCart() {
    const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cart-count').textContent = cartCount + ' item';
    
    // Show/hide empty state
    if (cart.length === 0) {
        document.getElementById('empty-cart').classList.remove('hidden');
        document.getElementById('cart-items').classList.add('hidden');
    } else {
        document.getElementById('empty-cart').classList.add('hidden');
        document.getElementById('cart-items').classList.remove('hidden');
        
        // Render cart items
        const container = document.getElementById('cart-items-list');
        const template = document.getElementById('cart-item-template');
        container.innerHTML = '';
        
        cart.forEach((item, index) => {
            const clone = template.content.cloneNode(true);
            
            clone.querySelector('.cart-item-name').textContent = item.name;
            clone.querySelector('.cart-item-category').textContent = item.category.toUpperCase();
            clone.querySelector('.quantity-value').textContent = item.quantity;
            clone.querySelector('.cart-item-price').textContent = formatCurrency(item.memberPrice * item.quantity);
            
            // Quantity controls
            clone.querySelector('.quantity-decrease').addEventListener('click', () => {
                if (item.quantity > 1) {
                    item.quantity--;
                    updateCart();
                } else {
                    removeFromCart(index);
                }
            });
            
            clone.querySelector('.quantity-increase').addEventListener('click', () => {
                if (item.quantity < item.stock) {
                    item.quantity++;
                    updateCart();
                } else {
                    showAlert('Stok tidak cukup!', 'error');
                }
            });
            
            // Remove button
            clone.querySelector('.remove-item').addEventListener('click', () => removeFromCart(index));
            
            container.appendChild(clone);
        });
    }
    
    updateCartSummary();
}

// Hapus dari keranjang
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Update summary
function updateCartSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.originalPrice * item.quantity), 0);
    const total = cart.reduce((sum, item) => sum + (item.memberPrice * item.quantity), 0);
    const discount = subtotal - total;
    
    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('discount').textContent = formatCurrency(discount);
    document.getElementById('total').textContent = formatCurrency(total);
    
    // Update payment section
    calculateChange();
}

// Cek anggota
function checkMember() {
    const nik = document.getElementById('nik-input').value.trim();
    const info = document.getElementById('member-info');
    
    if (nik === '') {
        currentMember = null;
        memberDiscount = 0;
        info.classList.add('hidden');
        updateCart();
        return;
    }
    
    if (members[nik]) {
        currentMember = members[nik];
        memberDiscount = currentMember.discount;
        info.textContent = `Anggota: ${currentMember.name} (Diskon ${memberDiscount}%)`;
        info.className = 'text-xs text-green-600 mt-1';
        info.classList.remove('hidden');
        
        // Update harga anggota
        cart.forEach(item => {
            item.memberPrice = item.originalPrice * (1 - memberDiscount / 100);
        });
        
        updateCart();
        showAlert('Diskon anggota berhasil diterapkan!', 'success');
    } else {
        info.textContent = 'NIK tidak terdaftar sebagai anggota';
        info.className = 'text-xs text-red-600 mt-1';
        info.classList.remove('hidden');
        currentMember = null;
        memberDiscount = 0;
    }
}

// Hitung kembalian
function calculateChange() {
    const totalText = document.getElementById('total').textContent;
    const total = parseInt(totalText.replace(/[^\d]/g, ''));
    const payment = parseInt(document.getElementById('payment-amount').value) || 0;
    const changeDisplay = document.getElementById('change-display');
    
    if (payment > 0) {
        const change = payment - total;
        if (change >= 0) {
            document.getElementById('change-amount').textContent = formatCurrency(change);
            changeDisplay.classList.remove('hidden');
        } else {
            document.getElementById('change-amount').textContent = formatCurrency(Math.abs(change)) + ' (Kurang)';
            changeDisplay.className = 'p-3 bg-red-50 border border-red-200 rounded-lg';
            changeDisplay.classList.remove('hidden');
        }
    } else {
        changeDisplay.classList.add('hidden');
    }
}

// Cetak struk
function printReceipt() {
    if (cart.length === 0) {
        showAlert('Keranjang kosong!', 'error');
        return;
    }
    
    const total = parseInt(document.getElementById('total').textContent.replace(/[^\d]/g, ''));
    const payment = parseInt(document.getElementById('payment-amount').value) || 0;
    
    if (payment < total) {
        showAlert('Jumlah pembayaran kurang!', 'error');
        return;
    }
    
    // Simulasi cetak
    const receiptContent = generateReceipt();
    console.log(receiptContent);
    
    showAlert('Struk berhasil dicetak!', 'success');
}

// Generate receipt
function generateReceipt() {
    const total = parseInt(document.getElementById('total').textContent.replace(/[^\d]/g, ''));
    const payment = parseInt(document.getElementById('payment-amount').value) || 0;
    const change = payment - total;
    const method = document.getElementById('payment-method').options[document.getElementById('payment-method').selectedIndex].text;
    
    let receipt = `
        =================================
        KOPERASI MERAH PUTIH
        Desa Mangsuri
        =================================
        Tanggal: ${new Date().toLocaleString('id-ID')}
        Kasir: Operator
        =================================
        ITEM                QTY   TOTAL
    `;
    
    cart.forEach(item => {
        const line = `${item.name.substring(0, 20)} ${item.quantity.toString().padStart(3)} ${formatCurrency(item.memberPrice * item.quantity).padStart(10)}`;
        receipt += '\n' + line;
    });
    
    receipt += `
        =================================
        Subtotal:           ${formatCurrency(total).padStart(15)}
        Bayar:             ${formatCurrency(payment).padStart(15)}
        Kembali:           ${formatCurrency(change).padStart(15)}
        =================================
        Metode: ${method}
        ${currentMember ? `Anggota: ${currentMember.name}` : 'Non-Anggota'}
        =================================
        Terima kasih telah berbelanja!
        =================================
    `;
    
    return receipt;
}

// Selesaikan pembayaran
function completePayment() {
    if (cart.length === 0) {
        showAlert('Keranjang kosong!', 'error');
        return;
    }
    
    const total = parseInt(document.getElementById('total').textContent.replace(/[^\d]/g, ''));
    const payment = parseInt(document.getElementById('payment-amount').value) || 0;
    
    if (payment < total) {
        showAlert('Jumlah pembayaran kurang!', 'error');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menyelesaikan transaksi ini?')) {
        // Update stok
        cart.forEach(cartItem => {
            const product = products.find(p => p.id === cartItem.id);
            if (product) {
                product.stock -= cartItem.quantity;
            }
        });
        
        // Reset
        cart = [];
        currentMember = null;
        memberDiscount = 0;
        document.getElementById('nik-input').value = '';
        document.getElementById('member-info').classList.add('hidden');
        document.getElementById('payment-amount').value = '';
        document.getElementById('change-display').classList.add('hidden');
        
        updateCart();
        renderProducts();
        showAlert('Transaksi berhasil disimpan!', 'success');
    }
}

// Show alert
function showAlert(message, type) {
    // Buat element alert
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'}`;
    alert.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'error' ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M5 13l4 4L19 7'}"></path>
            </svg>
            ${message}
        </div>
    `;
    
    document.body.appendChild(alert);
    
    // Hapus setelah 3 detik
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endsection