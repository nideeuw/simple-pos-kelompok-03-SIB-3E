<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        Product::updateOrCreate(
            ['name' => $row['name'], 'category_id' => $row['category_id']],
            ['price' => $row['price']],
        );

        // null = jangan insert lagi, karena updateOrCreate sudah menyimpan
        return null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
