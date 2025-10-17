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

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <div class="container mx-auto px-6 py-8 flex gap-6">
            <!-- Main Content -->
            <div class="flex-1">

                <!-- Filter & Search Section -->
                <div class="px-6 mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Buku</h2>

                        <form action="#" method="GET" class="flex flex-wrap items-center gap-3">
                            <label class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg">
                                <input type="checkbox" id="toggleAvailable"
                                    class="h-4 w-4 text-indigo-600 rounded cursor-pointer" checked>
                                <span class="text-sm text-gray-700">Hanya tersedia</span>
                            </label>

                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari judul / kode..."
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                            <select name="category_id"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <optgroup label="{{ $cat['category_name'] }}">
                                        @foreach ($cat['types'] as $type)
                                            <option value="{{ $type['id'] }}"
                                                {{ request('category_id') == $type['id'] ? 'selected' : '' }}>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg cursor-pointer transition duration-200 flex items-center gap-2">
                                <i class="fa fa-search"></i>
                                <span>Cari</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Grid Buku -->
                <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php $cart = session('cart', []); @endphp
                    @forelse($books as $item)
                        @php
                            $inCart = isset($cart[$item->id]);
                            $stock = $item->bookDetail->stock ?? 0;
                        @endphp
                        <div class="book-card relative bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 flex flex-col
                        {{ $stock <= 0 ? 'opacity-50' : 'hover:-translate-y-1' }}"
                            data-title="{{ strtolower($item->title) }} {{ strtolower($item->book_code) }} {{ strtolower($item->category->name ?? '') }}"
                            data-stock="{{ $stock }}">

                            <div class="relative w-full mb-4" style="padding-top: 150%;">
                                <img src="{{ asset('storage/' . $item->book_cover) }}" alt="cover {{ $item->title }}"
                                    class="absolute inset-0 w-full h-full object-cover rounded-lg {{ $stock <= 0 ? 'opacity-40' : '' }}">

                                @if ($stock <= 0)
                                    <div class="absolute inset-0 flex items-center justify-center rounded-lg">
                                        <span
                                            class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                            Stock Habis
                                        </span>
                                    </div>
                                @else
                                    <div
                                        class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                        {{ $stock }} pcs
                                    </div>
                                @endif
                            </div>

                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $item->title }}</h3>

                            <div class="space-y-2 mb-4">
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-tag text-blue-500 w-4"></i>
                                    <span class="truncate">{{ $item->category->name ?? '-' }} /
                                        {{ $item->bookType->name ?? '-' }}</span>
                                </p>
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-calendar text-green-500 w-4"></i>
                                    {{ \Carbon\Carbon::parse($item->publication_year)->format('Y') }}
                                </p>
                                <p class="text-lg font-bold text-indigo-600 flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave w-4"></i>
                                    {{ $item->bookDetail ? 'Rp ' . number_format($item->bookDetail->price, 0, ',', '.') : '-' }}
                                </p>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-auto flex gap-2">
                                <button onclick="showDetail({{ $item->id }})"
                                    class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg shadow-md transition-all duration-200 font-medium cursor-pointer hover:shadow-lg">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                @if ($stock > 0)
                                    @if (!$inCart)
                                        <form action="#" method="POST" class="w-1/2 add-cart-form">
                                            @csrf
                                            <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg shadow-md transition-all duration-200 font-medium cursor-pointer hover:shadow-lg">
                                                <i class="fas fa-cart-plus"></i> Tambah
                                            </button>
                                        </form>
                                    @else
                                        <form action="#" method="POST"
                                            class="flex w-1/2 border-2 border-gray-200 rounded-lg overflow-hidden update-cart-form"
                                            data-stock="{{ $stock }}">
                                            @csrf
                                            @method('PATCH')
                                            @php
                                                $cartQty = $cart[$item->id]['qty'] ?? 0;
                                            @endphp

                                            @if (!$inCart)
                                                <form>...</form>
                                            @else
                                                <form>
                                                    <button type="submit" name="qty"
                                                        value="{{ $cartQty - 1 }}">−</button>
                                                    <span>{{ $cartQty }}</span>
                                                    <button type="submit" name="qty"
                                                        value="{{ $cartQty + 1 }}">+</button>
                                                </form>
                                            @endif
                                        </form>
                                    @endif
                                @else
                                    <button disabled
                                        class="w-1/2 bg-gray-400 text-white py-2.5 rounded-lg shadow cursor-not-allowed font-medium">
                                        <i class="fas fa-ban"></i> Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada buku.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            const cart = {!! json_encode($cart) !!};
            const allBooks = @json($books->items());

            function renderCart() {
                const cartItemsDesktop = document.getElementById('cartItems');
                const cartItemsMobile = document.getElementById('mobileCartItems');
                const cartKeys = Object.keys(cart);

                if (cartKeys.length === 0) {
                    const emptyHTML = `<div class="text-center py-12 text-gray-400">
                <i class="fas fa-shopping-basket text-5xl mb-3"></i>
                <p>Keranjang kosong</p>
            </div>`;
                    if (cartItemsDesktop) cartItemsDesktop.innerHTML = emptyHTML;
                    if (cartItemsMobile) cartItemsMobile.innerHTML = emptyHTML;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                renderCart();

                const alerts = ['successAlert', 'errorAlert'];
                alerts.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(() => el.classList.remove('opacity-0'), 100);
                        setTimeout(() => el.classList.add('opacity-0'), 2000);
                    }
                });
            });
        </script>
    @endpush

@endsection
