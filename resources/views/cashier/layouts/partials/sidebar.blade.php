<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">

            <!-- Dashboard -->
            <li>
                <x-nav-link href="{{ route('cashier.dashboard') }}" :active="request()->routeIs('cashier.dashboard')">
                    <i class="fa-solid fa-home text-md"></i>
                    <span class="ml-3">Dashboard</span>
                </x-nav-link>
            </li>
            
            <!-- Transaksi -->
            <li>
                <x-nav-link href="{{ route('cashier.shop') }}" :active="request()->routeIs('cashier.shop')">
                    <i class="fa-solid fa-cart-shopping text-md"></i>
                    <span class="ml-3">Transaksi</span>
                </x-nav-link>
            </li>


            <!-- Riwayat Transaksi -->
            <li>
                <x-nav-link href="{{ route('admin.transaction') }}" :active="request()->routeIs(['admin.transaction', 'admin.transaction.detail'])">
                    <i class="fa-solid fa-money-bill text-md"></i>
                    <span class="ml-3">Riwayat Transaksi</span>
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
