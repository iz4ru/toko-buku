@extends('admin.layouts.app')
@section('title', 'Papery | Admin Dashboard')
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
                <p class="text-gray-400">Kelola buku, kategori, dan stok dengan cepat.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

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

        <!-- Grafik Section -->
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4 -translate-y-6">

            <!-- Grafik Stok Buku per Kategori -->
            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-sm transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Stok Buku per Kategori</h2>
                <div id="chart-stok"></div>
            </div>

            <!-- Grafik Transaksi per Bulan -->
            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-sm transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Transaksi per Bulan</h2>
                <div id="chart-transaksi"></div>
            </div>

        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            // Ambil data dari backend (diubah ke JSON)
            const stockCategories = @json($stockPerCategory->pluck('category'));
            const stockData = @json($stockPerCategory->pluck('total_stock'));

            const transactionData = @json($transactionsPerMonth);
            const transactionMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            // Grafik Stok Buku per Kategori
            var optionsStok = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Stok Buku',
                    data: stockData
                }],
                xaxis: {
                    categories: stockCategories
                },
                colors: ['#1779FC'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#eee'
                }
            };
            new ApexCharts(document.querySelector("#chart-stok"), optionsStok).render();

            // Grafik Transaksi per Bulan
            var optionsTransaksi = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Transaksi',
                    data: transactionMonths.map((_, i) => transactionData[i + 1] || 0)
                }],
                xaxis: {
                    categories: transactionMonths
                },
                colors: ['#00B67A'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: '#eee'
                }
            };
            new ApexCharts(document.querySelector("#chart-transaksi"), optionsTransaksi).render();
        </script>
    @endpush


@endsection
