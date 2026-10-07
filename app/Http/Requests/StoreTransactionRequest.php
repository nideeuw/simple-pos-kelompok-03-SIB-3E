<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $totals = collect($this->input('items'))
                    ->groupBy('product_id')
                    ->map(fn($rows) => $rows->sum('qty'));

                $products = Product::whereIn('id', $totals->keys())->get()->keyBy('id');

                foreach ($totals as $productId => $qty) {
                    $product = $products[$productId];

                    if ($qty > $product->stock) {
                        $validator->errors()->add(
                            'items',
                            "Stok {$product->name} tidak cukup (tersisa {$product->stock}, diminta {$qty})."
                        );
                    }
                }
            },
        ];
    }
}
