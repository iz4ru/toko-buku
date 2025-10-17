@extends('admin.layouts.app')
@section('title', 'Papery | Riwayat Transaksi')
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

        <div class="flex gap-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Riwayat Transaksi</h1>
                <p class="text-gray-400">Menampilkan daftar aktivitas transaksi, termasuk jenis, nominal, tanggal, dan
                    pengguna terkait.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <div class="relative overflow-x-auto shadow-md rounded-lg bg-white p-4">
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
                                Ditangani
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Tanggal Transaksi
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Jenis Transaksi
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Aksi
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
                    @foreach ($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-[#1779FC] bg-[#1779FC]/10 p-1 rounded">
                                    {{ $transaction->transactionItems->pluck('transaction_code')->unique()->join(', ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold">{{ $transaction->customer_name }}</td>
                            <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                            <td class="px-6 py-4">
                                <div class="grid grid-rows-2">
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('D, d M Y') }}
                                    </p>
                                    <p class="text-xs">

                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('H:i:s A') }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($transaction->transaction_type == 'sale')
                                    <span class="text-xs font-mono text-[#00B67A] bg-[#00B67A]/10 p-1 rounded">
                                        {{ $transaction->transaction_type }}
                                    </span>
                                @else
                                    <span class="text-xs font-mono text-[#EF4444] bg-[#EF4444]/10 p-1 rounded">
                                        {{ $transaction->transaction_type }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.transaction.detail', $transaction->id) }}"
                                        class="text-gray-700 hover:underline">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
