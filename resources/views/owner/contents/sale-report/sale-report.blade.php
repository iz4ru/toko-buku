@extends('owner.layouts.app')
@section('title', 'Papery | Laporan Penjualan')
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

        {{-- Header Section --}}
        <div class="lg:flex grid grid-rows gap-4 justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Laporan Penjualan</h1>
                <p class="text-gray-400">Menampilkan ringkasan dan detail transaksi penjualan dalam periode tertentu</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('owner.report.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                    class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded-lg shadow-md transition font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('owner.report.print', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                    target="_blank"
                    class="px-4 py-2 bg-[#1779FC] hover:bg-[#1565D8] text-white rounded-lg shadow-md transition font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak Laporan
                </a>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        {{-- Filter Period Section --}}
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <form method="GET" action="{{ route('owner.report') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-gray-400"></i> Tanggal Awal
                    </label>
                    <input type="date" name="start_date" value="{{ $start_date }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#1779FC] focus:border-[#1779FC] transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-gray-400"></i> Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="{{ $end_date }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#1779FC] focus:border-[#1779FC] transition">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-2 bg-[#1779FC] hover:bg-[#1565D8] text-white rounded-lg shadow-md transition font-medium">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('owner.report') }}"
                        class="w-full text-center px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold">{{ number_format($total_transactions) }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Pendapatan</p>
                        <h3 class="text-2xl font-bold">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-5 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Diskon</p>
                        <h3 class="text-2xl font-bold">Rp {{ number_format($total_discount, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-tag text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-5 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Buku Terjual</p>
                        <h3 class="text-2xl font-bold">{{ number_format($total_books_sold) }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Best Sellers Section --}}
        @if ($best_sellers->count() > 0)
            <div class="bg-white p-5 rounded-lg shadow-md mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-fire text-orange-500"></i>
                    Top 5 Buku Terlaris
                </h2>
                <div class="space-y-3">
                    @foreach ($best_sellers as $index => $item)
                        <div
                            class="flex items-center gap-4 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-100 hover:shadow-md transition">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-[#1779FC] text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                                {{ $index + 1 }}
                            </div>

                            @if ($item->book && $item->book->book_cover)
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->book->book_cover) }}"
                                        alt="{{ $item->book->title }}"
                                        class="w-12 h-16 object-cover rounded shadow-md border-2 border-white">
                                </div>
                            @else
                                <div
                                    class="flex-shrink-0 w-12 h-16 bg-gray-200 rounded shadow-md border-2 border-white flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate" title="{{ $item->book->title ?? '-' }}">
                                    {{ $item->book->title ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-boxes text-[#1779FC] mr-1"></i>
                                        Stok: <span
                                            class="font-semibold ml-1">{{ $item->book->bookDetail->stock ?? 0 }}</span>
                                    </span>
                                    <span class="mx-2">|</span>
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-tag text-green-500 mr-1"></i>
                                        Rp {{ number_format($item->book->bookDetail->price ?? 0, 0, ',', '.') }}
                                    </span>
                                </p>
                            </div>

                            <div class="flex-shrink-0 text-right">
                                <p class="text-2xl font-bold text-[#1779FC]">{{ $item->total_sold }}</p>
                                <p class="text-xs text-gray-500">Terjual</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Transaction Table --}}
        <div class="relative overflow-x-auto shadow-md rounded-lg bg-white p-4">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-list text-[#1779FC]"></i>
                    Riwayat Transaksi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </p>
            </div>

            <table id="filter-table" class="min-w-full text-sm text-left text-gray-600">
                <thead>
                    <tr>
                        <th>
                            <span class="flex items-center">
                                No
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Kode Transaksi
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Pelanggan
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Kasir
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Tanggal
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Subtotal
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Metode
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Jenis
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-6 py-4">
                                {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-[#1779FC] bg-[#1779FC]/10 p-1 rounded">
                                    {{ $transaction->transactionItems->pluck('transaction_code')->unique()->join(', ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold">{{ $transaction->customer_name }}</td>
                            <td class="px-6 py-4">{{ $transaction->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="grid grid-rows-2">
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('D, d M Y') }}
                                    </p>
                                    <p class="text-xs">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('H:i:s') }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-green-600">
                                Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($transaction->payment_method == 'cash')
                                    <span class="text-xs font-mono text-[#10B981] bg-[#10B981]/10 px-2 py-1 rounded">
                                        cash
                                    </span>
                                @else
                                    <span class="text-xs font-mono text-[#3B82F6] bg-[#3B82F6]/10 px-2 py-1 rounded">
                                        cashless
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($transaction->transaction_type == 'sale')
                                    <span class="text-xs font-mono text-[#00B67A] bg-[#00B67A]/10 px-2 py-1 rounded">
                                        sale
                                    </span>
                                @else
                                    <span class="text-xs font-mono text-[#EF4444] bg-[#EF4444]/10 px-2 py-1 rounded">
                                        return
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 block text-gray-300"></i>
                                Tidak ada transaksi pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($transactions->hasPages())
                <div class="mt-4">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.getElementById("filter-table") && typeof simpleDatatables !== 'undefined' &&
                    typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#filter-table", {
                        tableRender: function(_data, table, type) {
                            if (type === "print") {
                                return table;
                            }

                            const tHead = table.childNodes[0];
                            const filterHeaders = {
                                nodeName: "TR",
                                attributes: {
                                    class: "search-filtering-row"
                                },
                                childNodes: tHead.childNodes[0].childNodes.map(function(_th, index) {
                                    return {
                                        nodeName: "TH",
                                        childNodes: [{
                                            nodeName: "INPUT",
                                            attributes: {
                                                class: "datatable-input",
                                                type: "search",
                                                "data-columns": "[" + index + "]"
                                            }
                                        }]
                                    };
                                })
                            };

                            tHead.childNodes.push(filterHeaders);
                            return table;
                        }
                    });
                }
            });
        </script>
    @endpush

@endsection
