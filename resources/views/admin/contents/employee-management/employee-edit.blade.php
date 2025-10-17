@extends('admin.layouts.app')
@section('title', 'Papery | Edit Kasir')
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

        <a href="{{ route('admin.employee') }}"
            class="text-sm inline-flex items-center gap-1 font-semibold text-gray-500 hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
            <i class="fa-solid fa-chevron-left"></i>
            <span>Kembali</span>
        </a>

        <div class="flex gap-4 my-4 justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-600">Edit Kasir</h1>
                <p class="text-gray-400">Perbarui data informasi kasir di dalam daftar.</p>
            </div>
        </div>

        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>

        <section>
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Perbarui Data Kasir</h2>

                <form action="{{ route('admin.employee.update', $cashier->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                        {{-- Nama --}}
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                            <input type="text" name="name" id="name" placeholder="Masukkan nama pengguna"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                value="{{ old('name', $cashier->name) }}" required>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="sm:col-span-2">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" id="email" placeholder="Masukkan email pengguna"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                                value="{{ old('email', $cashier->email) }}" required>
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="w-full">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password" placeholder="Masukkan password baru"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5">
                            <div class="flex justify-end mt-1">
                                <button type="button" id="togglePassword" class="cursor-pointer text-sm text-[#1779FC] hover:underline">
                                    Tampilkan Password
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="w-full">
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Masukkan konfirmasi password baru"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5">
                            <div class="flex justify-end mt-1">
                                <button type="button" id="togglePasswordConfirm"
                                    class="cursor-pointer text-sm text-[#1779FC] hover:underline">
                                    Tampilkan Password
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePassword');
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.textContent = type === 'password' ? 'Tampilkan Password' : 'Sembunyikan Password';
            });

            const passwordConfirmInput = document.getElementById('password_confirmation');
            const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
            togglePasswordConfirmBtn.addEventListener('click', function() {
                const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
                passwordConfirmInput.type = type;
                this.textContent = type === 'password' ? 'Tampilkan Password' : 'Sembunyikan Password';
            });
        </script>
    @endpush

@endsection
