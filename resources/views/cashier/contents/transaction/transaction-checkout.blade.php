@extends('cashier.layouts.app')
@section('title', 'Papery | Checkout')
@section('content')

    <div class="p-3 lg:p-4 border-2 border-gray-200 border-dashed rounded-lg w-full mt-14 bg-white">

        <h1 class="text-xl lg:text-2xl font-semibold text-gray-700 mb-4">Proses Pembayaran</h1>

        {{-- Alert Section --}}
        <div class="w-full space-y-3">
            @if (session('success'))
                <div class="alert alert-success relative mb-4 w-full text-sm py-2 px-4 bg-green-50 text-green-700 border border-green-300 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="successAlert">
                    <i class="fa fa-circle-check absolute left-4 top-1/2 -translate-y-1/2 text-green-600"></i>
                    <p class="ml-6">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger relative mb-4 w-full text-sm py-2 px-4 bg-red-50 text-red-700 border border-red-300 rounded-md opacity-0 transition-opacity duration-150 ease-in-out"
                    role="alert" id="errorAlert">
                    <i class="fa fa-circle-exclamation absolute left-4 top-1/2 -translate-y-1/2 text-red-600"></i>
                    <p class="ml-6">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger relative mb-4 w-full text-sm py-2 px-4 bg-red-50 text-red-700 border border-red-300 rounded-md"
                    role="alert">
                    <i class="fa fa-circle-exclamation absolute left-4 top-1/2 -translate-y-1/2 text-red-600"></i>
                    <div class="ml-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

            <!-- Form Pembayaran (Mobile First - tampil di atas) -->
            <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-4">
                <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-[#1779FC]"></i>
                    Data Pembayaran
                </h2>

                <form id="checkoutForm" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pelanggan <span class="text-[#EF4444]">*</span>
                        </label>
                        <input type="text" name="customer_name" placeholder="Nama pelanggan"
                            value="{{ old('customer_name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Metode Pembayaran <span class="text-[#EF4444]">*</span>
                        </label>
                        <select name="payment_method" id="payment_method"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5 cursor-pointer"
                            onchange="toggleCashField()" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)
                            </option>
                            <option value="cashless" {{ old('payment_method') == 'cashless' ? 'selected' : '' }}>Non-Tunai
                                (Cashless)</option>
                        </select>
                    </div>

                    <div id="cashField">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Bayar <span class="text-[#EF4444]">*</span>
                        </label>
                        <input type="number" name="paid" id="paidInput" placeholder="Masukkan jumlah uang"
                            value="{{ old('paid') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                            min="0" step="1000" oninput="calculateChange()">

                        <div id="changeInfo" class="mt-2 hidden">
                            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-2">
                                <p class="text-xs text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <span id="changeText"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (Opsional)</label>
                        <select name="discount_id" id="discountSelect" onchange="calculateTotal()"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5 cursor-pointer">
                            <option value="">Tanpa Diskon</option>
                            @foreach ($discounts as $disc)
                                <option value="{{ $disc->id }}" data-percentage="{{ $disc->percentage }}"
                                    {{ old('discount_id') == $disc->id ? 'selected' : '' }}>
                                    {{ $disc->name }} ({{ $disc->percentage }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="note" rows="2" placeholder="Catatan transaksi (opsional)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5">{{ old('note') }}</textarea>
                    </div>

                    <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Subtotal:</span>
                            <span id="mobileSubtotalAmount" class="font-semibold">Rp
                                {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 0, ',', '.') }}</span>
                        </div>
                        <div id="mobileDiscountRow" class="flex justify-between text-sm text-green-600 hidden">
                            <span>Diskon:</span>
                            <span id="mobileDiscountAmount" class="font-semibold">- Rp 0</span>
                        </div>

                        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                        <div class="flex justify-between text-lg font-bold text-gray-700">
                            <span>Total Bayar:</span>
                            <span id="mobileTotalAmount" class="text-[#1779FC]">Rp
                                {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full justify-center inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Simpan Transaksi</span>
                    </button>

                    <a href="{{ route('cashier.shop') }}"
                        class="block w-full bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition-all text-sm text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </form>
            </div>

            <!-- Preview Keranjang -->
            <div class="lg:col-span-2">
                <div class="bg-[#1779FC]/5 border border-[#1779FC] rounded-xl p-4">
                    <h2 class="text-base lg:text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-shopping-basket text-[#1779FC]"></i>
                        Daftar Buku yang Dibeli
                    </h2>

                    <div class="space-y-3 max-h-[400px] lg:max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($cart as $id => $item)
                            <div
                                class="flex items-center gap-3 lg:gap-4 p-3 lg:p-4 rounded-lg bg-white border border-gray-300 hover:-translate-y-1 transition-all">
                                <img src="{{ asset('storage/' . $item['cover']) }}" alt="cover"
                                    class="w-12 h-16 lg:w-16 lg:h-20 object-cover rounded">
                                <div class="flex-1">
                                    <p class="text-gray-800 font-semibold text-sm lg:text-base line-clamp-2">
                                        {{ $item['title'] }}</p>
                                    <p class="text-xs text-gray-500">Kode: {{ $id }}</p>
                                    <p class="text-xs lg:text-sm text-gray-700 mt-1">{{ $item['quantity'] }}x @
                                        Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sm lg:text-base text-[#1779FC]">
                                        Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="my-4 border-t-2 border-dashed border-[#1779FC]/50 w-full"></div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Item:</span>
                            <span class="font-semibold">{{ count($cart) }} item</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Kuantitas:</span>
                            <span class="font-semibold">{{ collect($cart)->sum('quantity') }} pcs</span>
                        </div>
                        <div class="flex justify-between text-base lg:text-lg font-bold text-[#1779FC] mt-2">
                            <span>Subtotal:</span>
                            <span>Rp{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran Desktop -->
            <div class="hidden lg:block bg-white border border-gray-200 rounded-xl p-4 h-fit sticky top-20">
                <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-[#1779FC]"></i>
                    Data Pembayaran
                </h2>

                <form id="checkoutFormDesktop" action="{{ route('cashier.checkout.process') }}" method="POST"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pelanggan <span class="text-[#EF4444]">*</span>
                        </label>
                        <input type="text" name="customer_name" placeholder="Nama pelanggan"
                            value="{{ old('customer_name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Metode Pembayaran <span class="text-[#EF4444]">*</span>
                        </label>
                        <select name="payment_method" id="payment_method_desktop"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5 cursor-pointer"
                            onchange="toggleCashFieldDesktop()" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)
                            </option>
                            <option value="cashless" {{ old('payment_method') == 'cashless' ? 'selected' : '' }}>Non-Tunai
                                (Cashless)</option>
                        </select>
                    </div>

                    <div id="cashFieldDesktop">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Bayar <span class="text-[#EF4444]">*</span>
                        </label>
                        <input type="number" name="paid" id="paidInputDesktop" placeholder="Masukkan jumlah uang"
                            value="{{ old('paid') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5"
                            min="0" step="1000" oninput="calculateChangeDesktop()">

                        <div id="changeInfoDesktop" class="mt-2 hidden">
                            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-2">
                                <p class="text-xs text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <span id="changeTextDesktop"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (Opsional)</label>
                        <select name="discount_id" id="discountSelectDesktop" onchange="calculateTotalDesktop()"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5 cursor-pointer">
                            <option value="">Tanpa Diskon</option>
                            @foreach ($discounts as $disc)
                                <option value="{{ $disc->id }}" data-percentage="{{ $disc->percentage }}"
                                    {{ old('discount_id') == $disc->id ? 'selected' : '' }}>
                                    {{ $disc->name }} ({{ $disc->percentage }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="note" rows="3" placeholder="Catatan transaksi (opsional)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#1779FC] focus:border-[#1779FC] block w-full p-2.5">{{ old('note') }}</textarea>
                    </div>

                    <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Subtotal:</span>
                            <span id="desktopSubtotalAmount" class="font-semibold">Rp
                                {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 0, ',', '.') }}</span>
                        </div>
                        <div id="desktopDiscountRow" class="flex justify-between text-sm text-green-600 hidden">
                            <span>Diskon:</span>
                            <span id="desktopDiscountAmount" class="font-semibold">- Rp 0</span>
                        </div>

                        <div class="my-4 border-t-2 border-dashed border-gray-300 w-full"></div>
                        <div class="flex justify-between text-lg font-bold text-gray-700">
                            <span>Total Bayar:</span>
                            <span id="desktopTotalAmount" class="text-[#1779FC]">Rp
                                {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="submitBtnDesktop"
                        class="w-full justify-center inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-[#1779FC] rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-[#DEECFF] hover:text-[#1779FC] active:scale-[0.98] transition-all duration-300 ease-out">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Simpan Transaksi</span>
                    </button>

                    <a href="{{ route('cashier.shop') }}"
                        class="block w-full bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition-all text-sm text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @push('scripts')
        <script>
            const subtotal = {{ collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']) }};

            // Toggle cash field - Mobile
            function toggleCashField() {
                const method = document.getElementById('payment_method').value;
                const cashField = document.getElementById('cashField');
                const paidInput = document.getElementById('paidInput');

                if (method === 'cashless') {
                    cashField.style.display = 'none';
                    paidInput.value = subtotal;
                    paidInput.removeAttribute('required');
                    calculateChange();
                } else {
                    cashField.style.display = 'block';
                    paidInput.setAttribute('required', 'required');
                    paidInput.value = '';
                }
            }

            // Toggle cash field - Desktop
            function toggleCashFieldDesktop() {
                const method = document.getElementById('payment_method_desktop').value;
                const cashField = document.getElementById('cashFieldDesktop');
                const paidInput = document.getElementById('paidInputDesktop');

                if (method === 'cashless') {
                    cashField.style.display = 'none';
                    paidInput.value = subtotal;
                    paidInput.removeAttribute('required');
                    calculateChangeDesktop();
                } else {
                    cashField.style.display = 'block';
                    paidInput.setAttribute('required', 'required');
                    paidInput.value = '';
                }
            }

            // Calculate total with discount - Mobile
            function calculateTotal() {
                const discountSelect = document.getElementById('discountSelect');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);

                const discountAmount = subtotal * (percentage / 100);
                const total = subtotal - discountAmount;

                // Update display
                document.getElementById('mobileSubtotalAmount').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

                if (percentage > 0) {
                    document.getElementById('mobileDiscountRow').classList.remove('hidden');
                    document.getElementById('mobileDiscountAmount').textContent = '- Rp ' + discountAmount.toLocaleString(
                        'id-ID');
                } else {
                    document.getElementById('mobileDiscountRow').classList.add('hidden');
                }

                document.getElementById('mobileTotalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');

                calculateChange();
            }

            // Calculate total with discount - Desktop
            function calculateTotalDesktop() {
                const discountSelect = document.getElementById('discountSelectDesktop');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);

                const discountAmount = subtotal * (percentage / 100);
                const total = subtotal - discountAmount;

                // Update display
                document.getElementById('desktopSubtotalAmount').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

                if (percentage > 0) {
                    document.getElementById('desktopDiscountRow').classList.remove('hidden');
                    document.getElementById('desktopDiscountAmount').textContent = '- Rp ' + discountAmount.toLocaleString(
                        'id-ID');
                } else {
                    document.getElementById('desktopDiscountRow').classList.add('hidden');
                }

                document.getElementById('desktopTotalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');

                calculateChangeDesktop();
            }

            // Calculate change - Mobile
            function calculateChange() {
                const paidInput = document.getElementById('paidInput');
                const changeInfo = document.getElementById('changeInfo');
                const changeText = document.getElementById('changeText');

                const paid = parseFloat(paidInput.value || 0);

                // Get discount
                const discountSelect = document.getElementById('discountSelect');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);
                const total = subtotal - (subtotal * (percentage / 100));

                if (paid > 0) {
                    const change = paid - total;

                    if (change < 0) {
                        changeInfo.classList.remove('hidden');
                        changeInfo.querySelector('div').className = 'mt-2 bg-red-100 border border-red-500 rounded-lg p-2';
                        changeText.innerHTML = '<strong>Kurang:</strong> Rp ' + Math.abs(change).toLocaleString('id-ID');
                        changeText.parentElement.className = 'text-sm text-red-500';
                    } else {
                        changeInfo.classList.remove('hidden');
                        changeInfo.querySelector('div').className = 'mt-2 bg-green-100 border border-green-500 rounded-lg p-2';
                        changeText.innerHTML = '<strong>Kembalian:</strong> Rp ' + change.toLocaleString('id-ID');
                        changeText.parentElement.className = 'text-sm text-green-500';
                    }
                } else {
                    changeInfo.classList.add('hidden');
                }
            }

            // Calculate change - Desktop
            function calculateChangeDesktop() {
                const paidInput = document.getElementById('paidInputDesktop');
                const changeInfo = document.getElementById('changeInfoDesktop');
                const changeText = document.getElementById('changeTextDesktop');

                const paid = parseFloat(paidInput.value || 0);

                // Get discount
                const discountSelect = document.getElementById('discountSelectDesktop');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);
                const total = subtotal - (subtotal * (percentage / 100));

                if (paid > 0) {
                    const change = paid - total;

                    if (change < 0) {
                        changeInfo.classList.remove('hidden');
                        changeInfo.querySelector('div').className = 'mt-2 bg-red-100 border border-red-500 rounded-lg p-2';
                        changeText.innerHTML = '<strong>Kurang:</strong> Rp ' + Math.abs(change).toLocaleString('id-ID');
                        changeText.parentElement.className = 'text-sm text-red-500';
                    } else {
                        changeInfo.classList.remove('hidden');
                        changeInfo.querySelector('div').className = 'mt-2 bg-green-100 border border-green-500 rounded-lg p-2';
                        changeText.innerHTML = '<strong>Kembalian:</strong> Rp ' + change.toLocaleString('id-ID');
                        changeText.parentElement.className = 'text-sm text-green-500';
                    }
                } else {
                    changeInfo.classList.add('hidden');
                }
            }

            // Form submission with validation - Mobile
            document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const method = document.getElementById('payment_method').value;
                const paidInput = document.getElementById('paidInput');
                const paid = parseFloat(paidInput.value || 0);

                // Get discount
                const discountSelect = document.getElementById('discountSelect');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);
                const total = subtotal - (subtotal * (percentage / 100));

                if (method === 'cash' && paid < total) {
                    alert('Jumlah bayar tidak mencukupi!');
                    paidInput.focus();
                    return;
                }

                // Set paid value for cashless
                if (method === 'cashless') {
                    paidInput.value = total;
                }

                // Submit form
                this.action = '{{ route('cashier.checkout.process') }}';
                this.submit();
            });

            // Form submission with validation - Desktop
            document.getElementById('checkoutFormDesktop')?.addEventListener('submit', function(e) {
                const method = document.getElementById('payment_method_desktop').value;
                const paidInput = document.getElementById('paidInputDesktop');
                const paid = parseFloat(paidInput.value || 0);

                // Get discount
                const discountSelect = document.getElementById('discountSelectDesktop');
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const percentage = parseFloat(selectedOption.dataset.percentage || 0);
                const total = subtotal - (subtotal * (percentage / 100));

                if (method === 'cash' && paid < total) {
                    e.preventDefault();
                    alert('Jumlah bayar tidak mencukupi!');
                    paidInput.focus();
                    return;
                }

                // Set paid value for cashless
                if (method === 'cashless') {
                    paidInput.value = total;
                }
            });

            // Alert auto hide
            document.addEventListener('DOMContentLoaded', () => {
                const alerts = ['successAlert', 'errorAlert'];
                alerts.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(() => el.classList.remove('opacity-0'), 100);
                        setTimeout(() => el.classList.add('opacity-0'), 3000);
                        setTimeout(() => el.remove(), 3500);
                    }
                });

                // Initialize calculations
                calculateTotal();
                calculateTotalDesktop();
            });
        </script>
    @endpush
@endsection
