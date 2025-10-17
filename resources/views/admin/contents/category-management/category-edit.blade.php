@extends('admin.layouts.app')
@section('title', 'Papery | Edit Kategori')
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

        <a href="{{ route('admin.category') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Edit Kategori</h1>
                <p class="text-gray-400">Perbarui kategori dan jenis buku yang sudah ada agar data tetap akurat.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <section>
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Perbarui Kategori dan Jenis Buku</h2>

                <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama kategori -->
                    <div class="mb-4">
                        <label for="category_name" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Kategori</label>
                        <input type="text" id="category_name" name="category_name" placeholder="Masukkan nama kategori"
                            value="{{ old('category_name', $category->name) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5 @error('category_name') border-red-500 @enderror"
                            required>
                    </div>

                    <!-- Jenis buku -->
                    <div id="bookTypeContainer" class="mb-4 space-y-3">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Jenis Buku</label>

                        @php
                            $oldBookTypes = old('book_types', $category->bookTypes->pluck('name')->toArray());
                        @endphp

                        @foreach ($oldBookTypes as $index => $type)
                            <div
                                class="flex items-center gap-2 transition-all duration-300 ease-in-out bg-gray-50 p-2 rounded-lg">
                                <span class="text-gray-400 font-semibold w-5 text-right">{{ $index + 1 }}.</span>
                                <input type="text" name="book_types[]" value="{{ $type }}"
                                    placeholder="Masukkan jenis buku"
                                    class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] p-2.5 @error('book_types.' . $index) border-red-500 @enderror">
                                <button type="button"
                                    class="removeBtn cursor-pointer text-gray-400 font-bold hover:text-red-500 transition-all {{ $loop->count <= 1 ? 'hidden' : '' }}">✕</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="addTypeBtn"
                        class="cursor-pointer mt-2 text-sm font-semibold text-[#1779FC] hover:underline">+ Tambah Jenis
                        Buku</button>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                            Perbarui Kategori
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </div>

    @push('scripts')
        <script>
            const container = document.getElementById('bookTypeContainer');
            const addBtn = document.getElementById('addTypeBtn');

            function updateState() {
                const wrappers = container.querySelectorAll('.flex');
                wrappers.forEach((div, i) => {
                    div.querySelector('span').textContent = `${i + 1}.`;
                });

                const showRemove = wrappers.length > 1;
                wrappers.forEach(div => {
                    const removeBtn = div.querySelector('.removeBtn');
                    removeBtn.classList.toggle('hidden', !showRemove);
                });
            }

            addBtn.addEventListener('click', () => {
                const wrapper = document.createElement('div');
                wrapper.className =
                    'flex items-center gap-2 opacity-0 translate-y-2 transition-all duration-300 ease-in-out bg-gray-50 p-2 rounded-lg';

                wrapper.innerHTML = `
            <span class="text-gray-400 font-semibold w-5 text-right"></span>
            <input type="text" name="book_types[]" placeholder="Masukkan jenis buku"
                class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] p-2.5">
            <button type="button" class="removeBtn cursor-pointer text-gray-400 font-bold hover:text-red-500 transition-all">✕</button>
        `;

                container.appendChild(wrapper);
                updateState();

                setTimeout(() => {
                    wrapper.classList.remove('opacity-0', 'translate-y-2');
                }, 10);
            });

            container.addEventListener('click', e => {
                if (e.target.classList.contains('removeBtn')) {
                    const item = e.target.closest('.flex');
                    item.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => {
                        item.remove();
                        updateState();
                    }, 200);
                }
            });

            updateState();
        </script>
    @endpush

@endsection
