<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable; 
use Illuminate\Database\Eloquent\Builder;

class IdempotencyKey extends Model
{
    use HasFactory, Prunable; 

    protected $fillable = [
        'user_id',
        'key',
        'request_hash',
        'response_code',
        'response_body',
        'response_status'
    ];

    // 3. Definisikan query untuk data yang dianggap kedaluwarsa
    public function prunable(): Builder
    {
        // Menargetkan baris yang dibuat lebih dari 24 jam yang lalu
        return static::where('created_at', '<=', now()->subDay());
    }
}