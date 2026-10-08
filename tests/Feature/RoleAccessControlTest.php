<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    private const FORBIDDEN_MESSAGE = 'Anda tidak memiliki akses untuk halaman ini.';

    private function kasir(): User
    {
        return User::factory()->create([
            'email' => 'kasir@pos.test',
            'role' => 'kasir',
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'admin@pos.test',
            'role' => 'admin',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_products(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_logout_then_login_as_kasir_cannot_access_products(): void
    {
        $this->kasir();

        // 1. Login sebagai Kasir
        $this->post('/login', [
            'email' => 'kasir@pos.test',
            'password' => 'password',
        ])->assertRedirect(route('pos.create'));

        $this->assertAuthenticated();

        // 2. Logout dari akun yang aktif
        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        // 3. Login ulang sebagai Kasir
        $this->post('/login', [
            'email' => 'kasir@pos.test',
            'password' => 'password',
        ])->assertRedirect(route('pos.create'));

        // 4. Akses /products ditolak dengan halaman 403 kustom
        $this->get('/products')
            ->assertStatus(403)
            ->assertSee(self::FORBIDDEN_MESSAGE)
            ->assertDontSee('Daftar Produk');
    }

    public function test_kasir_can_access_pos_page(): void
    {
        $this->actingAs($this->kasir())
            ->get('/pos')
            ->assertOk();
    }

    public function test_admin_can_access_products_page(): void
    {
        $this->actingAs($this->admin())
            ->get('/products')
            ->assertOk()
            ->assertSee('Daftar Produk');
    }

    public function test_products_page_requires_authentication(): void
    {
        $this->get('/products')->assertRedirect('/login');
        $this->get('/products/create')->assertRedirect('/login');
    }

    public function test_login_page_rejects_kasir_credentials_with_wrong_password(): void
    {
        $this->kasir();

        $this->post('/login', [
            'email' => 'kasir@pos.test',
            'password' => 'salah',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');
    }
}
