@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Transaksi Kasir</h1>

    <div x-data="{
        cart: [],
        selectedProduct: null,
        
        addToCart(id, name, price, stock) {
            let item = this.cart.find(i => i.id === id);
            
            if (item) {
                if (item.qty < stock) {
                    item.qty++;
                } else {
                    alert(`Tidak bisa menambah! Stok ${name} maksimal ${stock}.`);
                }
            } else {
                if (stock > 0) {
                    this.cart.push({
                        id: id,
                        name: name,
                        price: Number(price),
                        qty: 1,
                        stock: Number(stock)
                    });
                } else {
                    alert(`Stok ${name} sedang kosong!`);
                }
            }
        },
        
        validateQty(item) {
            if (item.qty > item.stock) {
                item.qty = item.stock;
                alert(`Maksimal stok yang bisa dibeli: ${item.stock}`);
            } else if (item.qty < 1 || item.qty === '' || isNaN(item.qty)) {
                item.qty = 1;
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        
        subtotal() {
            return this.cart.reduce((sum, item) => sum + (Number(item.price) * Number(item.qty)), 0);
        }
    }">
        <div class="grid grid-cols-3 gap-4 mb-6">
            @foreach($products as $product)
                <div class="border rounded-md p-3 cursor-pointer hover:bg-slate-50 transition select-none shadow-sm"
                    :class="selectedProduct === {{ $product->id }} ? 'ring-2 ring-blue-500' : ''"
                    x-on:click="selectedProduct = {{ $product->id }}; addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">
                    
                    <div class="flex justify-between items-start mb-1">
                        <p class="font-medium text-slate-800">{{ $product->name }}</p>
                        
                        @if($product->stock <= 10)
                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                Stok Menipis
                            </span>
                        @endif
                    </div>
                    
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
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center justify-between py-2 border-b">
                        
                        <span class="text-slate-700 font-medium" x-text="item.name + ' - Rp ' + Number(item.price).toLocaleString('id-ID')"></span>

                        <div class="flex items-center gap-3">
                            <input type="number" 
                                   x-model.number="item.qty" 
                                   x-on:input="validateQty(item)"
                                   class="w-16 border rounded py-1 px-2 text-center text-sm shadow-sm outline-none" 
                                   min="1" :max="item.stock">
                            
                            <span class="font-bold text-slate-800 w-24 text-right" x-text="'Rp ' + (Number(item.price) * Number(item.qty)).toLocaleString('id-ID')"></span>

                            <button
                                type="button"
                                x-on:click="removeFromCart(index)"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-sm transition">
                                <i class="bi bi-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-4 pt-2 flex justify-between items-center text-lg font-bold text-slate-900 border-t">
                <span>Subtotal:</span>
                <span>Rp <span x-text="subtotal().toLocaleString('id-ID')"></span></span>
            </div>
        </div>
    </div>
</div>
@endsection
