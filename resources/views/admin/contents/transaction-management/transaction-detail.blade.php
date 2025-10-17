@extends('admin.layouts.app')
@section('title', 'Papery | Detail Transaksi')
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

        <a href="{{ route('admin.transaction') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Detail Transaksi</h1>
                <p class="text-gray-400">Informasi lengkap transaksi, termasuk item, pelanggan, dan pembayaran.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <section>
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-10">

                {{-- Daftar Buku --}}
                <div class="grid grid-cols-1 gap-4 w-full mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Transaksi #{{ $transaction->id }}</label>
                    @foreach ($transaction->transactionItems as $item)
                        <div class="sm:col-span-2">
                            <div
                                class="flex items-center gap-4 p-4 rounded-lg bg-[#1779FC]/5 ring-1 ring-[#1779FC] border-[#1779FC]">
                                <img src="{{ $item->book->book_cover ? Storage::url($item->book->book_cover) : '' }}"
                                    alt="cover" class="w-16 h-20 object-cover rounded shadow">
                                <div>
                                    <p class="text-gray-700 font-semibold">{{ $item->book->title }}</p>
                                    <p class="font-semibold text-gray-800">{{ $item->book_title }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->quantity }}x qty @
                                        Rp{{ number_format($item->price) }} =
                                        <span class="font-semibold">
                                            Rp{{ number_format($item->subtotal) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Info Transaksi --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 w-full">
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-barcode text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Kode Transaksi</p>
                        </div>
                        <span class="font-mono text-[#1779FC] bg-[#1779FC]/10 p-1 rounded">
                            {{ $transaction->transactionItems->pluck('transaction_code')->unique()->join(', ') }}
                        </span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-user text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Nama Pelanggan</p>
                        </div>
                        <p class="text-gray-900 font-semibold">{{ $transaction->customer_name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-user-tie text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Ditangani</p>
                        </div>
                        <p class="text-gray-900 font-semibold">{{ $transaction->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-calendar text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Tanggal Transaksi</p>
                        </div>
                        <p class="text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('D, d M Y H:i:s A') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-money-bill text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Jenis Transaksi</p>
                        </div>
                        @if ($transaction->transaction_type == 'sale')
                            <span class="font-mono text-[#00B67A] bg-[#00B67A]/10 p-1 rounded">
                                {{ $transaction->transaction_type }}
                            </span>
                        @else
                            <span class="font-mono text-[#EF4444] bg-[#EF4444]/10 p-1 rounded">
                                {{ $transaction->transaction_type }}
                            </span>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-coins text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Subtotal</p>
                        </div>
                        <p class="text-gray-900 font-semibold">
                            Rp{{ number_format($transaction->transactionItems->sum('subtotal')) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-hand-holding-dollar text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Dibayar Pelanggan</p>
                        </div>
                        <p class="text-gray-900 font-semibold">Rp{{ number_format($transaction->paid) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-receipt text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Kembalian Pelanggan</p>
                        </div>
                        <p class="text-gray-900 font-semibold">Rp{{ number_format($transaction->spare_change) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border-gray-300 border col-span-2">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-sticky-note text-[#1779FC]"></i>
                            <p class="font-medium text-gray-700 text-sm">Catatan Kasir</p>
                        </div>
                        <p class="text-gray-900 font-semibold">{{ $transaction->note ?? '-' }}</p>
                    </div>
                </div>

            </div>
        </section>

    </div>

@endsection
