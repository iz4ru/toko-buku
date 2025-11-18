@extends('owner.layouts.app')
@section('title', 'Papery | Edit Transaksi')
@section('content')

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print,
            aside,
            header {
                display: none !important;
            }
        }
    </style>

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

        <a href="{{ route('owner.transaction') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Riwayat Transaksi #{{ $transaction->id }}</h1>
                <p class="text-gray-400">Menampilkan struk pembelian terkait.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <div class="border border-gray-300 rounded-lg p-4">
            <div id="print-area" class="bg-white p-8 max-w-xs mx-auto font-mono text-sm leading-snug">
                <div class="text-center mb-3">
                    <div class="pb-4">
                        <div class="flex items-center justify-center tracking-wide">
                            <img src="{{ asset('images/papery-receipt.png') }}" class="max-w-[200px]" alt="Papery Logo" />
                        </div>
                        <p class="text-xs font-bold">Jl. Kenangan Kita 45</p>
                    </div>

                    <p class="text-xs">Customer:
                        <span class="font-bold">
                            {{ $transaction->customer_name }}
                        </span>
                    </p>
                    <p class="text-xs">Kasir:
                        <span class="font-bold">
                            {{ $transaction->user->name }}
                        </span>
                    </p>
                    <p class="text-xs">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i:s') }}
                    </p>

                    <div class="my-4 border-t-2 border-dashed w-full"></div>

                    {{-- Loop item transaksi --}}
                    @foreach ($transactions as $item)
                        <div class="items-start text-start mt-2">
                            <p class="font-semibold">{{ $item->book->title }}</p>
                            <div class="flex justify-between text-xs">
                                <p class="text-xs">
                                    {{ $item->quantity }}x qty @ Rp {{ number_format($item->price, 0, ',', '.') }} =
                                </p>
                                <p class="font-semibold">
                                    Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-between text-xs mt-4">
                        <span>Qty/Item</span>
                        <span>{{ $transaction->transactionItems->sum('quantity') }}/{{ $transaction->transactionItems->count() }}</span>
                    </div>

                    <div class="my-4 border-t-2 border-dashed w-full"></div>

                    <div class="space-y-1 text-xs">
                        @php
                            $discountPercentage = optional($transaction->discount)->percentage ?? 0;
                            $discountAmount = ($transaction->subtotal * $discountPercentage) / 100;
                            $finalSubtotal = $transaction->subtotal - $discountAmount;
                        @endphp

                        <div class="flex justify-between">
                            <span>Diskon</span>
                            @if ($discountPercentage > 0)
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            @else
                                <span>Rp 0</span>
                            @endif
                        </div>

                        <div class="flex justify-between font-bold underline">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($finalSubtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Dibayar</span>
                            <span>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Kembalian</span>
                            <span>Rp {{ number_format($transaction->spare_change, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="my-4 border-t-2 border-dashed w-full"></div>

                    <div class="text-center mt-3 text-xs">
                        <p class="p-2">TERIMA KASIH</p>
                        <p class="italic">> Barang yang sudah dibeli<br>tidak dapat dikembalikan</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button onclick="window.print()"
                    class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                    Print Out Struk
                </button>
            </div>
        </div>
    </div>

@endsection
