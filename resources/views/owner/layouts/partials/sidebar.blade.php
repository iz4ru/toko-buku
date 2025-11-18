<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">

            <!-- Dashboard -->
            <li>
                <x-nav-link href="{{ route('owner.dashboard') }}" :active="request()->routeIs('owner.dashboard')">
                    <i class="fa-solid fa-home text-md"></i>
                    <span class="ml-3">Dashboard</span>
                </x-nav-link>
            </li>

            <!-- Kelola Buku -->
            <li>
                <x-nav-link href="{{ route('owner.book') }}" :active="request()->routeIs(['owner.book', 'owner.book.create'])">
                    <i class="fa-solid fa-book text-md"></i>
                    <span class="ml-3">Kelola Buku</span>
                </x-nav-link>
            </li>

            <!-- Kelola Kategori Buku -->
            <li>
                <x-nav-link href="{{ route('owner.category') }}" :active="request()->routeIs(['owner.category', 'owner.category.create'])">
                    <i class="fa-solid fa-layer-group text-md"></i>
                    <span class="ml-3">Kelola Kategori Buku</span>
                </x-nav-link>
            </li>

            <!-- Kelola Stok & Harga Buku -->
            <li>
                <x-nav-link href="{{ route('owner.book_detail') }}" :active="request()->routeIs([
                    'owner.book_detail',
                    'owner.book_detail.edit.stock',
                    'owner.book_detail.edit.price',
                ])">
                    <i class="fa-solid fa-boxes-stacked text-md"></i>
                    <span class="ml-3">Kelola Stok & Harga Buku</span>
                </x-nav-link>
            </li>

            <!-- Riwayat Transaksi -->
            <li>
                <x-nav-link href="{{ route('owner.transaction') }}" :active="request()->routeIs([
                    'owner.transaction',
                    'owner.transaction.edit',
                    'owner.transaction.receipt',
                ])">
                    <i class="fa-solid fa-money-bill text-md"></i>
                    <span class="ml-3">Riwayat Transaksi</span>
                </x-nav-link>
            </li>
            
            <!-- Laporan Penjualan -->
            <li>
                <x-nav-link href="{{ route('owner.report') }}" :active="request()->routeIs(['owner.report', 'owner.report.print', 'owner.report.export'])">
                    <i class="fa-solid fa-chart-line text-md"></i>
                    <span class="ml-3">Laporan Penjualan</span>
                </x-nav-link>
            </li>

            <!-- Kelola Data Admin & Kasir -->
            <li>
                <x-nav-link href="{{ route('owner.employee') }}" :active="request()->routeIs(['owner.employee', 'owner.employee.create', 'owner.employee.edit'])">
                    <i class="fa-solid fa-user-gear text-md"></i>
                    <span class="ml-3">Kelola Data Admin & Kasir</span>
                </x-nav-link>
            </li>


            <!-- Log Aktivitas-->
            <li>
                <x-nav-link href="{{ route('owner.log') }}" :active="request()->routeIs(['owner.log'])">
                    <i class="fa-solid fa-clock-rotate-left text-md"></i>
                    <span class="ml-3">Log Aktivitas User</span>
                </x-nav-link>
            </li>

        </ul>
    </div>
</aside>
