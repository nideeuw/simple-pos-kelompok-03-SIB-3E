<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ApiTokenExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('mobile-kasir', ['products:read'], now()->addDay())->plainTextToken;

        // sebelum kedaluwarsa: token masih diterima
        $this->withToken($token)->getJson('/api/v1/products')->assertOk(); // 200

        // reset cache guard supaya request berikutnya mengecek token lagi
        $this->app['auth']->forgetGuards();

        // majukan waktu 2 hari: token sudah lewat masa berlaku
        Carbon::setTestNow(now()->addDays(2));

        $this->withToken($token)->getJson('/api/v1/products')->assertUnauthorized(); // 401

        Carbon::setTestNow();
    }
}
