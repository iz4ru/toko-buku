<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">

            <!-- Dashboard -->
            <li>
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    <i class="fa-solid fa-home text-md"></i>
                    <span class="ml-3">Dashboard</span>
                </x-nav-link>
            </li>

            <!-- Kelola Buku -->
            <li>
                <x-nav-link href="{{ route('admin.book') }}" :active="request()->routeIs(['admin.book', 'admin.book.create'])">
                    <i class="fa-solid fa-book text-md"></i>
                    <span class="ml-3">Kelola Buku</span>
                </x-nav-link>
            </li>

            <!-- Kelola Kategori Buku -->
            <li>
                <x-nav-link href="{{ route('admin.category') }}" :active="request()->routeIs(['admin.category', 'admin.category.create'])">
                    <i class="fa-solid fa-layer-group text-md"></i>
                    <span class="ml-3">Kelola Kategori Buku</span>
                </x-nav-link>
            </li>

            <!-- Kelola Stok & Harga Buku -->
            <li>
                <x-nav-link href="{{ route('admin.book_detail') }}" :active="request()->routeIs(['admin.book_detail', 'admin.book_detail.edit.stock', 'admin.book_detail.edit.price'])">
                    <i class="fa-solid fa-boxes-stacked text-md"></i>
                    <span class="ml-3">Kelola Stok & Harga Buku</span>
                </x-nav-link>
            </li>

            <!-- Riwayat Transaksi -->
            <li>
                <x-nav-link href="{{ route('admin.transaction') }}" :active="request()->routeIs(['admin.transaction', 'admin.transaction.detail'])">
                    <i class="fa-solid fa-money-bill text-md"></i>
                    <span class="ml-3">Riwayat Transaksi</span>
                </x-nav-link>
            </li>

            <!-- Kelola Data Kasir -->
            <li>
                <x-nav-link href="{{ route('admin.employee') }}" :active="request()->routeIs(['admin.employee'])">
                    <i class="fa-solid fa-user-group text-md"></i>
                    <span class="ml-3">Kelola Data Kasir</span>
                </x-nav-link>
            </li>

            <!-- Log Aktivitas-->
            <li>
                <x-nav-link href="{{ route('admin.log') }}" :active="request()->routeIs(['admin.log'])">
                    <i class="fa-solid fa-clock-rotate-left text-md"></i>
                    <span class="ml-3">Log Aktivitas</span>
                </x-nav-link>
            </li>

        </ul>
    </div>
</aside>
