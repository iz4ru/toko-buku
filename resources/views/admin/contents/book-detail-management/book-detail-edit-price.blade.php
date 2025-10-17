@extends('admin.layouts.app')
@section('title', 'Papery | Edit Harga Buku')
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

        <a href="{{ route('admin.book_detail') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Edit Harga Buku</h1>
                <p class="text-gray-400">Perbarui data harga buku agar informasi tetap akurat dan mudah dikelola.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <section>
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-10">
                <form id="updatePriceForm" action="{{ route('admin.book_detail.update.price', $bookDetail->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                        {{-- Info Buku --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buku</label>
                            <div
                                class="flex items-center gap-4 p-4 rounded-lg bg-[#1779FC]/5 ring-1 ring-[#1779FC] border-[#1779FC]">
                                <img src="{{ Storage::url($bookDetail->book->book_cover) }}" alt="cover"
                                    class="w-16 h-20 object-cover rounded shadow">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $bookDetail->book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $bookDetail->book->author }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Harga Saat Ini --}}
                        <div class="sm:col-span-2">
                            <div class="mt-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Saat Ini</label>
                                <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">
                                    <span class="text-[#1779FC] text-2xl font-bold">
                                        <span class="text-gray-500 font-semibold text-base">Rp</span>
                                        {{ number_format($bookDetail->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Input Harga --}}
                        <div class="sm:col-span-2">
                            <div class="mt-5">
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga
                                    Baru</label>
                                <input type="number" id="price" name="price" min="1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                    placeholder="Masukkan harga baru" value="{{ old('price', (int) $bookDetail->price) }}"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Harga akan diperbarui sesuai nilai di atas.</p>
                            </div>
                        </div>

                        {{-- Preview Total --}}
                        <div class="sm:col-span-2">
                            <div class="mt-5 bg-[#1779FC]/5 ring-1 ring-[#1779FC] border-[#1779FC] rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-1">Total harga setelah perubahan:</p>
                                <p class="text-2xl font-bold text-[#1779FC]">
                                    <span class="text-base text-gray-600 ml-2">Rp</span>
                                    <span id="preview-total">{{ number_format($bookDetail->price, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-6 text-sm font-medium text-white bg-[#1779FC] rounded-lg hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const priceInput = document.getElementById('price');
            const previewTotal = document.getElementById('preview-total');

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function updatePreview() {
                const value = parseInt(priceInput.value) || 0;
                previewTotal.textContent = formatRupiah(value);
            }

            priceInput.addEventListener('input', updatePreview);
        </script>
    @endpush

@endsection
