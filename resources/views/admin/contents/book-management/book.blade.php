@extends('admin.layouts.app')
@section('title', 'Papery | Kelola Buku')
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
                <h1 class="text-2xl font-semibold text-gray-600">Kelola Buku</h1>
                <p class="text-gray-400">Atur daftar buku, detail, kategori, dan stok dengan cepat dan mudah.</p>
            </div>
        </div>

        <a href="{{ route('admin.book.create') }}"
            class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 gap-2 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98]
        transition-all duration-300 ease-out">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Tambah Buku</span>
        </a>

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
                                Kode
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
                                Kategori
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
                    @foreach ($books as $book)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ Storage::url($book->book_cover) }}" alt="{{ $book->title }}"
                                    class="w-12 h-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4">{{ $book->book_code }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-600">{{ $book->title }}</td>
                            <td class="px-6 py-4">{{ $book->category->name }}</td>
                            <td class="px-6 py-4">{{ $book->bookType->name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">

                                    <button type="button" class="detail-btn cursor-pointer text-gray-700 hover:underline"
                                        onclick="showDetail({{ $book->id }})">Detail</button>

                                    <p class="font-bold text-gray-300">|</p>

                                    <a href="{{ route('admin.book.edit', $book->id) }}"
                                        class="text-[#1776FC] hover:underline font-medium focus:outline-none">Edit</a>
                                    <p class="font-bold text-gray-300">|</p>

                                    <form action="{{ route('admin.book.destroy', $book->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn cursor-pointer text-red-500 hover:underline"
                                            data-book="{{ $book->title }}">Hapus</button>
                                    </form>
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
            function showDetail(id) {
                const books = @json($booksArray);

                let item = books.find(b => b.id === id);
                if (!item) return;

                Swal.fire({
                    width: 720,
                    padding: "1.5rem",
                    background: "#ffffff",
                    showCloseButton: false,
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#1779FC",
                    customClass: {
                        popup: 'rounded-lg shadow-md p-6'
                    },
                    html: `
        <div class="flex flex-col items-center w-full">
            <!-- Book Cover -->
            <div class="mb-6">
                <img src="/storage/${item.book_cover}" class="w-40 h-56 object-cover rounded-lg border-2 border-gray-200 shadow-lg">
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
                <div class="gr-gray-50 p-4 rounded-lg border-gray-300 border">
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

        <script>
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn')) {
                    const button = e.target;
                    const form = button.closest('.delete-form');
                    const bookName = button.getAttribute('data-book');

                    Swal.fire({
                        title: `Yakin hapus buku "${bookName}"?`,
                        text: 'Data buku ini akan hilang permanen.',
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
                }
            });
        </script>
    @endpush

@endsection
