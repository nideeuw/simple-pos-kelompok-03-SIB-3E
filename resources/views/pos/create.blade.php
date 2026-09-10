@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Transaksi Kasir</h1>

    <div x-data="{
        cart: [],
        addToCart(id, name, price) {
            this.cart.push({
                id: id,
                name: name,
                price: Number(price)
            });
        },
        // Hapus spesifik 1 baris berdasarkan index urutan
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        subtotal() {
            return this.cart.reduce((sum, item) => sum + Number(item.price), 0);
        }
    }">
        <div class="grid grid-cols-3 gap-4 mb-6">
            @foreach ($products as $product)
                <div class="border rounded-md p-3 cursor-pointer hover:bg-slate-50 transition select-none shadow-sm"
                     @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                    <p class="font-medium text-slate-800">{{ $product->name }}</p>
                    <p class="text-sm text-slate-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="border-t pt-4">
            <h2 class="text-base font-semibold mb-3">Item Keranjang</h2>

            <p x-show="cart.length === 0" class="text-sm text-slate-400 italic py-2">
                Keranjang masih kosong.
            </p>

            <div class="space-y-2">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-slate-700 font-medium" x-text="item.name + ' - Rp ' + Number(item.price).toLocaleString('id-ID')"></span>

                        <button
                            type="button"
                            @click="removeFromCart(index)"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-sm transition">
                            <i class="bi bi-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </div>
                </template>
            </div>

            <div class="mt-4 pt-2 flex justify-between items-center text-lg font-bold text-slate-900">
                <span>Subtotal:</span>
                <span>Rp <span x-text="subtotal().toLocaleString('id-ID')"></span></span>
            </div>
        </div>
    </div>
</div>
@endsection
