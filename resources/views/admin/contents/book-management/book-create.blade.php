@extends('admin.layouts.app')
@section('title', 'Papery | Tambah Buku')
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

        <a href="{{ route('admin.book') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Tambah Buku</h1>
                <p class="text-gray-400">Tambahkan informasi buku baru ke dalam daftar.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <section>
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Tambahkan Buku Baru</h2>

                <form action="{{ route('admin.book.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                        {{-- Kode Buku --}}
                        <div class="sm:col-span-2">
                            <label for="book_code" class="block mb-2 text-sm font-medium text-gray-900">Kode Buku</label>
                            <input type="text" name="book_code" id="book_code" placeholder="Kode buku otomatis terisi"
                                readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5">
                        </div>

                        {{-- Judul Buku --}}
                        <div class="w-full">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Buku</label>
                            <input type="text" name="title" id="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                placeholder="Masukkan judul buku" value="{{ old('title') }}" required>
                        </div>

                        {{-- Pengarang --}}
                        <div class="w-full">
                            <label for="author" class="block mb-2 text-sm font-medium text-gray-900">Pengarang</label>
                            <input type="text" name="author" id="author"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                placeholder="Masukkan nama pengarang" value="{{ old('author') }}" required>
                        </div>

                        {{-- Penerbit --}}
                        <div class="w-full">
                            <label for="publisher" class="block mb-2 text-sm font-medium text-gray-900">Penerbit</label>
                            <input type="text" name="publisher" id="publisher"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                placeholder="Masukkan nama penerbit" value="{{ old('publisher') }}" required>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                            <select name="category" id="category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                required>
                                @if ($categories->count() < 1)
                                    <option disabled selected>Tambah dahulu kategori</option>
                                @else
                                    <option disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Jenis Buku --}}
                        <div>
                            <label for="book_type" class="block mb-2 text-sm font-medium text-gray-900">Jenis</label>
                            <select name="book_type" id="book_type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                required>
                                @if (old('book_type'))
                                    <option value="{{ old('book_type') }}" selected>
                                        {{ App\Models\BookType::find(old('book_type'))->name ?? 'Jenis lama tidak ditemukan' }}
                                    </option>
                                @else
                                    <option disabled selected>Pilih jenis</option>
                                @endif
                            </select>
                        </div>

                        {{-- Tahun Terbit --}}
                        <div>
                            <label for="publication_year" class="block mb-2 text-sm font-medium text-gray-900">Tahun
                                Terbit</label>
                            <input type="month" name="publication_year" id="publication_year"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                value="{{ old('publication_year') }}" required>
                        </div>

                        {{-- Harga --}}
                        <div class="w-full">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Harga</label>
                            <input type="number" name="price" id="price" min="0"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                placeholder="Masukkan harga buku" value="{{ old('price') }}" required>
                        </div>

                        {{-- Stok --}}
                        <div class="w-full">
                            <label for="stock" class="block mb-2 text-sm font-medium text-gray-900">Stok Awal</label>
                            <input type="number" name="stock" id="stock" min="0"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                placeholder="Masukkan jumlah stok awal" value="{{ old('stock') }}" required>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="sm:col-span-2">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                            <textarea name="description" id="description" rows="8"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-[#1779FC] focus:border-[#1779FC]"
                                placeholder="Masukkan deskripsi buku">{{ old('description') }}</textarea>
                        </div>

                        {{-- Cover Buku --}}
                        <div class="sm:col-span-2">
                            <label for="book_cover" class="block mb-2 text-sm font-medium text-gray-900">Cover
                                Buku</label>
                            <div
                                class="flex items-center bg-gray-50 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-[#1779FC] focus-within:border-[#1779FC] p-2.5">
                                <label for="book_cover"
                                    class="px-5 py-2 text-sm font-medium text-white bg-[#1779FC] rounded-lg cursor-pointer hover:bg-[#DEECFF] hover:text-[#1779FC] transition-all duration-300 ease-out">
                                    Pilih Gambar
                                </label>
                                <span id="file-name" class="ml-3 text-sm text-gray-500 truncate">Belum ada file
                                    dipilih</span>
                            </div>

                            <input type="file" name="book_cover" id="book_cover" accept="image/*" class="hidden">

                            <div
                                class="mt-4 flex justify-center p-4 bg-[#1779FC]/5 ring-1 ring-[#1779FC] border-[#1779FC] rounded-lg transition-all duration-300 ease-in-out">
                                <p id="no-preview" class="text-[#1779FC] text-sm">Tidak ada preview gambar.</p>
                                <img id="preview-image" src="#" alt="Preview Cover"
                                    class="hidden w-40 h-56 aspect-[3/4] object-cover rounded-lg border border-gray-300">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                            Tambahkan Buku
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categorySelect = document.getElementById('category');
                const bookTypeSelect = document.getElementById('book_type');
                const bookCodeInput = document.getElementById('book_code');

                // Fungsi untuk generate kode final
                const generateBookCode = async () => {
                    const categoryId = categorySelect.value;
                    const typeId = bookTypeSelect.value;

                    // Hanya generate jika kedua field sudah dipilih
                    if (!categoryId || !typeId) {
                        bookCodeInput.value = ''; // Clear jika ada yang kosong
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/book/generate-code/${categoryId}/${typeId}`);
                        const data = await response.json();
                        bookCodeInput.value = data.code; // Live update ke textbox
                    } catch (error) {
                        console.error('Error generating book code:', error);
                    }
                };

                // Trigger saat kategori berubah
                categorySelect.addEventListener('change', function() {
                    const categoryId = this.value;
                    bookTypeSelect.disabled = true;
                    bookTypeSelect.classList.add('bg-gray-100');
                    bookTypeSelect.innerHTML = '<option disabled selected>Memuat jenis...</option>';
                    bookCodeInput.value = ''; // Clear kode saat kategori berubah

                    fetch(`/admin/book/get-types/${categoryId}`)
                        .then(res => res.json())
                        .then(data => {
                            bookTypeSelect.innerHTML = '<option disabled selected>Pilih jenis</option>';
                            for (const id in data) {
                                bookTypeSelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                            }
                            bookTypeSelect.disabled = false;
                            bookTypeSelect.classList.remove('bg-gray-100');
                            bookTypeSelect.classList.add('bg-gray-50');
                        });
                });

                // Trigger saat jenis buku berubah - generate kode final
                bookTypeSelect.addEventListener('change', generateBookCode);
            });

            document.getElementById("book_cover").addEventListener("change", function() {
                const previewImage = document.getElementById('preview-image')
                const noPreviewText = document.getElementById('no-preview')
                const fileNameSpan = document.getElementById('file-name')
                const file = this.files[0]

                // update nama file
                fileNameSpan.textContent = file ? file.name : "Belum ada file dipilih"

                if (file) {
                    const reader = new FileReader()
                    reader.onload = function(e) {
                        previewImage.src = e.target.result
                        previewImage.classList.remove('hidden')
                        noPreviewText.classList.add('hidden')
                    }
                    reader.readAsDataURL(file)
                } else {
                    previewImage.src = "#"
                    previewImage.classList.add('hidden')
                    noPreviewText.classList.remove('hidden')
                }
            })
        </script>
        <script>
            document.getElementById("book_cover").addEventListener("change", function() {
                const previewImage = document.getElementById('preview-image')
                const noPreviewText = document.getElementById('no-preview')
                const fileNameSpan = document.getElementById('file-name')
                const file = this.files[0]

                // update nama file
                fileNameSpan.textContent = file ? file.name : "Belum ada file dipilih"

                if (file) {
                    const reader = new FileReader()
                    reader.onload = function(e) {
                        previewImage.src = e.target.result
                        previewImage.classList.remove('hidden')
                        noPreviewText.classList.add('hidden')
                    }
                    reader.readAsDataURL(file)
                } else {
                    previewImage.src = "#"
                    previewImage.classList.add('hidden')
                    noPreviewText.classList.remove('hidden')
                }
            })
        </script>
    @endpush

@endsection
