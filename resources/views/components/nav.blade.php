<nav class="bg-slate-900 text-white px-4 py-3 flex gap-4 items-center">
    <span class="font-semibold">Simple POS</span>
        <a href="{{ route('pos.create') }}"
        class="{{ request()->routeIs('pos.create') ? 'text-blue-400 font-semibold' : 'hover:underline' }}">
            Kasir
        </a>

        <a href="{{ route('transactions.index') }}"
        class="{{ request()->routeIs('transactions.index') ? 'text-blue-400 font-semibold' : 'hover:underline' }}">
            Transaksi
        </a>

        @can('manage-products')
        <a href="{{ route('products.index') }}"
        class="{{ request()->routeIs('products.*') ? 'text-blue-400 font-semibold' : 'hover:underline' }}">
            Produk
        </a>
        @endcan

    @auth
    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button type="submit" class="text-sm text-slate-300 hover:text-white hover:underline">
            Logout ({{ auth()->user()->name }})
        </button>
    </form>
    @endauth
</nav>
