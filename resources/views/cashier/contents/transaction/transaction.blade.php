@extends('cashier.layouts.app')
@section('title', 'Papery | Transaksi Penjualan')
@section('content')

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg w-full mt-14">

        {{-- Alert Section --}}
        <div class="w-full space-y-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible relative mb-4 w-full text-sm py-2 px-4 bg-green-100 text-green-500 border border-green-500 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="successAlert">
                    <i class="fa fa-circle-check absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <p class="ml-6">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible relative mb-4 w-full text-sm py-2 px-4 bg-red-100 text-red-500 border border-red-500 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="errorAlert">
                    <i class="fa fa-circle-exclamation absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <p class="ml-6">{{ session('error') }}</p>
                </div>
            @endif
        </div>

        <div class="flex gap-4 justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Transaksi Penjualan</h1>
                <p class="text-gray-400">Pilih buku dan masukkan ke keranjang untuk memproses transaksi penjualan.</p>
            </div>
        </div>

        <!-- Keranjang Mobile (tampil di bawah deskripsi) -->
        <div class="lg:hidden mb-6">
            <div class="bg-white border-1 border-gray-300 rounded-xl p-4">
                <button onclick="toggleMobileCart()" class="w-full flex items-center justify-between text-left">
                    <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-[#1779FC]"></i>
                        Keranjang Belanja
                        <div
                            class="bg-[#1779FC] text-white text-xs rounded-full min-w-[1.25rem] h-5 flex items-center justify-center px-1">
                            {{ count(session('cart', [])) }}
                        </div>
                    </h2>
                    <i class="fas fa-chevron-down transition-transform" id="mobileCartIcon"></i>
                </button>

                <div id="mobileCartContent" class="hidden mt-4">
                    <div id="mobileCartItems" class="space-y-3 mb-4 max-h-80 overflow-y-auto">
                        @php $cart = session('cart', []); @endphp
                        @if (empty($cart))
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                                <p>Keranjang kosong</p>
                            </div>
                        @else
                            @foreach ($cart as $bookId => $item)
                                <div class="flex gap-3 p-3 bg-[#1779FC]/5 border-1 border-[#1779FC] rounded-lg"
                                    id="mobile-cart-item-{{ $bookId }}">
                                    <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                                        class="w-14 h-18 object-cover rounded">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800 line-clamp-2">
                                            {{ $item['title'] }}
                                        </h4>
                                        <p class="text-xs text-gray-600">{{ $item['quantity'] }}x qty @ Rp
                                            {{ number_format($item['price'], 0, ',', '.') }} =
                                            <span class="font-semibold">
                                                Rp{{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}
                                            </span>
                                        </p>
                                    </div>
                                    <button onclick="removeFromCart({{ $bookId }})"
                                        class="text-[#EF4444] hover:text-[#CC2929] cursor-pointer transition-all duration-300 ease-in-out">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if (!empty($cart))
                        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-[#1779FC]" id="mobileSubtotal">
                                Rp
                                {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('cashier.checkout.form') }}"
                            class="w-full justify-center inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                            <i class="fas fa-credit-card mr-2"></i>Checkout
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <div class="container mx-auto px-2 lg:px-6 py-4 lg:py-8 flex flex-col lg:flex-row gap-6">
            <!-- Main Content -->
            <div class="flex-1">

                <!-- Filter & Search Section -->
                <div class="mb-6">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Daftar Buku</h2>

                        <div class="w-full lg:w-auto flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
                            <label class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg">
                                <input type="checkbox" id="toggleAvailable"
                                    class="h-4 w-4 text-[#1779FC] rounded cursor-pointer" checked>
                                <span class="text-sm text-gray-700">Hanya tersedia</span>
                            </label>

                            <input type="text" id="searchInput" placeholder="Cari judul / kode..."
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1779FC] focus:border-transparent w-full lg:w-auto">

                            <select id="categoryFilter"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1779FC] cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <optgroup label="{{ $cat['category_name'] }}">
                                        @foreach ($cat['types'] as $type)
                                            <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-[#1779FC]"></i>
                    <p class="text-gray-500 mt-2">Memuat data...</p>
                </div>

                <!-- Grid Buku -->
                <div id="bookGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6">
                    @php $cart = session('cart', []); @endphp
                    @forelse($books as $item)
                        @php
                            $inCart = isset($cart[$item->id]);
                            $stock = $item->bookDetail->stock ?? 0;
                            $cartQty = $cart[$item->id]['quantity'] ?? 0;
                        @endphp
                        <div class="book-card relative bg-white border-2 border-gray-200 hover:border-[#1779FC] rounded-xl transition-all duration-300 ease-in-out p-3 lg:p-4 flex flex-col {{ $stock <= 0 ? 'opacity-50' : 'hover:-translate-y-1' }}"
                            data-book-id="{{ $item->id }}" data-title="{{ strtolower($item->title) }}"
                            data-code="{{ strtolower($item->book_code) }}"
                            data-category="{{ strtolower($item->bookType->id ?? '') }}" data-stock="{{ $stock }}"
                            @if ($stock <= 0 && request()->get('available_only', 1)) style="display:none;" @endif>

                            <div class="relative w-full mb-3 lg:mb-4" style="padding-top: 150%;">
                                <img src="{{ asset('storage/' . $item->book_cover) }}" alt="cover {{ $item->title }}"
                                    class="absolute inset-0 w-full h-full object-cover rounded-lg {{ $stock <= 0 ? 'opacity-40' : '' }}">

                                @if ($stock <= 0)
                                    <div class="absolute inset-0 flex items-center justify-center rounded-lg">
                                        <span
                                            class="bg-[#EF4444] text-white text-xs lg:text-sm font-bold px-3 py-1 lg:px-4 lg:py-2 rounded-full">
                                            Stock Habis
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-bold text-base lg:text-lg text-gray-700 line-clamp-2">{{ $item->title }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-barcode w-3 lg:w-4"></i>
                                    {{ $item->book_code }}
                                </p>
                            </div>

                            <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

                            <div class="grid grid-cols-2 gap-2 mb-4 justify-center items-center">

                                <div class="col-span-2 text-sm">
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-user"></i>
                                        {{ $item->author }}
                                    </p>
                                </div>

                                <div class="col-span-2 text-sm">
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="truncate">{{ $item->category->name ?? '-' }}</span>
                                    </p>
                                </div>

                                <div class="col-span-2 text-sm">
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-boxes-stacked"></i>
                                        <span class="truncate">Stok:
                                            @if ($stock > 10)
                                                <span class="text-[#1779FC] font-bold">
                                                    {{ $stock }} <span
                                                        class="text-gray-600 font-semibold">pcs</span>
                                                </span>
                                            @elseif ($stock > 0)
                                                <span class="text-[#F3AD21] font-bold">
                                                    {{ $stock }} <span
                                                        class="text-gray-600 font-semibold">pcs</span>
                                                </span>
                                            @else
                                                <span class="text-[#EF4444] font-bold">
                                                    Kosong
                                                </span>
                                                <p>( {{ $stock }} <span
                                                        class="text-gray-600 font-semibold">pcs</span> )
                                                </p>
                                            @endif
                                        </span>
                                    </p>
                                </div>

                            </div>
                            <div class="space-y-1 lg:space-y-2 mb-3 lg:mb-4 text-xs lg:text-sm">

                                <p class="text-sm lg:text-lg font-bold text-[#1779FC] flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave w-3 lg:w-4"></i>
                                    Rp {{ number_format($item->bookDetail->price ?? 0, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-auto flex gap-2">
                                <button onclick="showDetail({{ $item->id }})"
                                    class="w-1/2 justify-center inline-flex cursor-pointer items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out gap-2">
                                    <i class="fas fa-eye"></i> <span class="hidden lg:inline">Detail</span>
                                </button>

                                @if ($stock > 0)
                                    @if (!$inCart)
                                        <button onclick="addToCart({{ $item->id }})" type="button"
                                            class="w-1/2 justify-center inline-flex cursor-pointer items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out gap-2">
                                            <i class="fas fa-cart-plus"></i> <span class="hidden lg:inline">Tambah</span>
                                        </button>
                                    @else
                                        <div class="flex w-1/2 border-2 border-gray-200 rounded-lg overflow-hidden"
                                            data-book-id="{{ $item->id }}" data-stock="{{ $stock }}">
                                            <button type="button"
                                                onclick="updateCartQty({{ $item->id }}, {{ $cartQty - 1 }})"
                                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-[#1779FC] font-bold text-sm lg:text-lg cursor-pointer transition">
                                                −
                                            </button>
                                            <span
                                                class="flex-1 bg-white flex items-center justify-center font-semibold text-xs lg:text-sm text-gray-600">
                                                {{ $cartQty }}
                                            </span>
                                            <button type="button"
                                                onclick="updateCartQty({{ $item->id }}, {{ $cartQty + 1 }})"
                                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-[#1779FC] font-bold text-sm lg:text-lg cursor-pointer transition">
                                                +
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <button disabled
                                        class="w-1/2 bg-gray-400 text-white py-2 lg:py-2.5 rounded-lg cursor-not-allowed text-xs lg:text-sm font-medium">
                                        <i class="fas fa-ban"></i> Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 lg:col-span-4 text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-5xl lg:text-6xl mb-4"></i>
                            <p class="text-gray-500 text-base lg:text-lg">Belum ada buku.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($books->hasPages())
                    <div class="mt-6">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar Keranjang Desktop -->
            <div class="hidden lg:block w-96 bg-white rounded-xl p-6 sticky top-20 h-fit border-1 border-gray-300">
                <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-[#1799FC]"></i>
                    Keranjang Belanja
                    <div
                        class="bg-[#1779FC] text-white text-xs rounded-full min-w-[1.25rem] h-5 flex items-center justify-center px-1">
                        {{ count(session('cart', [])) }}
                    </div>
                </h2>

                <div id="cartItems" class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                    @if (empty($cart))
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-shopping-basket text-5xl mb-3"></i>
                            <p>Keranjang kosong</p>
                        </div>
                    @else
                        @foreach ($cart as $bookId => $item)
                            <div class="flex gap-3 p-3 bg-[#1779FC]/5 border-1 border-[#1779FC] rounded-lg"
                                id="cart-item-{{ $bookId }}">
                                <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                                    class="w-14 h-18 object-cover rounded">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 line-clamp-2">
                                        {{ $item['title'] }}
                                    </h4>
                                    <p class="text-xs text-gray-600">{{ $item['quantity'] }}x qty @ Rp
                                        {{ number_format($item['price'], 0, ',', '.') }} =
                                        <span class="font-semibold">
                                            Rp{{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                                <button onclick="removeFromCart({{ $bookId }})"
                                    class="text-[#EF4444] hover:text-[#CC2929] cursor-pointer transition-all duration-300 ease-in-out">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if (!empty($cart))
                    <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-[#1779FC]" id="subtotal">
                            Rp
                            {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 0, ',', '.') }}
                        </span>
                    </div>
                    <a href="{{ route('cashier.checkout.form') }}"
                        class="w-full justify-center inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                        <i class="fas fa-credit-card mr-2"></i>Checkout
                    </a>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let searchTimeout;

            // Fungsi utama filter buku
            function liveSearch() {
                const searchQuery = document.getElementById('searchInput').value.toLowerCase();
                const categoryId = document.getElementById('categoryFilter').value;
                const showAvailable = document.getElementById('toggleAvailable').checked;

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const bookCards = document.querySelectorAll('.book-card');

                    bookCards.forEach(card => {
                        const title = card.dataset.title;
                        const code = card.dataset.code;
                        const category = card.dataset.category;
                        const stock = Number(card.dataset.stock) || 0;

                        let matchSearch = true;
                        let matchCategory = true;
                        let matchStock = true;

                        // Filter search
                        if (searchQuery) {
                            matchSearch = title.includes(searchQuery) || code.includes(searchQuery);
                        }

                        // Filter category
                        if (categoryId) {
                            matchCategory = category === categoryId;
                        }

                        // Filter stock
                        if (showAvailable) {
                            matchStock = stock > 0;
                        }

                        // Show/hide card
                        card.style.display = (matchSearch && matchCategory && matchStock) ? '' : 'none';
                    });
                }, 100); // delay kecil biar responsif
            }

            // Panggil liveSearch saat halaman load biar filter langsung aktif
            document.addEventListener('DOMContentLoaded', () => {
                liveSearch();

                // Event listener filter
                document.getElementById('searchInput').addEventListener('input', liveSearch);
                document.getElementById('categoryFilter').addEventListener('change', liveSearch);
                document.getElementById('toggleAvailable').addEventListener('change', liveSearch);

                // Alert auto hide
                const alerts = ['successAlert', 'errorAlert'];
                alerts.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(() => el.classList.remove('opacity-0'), 100);
                        setTimeout(() => el.classList.add('opacity-0'), 3000);
                        setTimeout(() => el.remove(), 3500);
                    }
                });
            });

            // Toggle Mobile Cart
            function toggleMobileCart() {
                const content = document.getElementById('mobileCartContent');
                const icon = document.getElementById('mobileCartIcon');
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }

            // Fungsi untuk menambah ke keranjang
            function addToCart(bookId) {
                fetch('{{ route('cashier.cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            book_id: bookId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }

            // Fungsi update quantity
            function updateCartQty(bookId, quantity) {
                if (quantity < 1) {
                    if (confirm('Hapus item dari keranjang?')) {
                        removeFromCart(bookId);
                    }
                    return;
                }

                fetch('{{ route('cashier.cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            book_id: bookId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }

            // Fungsi hapus dari keranjang
            function removeFromCart(bookId) {
                if (confirm('Hapus item dari keranjang?')) {
                    window.location.href = `{{ route('cashier.cart.remove', ':id') }}`.replace(':id', bookId);
                }
            }

            // Fungsi show detail buku (placeholder)
            function showDetail(bookId) {
                const books = @json($booksArray);

                let item = books.find(b => b.id === bookId);
                if (!item) return;

                Swal.fire({
                    width: 720,
                    padding: "1.5rem",
                    background: "#ffffff",
                    showCloseButton: false,
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#1779FC",
                    customClass: {
                        popup: 'rounded-lg p-6'
                    },
                    html: `
        <div class="flex flex-col items-center w-full">
            <!-- Book Cover -->
            <div class="mb-6">
                <img src="/storage/${item.book_cover}" class="w-40 h-56 object-cover rounded-lg border-2 border-gray-200">
            </div>
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">${item.title}</h2>
            
            <!-- Details Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 w-full">
                <!-- Kode -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-barcode text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Kode</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.book_code}</p>
                </div>
                
                <!-- Stok -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-cubes text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Stok</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.book_detail.stock} pcs</p>
                </div>
                
                <!-- Penerbit -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-building text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Penerbit</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.publisher}</p>
                </div>
                
                <!-- Harga -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-dollar-sign text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Harga</p>
                    </div>
                    <p class="text-gray-900 font-semibold text-lg">Rp ${new Intl.NumberFormat('id-ID').format(item.book_detail.price)}</p>
                </div>
                
                <!-- Pengarang -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-user text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Pengarang</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.author}</p>
                </div>
                
                <!-- Tahun Terbit -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-calendar text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Tahun Terbit</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${new Date(item.publication_year).getFullYear()}</p>
                </div>
                
                <!-- Kategori -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-tags text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Kategori</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.category.category}</p>
                </div>
                
                <!-- Jenis -->
                <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-list text-[#1779FC]"></i>
                        <p class="font-medium text-gray-700 text-sm">Jenis</p>
                    </div>
                    <p class="text-gray-900 font-semibold">${item.category.book_type}</p>
                </div>
            </div>
        </div>
        `
                });
            }
        </script>
    @endpush


@endsection
