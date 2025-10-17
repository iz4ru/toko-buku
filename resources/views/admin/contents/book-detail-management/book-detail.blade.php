@extends('admin.layouts.app')
@section('title', 'Papery | Kelola Stok & Harga Buku')
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
                <h1 class="text-2xl font-semibold text-gray-600">Kelola Stok & Harga Buku</h1>
                <p class="text-gray-400">Pantau dan kelola stok serta harga buku dengan cepat dan mudah setiap saat.</p>
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
                                Cover
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Judul
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Stok
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Harga
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
                    @foreach ($bookDetails as $bookDetail)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ Storage::url($bookDetail->book->book_cover) }}"
                                    alt="{{ $bookDetail->book->title }}" class="w-12 h-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-600">{{ $bookDetail->book->title }}</td>
                            <td class="px-6 py-4">
                                @if ($bookDetail->stock > 10)
                                    <span class="text-[#1779FC] font-bold">
                                        {{ $bookDetail->stock }} <span class="text-gray-500 font-semibold">pcs</span>
                                    </span>
                                @elseif ($bookDetail->stock > 0)
                                    <span class="text-[#F3AD21] font-bold">
                                        {{ $bookDetail->stock }} <span class="text-gray-500 font-semibold">pcs</span>
                                    </span>
                                @else
                                    <span class="text-[#EF4444] font-bold">
                                        Stok Kosong
                                    </span>
                                    <p>( {{ $bookDetail->stock }} <span class="text-gray-500 font-semibold">pcs</span> )
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">Rp{{ number_format($bookDetail?->price ?? 0, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.book_detail.edit.stock', $bookDetail->id) }}"
                                        class="text-[#1776FC] hover:underline font-medium focus:outline-none">Edit Stok</a>
                                    <p class="font-bold text-gray-300">|</p>
                                    <a href="{{ route('admin.book_detail.edit.price', $bookDetail->id) }}"
                                        class="text-[#1776FC] hover:underline font-medium focus:outline-none">Edit
                                        Harga</a>
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

        <script>
            function toggleList(button) {
                const list = button.nextElementSibling;
                const isHidden = list.classList.contains('hidden');

                if (isHidden) {
                    list.classList.remove('hidden');
                    setTimeout(() => {
                        list.classList.remove('opacity-0');
                        list.classList.remove('max-h-0');
                        list.classList.add('opacity-100');
                        list.classList.add('max-h-96');
                    }, 10);
                } else {
                    list.classList.remove('opacity-100');
                    list.classList.remove('max-h-96');
                    list.classList.add('opacity-0');
                    list.classList.add('max-h-0');
                    setTimeout(() => {
                        list.classList.add('hidden');
                    }, 300);
                }
            }
        </script>

        <script>
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.delete-form');
                    const categoryName = this.getAttribute('data-category');
                    Swal.fire({
                        title: `Yakin hapus kategori "${categoryName}"?`,
                        text: 'Data kategori ini akan hilang permanen bersama dengan jenis buku.',
                        icon: 'warning',
                        iconColor: '#EF4444',
                        showCancelButton: true,
                        confirmButtonColor: '#1776FC',
                        cancelButtonColor: '#EF4444',
                        confirmButtonText: 'Konfirmasi',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
