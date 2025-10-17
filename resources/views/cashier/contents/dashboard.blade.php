@extends('cashier.layouts.app')
@section('title', 'Papery | Cashier Dashboard')
@section('content')

    <div x-data="{
        active: localStorage.getItem('activeTab') || 'image'
    }" x-init="$watch('active', value => localStorage.setItem('activeTab', value))"
        class="p-4 border-2 border-gray-200 border-dashed rounded-lg w-full mt-14">

        {{-- Alert Section --}}
        <div class="w-full space-y-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible relative mb-4 w-full text-sm py-2 px-4 bg-green-100 text-green-500 border border-green-500 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="successAlert">
                    <i class="fa fa-circle-check absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <p class="ml-6">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible relative mb-4 w-full text-sm py-2 px-4 bg-red-100 text-red-500 border border-red-500 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="errorAlert">
                    <i class="fa fa-circle-exclamation absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <ul class="list-none m-0 p-0">
                        @foreach ($errors->all() as $error)
                            <li class="ml-6">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex gap-4 mb-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Dashboard</h1>
                <p class="text-gray-400">Akses mudah fitur cepat untuk kasir.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        {{-- CARD BAGIAN 1 --}}
        <div class="grid gap-4 grid-cols-2 xl:grid-cols-4">

            <!-- Total Buku -->
            <div
                class="relative group border-2 border-gray-200 hover:border-[#1779FC] hover:shadow-sm transition-all duration-300 backdrop-blur-lg rounded-xl p-6 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-[#1779FC]/20 blur-[100px]">
                    </div>
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <div class="w-12 h-12 mb-4 bg-[#DEECFF]/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-book fa-xl text-[#1779FC]"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-700">{{ $bookTotal }}</h3>
                        <p class="text-gray-500">Total Buku</p>
                    </div>
                </div>
            </div>

            <!-- Total Kategori -->
            <div
                class="relative group border-2 border-gray-200 hover:border-[#00B67A] hover:shadow-sm transition-all duration-300 backdrop-blur-lg rounded-xl p-6 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-[#00B67A]/20 blur-[100px]">
                    </div>
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <div class="w-12 h-12 mb-4 bg-[#CFFFEA]/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-layer-group fa-xl text-[#00B67A]"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-700">{{ $categoryTotal }}</h3>
                        <p class="text-gray-500">Total Kategori</p>
                    </div>
                </div>
            </div>

            <!-- Total Stok Buku -->
            <div
                class="relative group border-2 border-gray-200 hover:border-[#A855F7] hover:shadow-sm transition-all duration-300 backdrop-blur-lg rounded-xl p-6 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-[#A855F7]/20 blur-[100px]">
                    </div>
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <div class="w-12 h-12 mb-4 bg-[#F3E8FF]/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-boxes-stacked fa-xl text-[#A855F7]"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-700">{{ $stockTotal }}</h3>
                        <p class="text-gray-500">Total Stok Buku</p>
                    </div>
                </div>
            </div>

            <!-- Total Hampir Habis -->
            <div
                class="relative group border-2 border-gray-200 hover:border-[#FACC15] hover:shadow-sm transition-all duration-300 backdrop-blur-lg rounded-xl p-6 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-[#FACC15]/20 blur-[100px]">
                    </div>
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <div class="w-12 h-12 mb-4 bg-[#FEF9C3]/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-triangle-exclamation fa-xl text-[#FACC15]"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-700">{{ $nearEmpty }}</h3>
                        <p class="text-gray-500">Total Buku Hampir Habis</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- BAGIAN 2 - BUKU DENGAN STOK MENIPIS --}}
        <div class="mt-10 bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-sm transition-all duration-300">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Buku dengan Stok Menipis / Habis</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-500">
                    <thead class="text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-6 py-3">Judul Buku</th>
                            <th class="px-6 py-3">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockBooks as $book)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $book->book->title }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-semibold {{ $book->stock < 1 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">
                                        {{ $book->stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-3 text-center text-gray-400">Tidak ada buku dengan stok
                                    menipis</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CARD KHUSUS KASIR --}}
        <div class="grid gap-4 grid-cols-1 xl:grid-cols-4 mt-8">

            <!-- Transaksi Hari Ini -->
            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#1779FC] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $transactionsToday }}</h3>
                        <p class="text-gray-500">Transaksi Hari Ini</p>
                    </div>
                    <i class="fa-solid fa-receipt text-[#1779FC] text-2xl"></i>
                </div>
            </div>

            <!-- Buku Terjual Hari Ini -->
            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#00B67A] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $booksSoldToday }}</h3>
                        <p class="text-gray-500">Buku Terjual Hari Ini</p>
                    </div>
                    <i class="fa-solid fa-book-open text-[#00B67A] text-2xl"></i>
                </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#A855F7] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-700">Rp {{ number_format($incomeToday, 0, ',', '.') }}</h3>
                        <p class="text-gray-500">Pendapatan Hari Ini</p>
                    </div>
                    <i class="fa-solid fa-money-bill-wave text-[#A855F7] text-2xl"></i>
                </div>
            </div>

            <!-- Card tambahan -->
            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-[#FACC15] transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-700">{{ $lowStockBooks->where('stock', '<=', 0)->count() }}
                        </h3>
                        <p class="text-gray-500">Buku Stok Habis</p>
                    </div>
                    <i class="fa-solid fa-ban text-[#FACC15] text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3 - GRAFIK --}}
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4 -translate-y-6">

            <!-- Grafik Penjualan 7 Hari Terakhir -->
            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-sm transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Penjualan 7 Hari Terakhir</h2>
                <div id="chart-penjualan"></div>
            </div>

            <!-- Buku Terlaris Minggu Ini -->
            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-sm transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Buku Terlaris Minggu Ini</h2>
                <ul>
                    @foreach ($topBooksThisWeek as $book)
                        <li class="flex justify-between py-2 border-b">
                            <span>{{ $book->book->title }}</span>
                            <span class="font-semibold text-gray-700">{{ $book->total_sold }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            const salesLast7Days = @json($salesLast7Days->pluck('total_sold'));
            const salesDates = @json($salesLast7Days->pluck('date'));

            var optionsSales = {
                chart: {
                    type: 'line',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Buku Terjual',
                    data: salesLast7Days
                }],
                xaxis: {
                    categories: salesDates
                },
                colors: ['#1779FC'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#eee'
                }
            };
            new ApexCharts(document.querySelector("#chart-penjualan"), optionsSales).render();
        </script>
    @endpush

@endsection
