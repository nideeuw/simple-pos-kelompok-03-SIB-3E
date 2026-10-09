<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Catatan Jawaban untuk Langkah 6 Tugas No 5 pada Jobsheet 6

### Soal

Kalau field `total` tetap dikirim dari form dan divalidasi dengan aturan `numeric`, apakah itu cukup mencegah manipulasi total? Kenapa atau kenapa tidak?

### Jawaban

Tidak cukup.

**Alasan:**
- Aturan `numeric` hanya mengecek bahwa isi field adalah angka, bukan apakah angkanya benar.
- Pengguna bisa mengubah isi form lewat DevTools atau `curl`. Misalnya belanja Rp 100.000, lalu dikirim `total=1`. Angka 1 tetap lolos `numeric`.
- Jadi validasi hanya memastikan format, bukan kebenaran nilai.

**Cara yang benar:**
- Jangan pakai `total` dari form sama sekali.
- Hitung ulang di server dari harga yang tersimpan di database (`$product->price * $item['qty']`).
- Di Simple POS ini sudah dilakukan di `TransactionController::store()`. Form hanya mengirim `product_id` dan `qty`.

**Kesimpulan:** data dari klien tidak bisa dipercaya, jadi nilai penting seperti total harus dihitung di server.

---

## Jawaban untuk Langkah 11 Tugas No 4 pada Jobsheet 7

### Skenario Uji Manual: Autentikasi dan Otorisasi (RBAC)

Persiapan:

```bash
php artisan migrate:fresh --seed
php artisan serve
npm run dev   # di terminal kedua
```

Akun demo (kata sandi semuanya `password`):

| Peran   | Email            |
|---------|------------------|
| admin   | admin@pos.test   |
| kasir   | kasir@pos.test   |
| manager | manager@pos.test |

### Skenario 1: Peran admin

| Langkah | Hasil yang diharapkan |
|---------|-----------------------|
| 1. Buka `/login`, masuk sebagai `admin@pos.test` | Diarahkan ke `/pos` |
| 2. Lihat navigasi | Menu Kasir, Transaksi, Produk, Kategori, nama dan peran "admin", serta tombol Keluar |
| 3. Buka `/products` | Daftar produk tampil |
| 4. Buka `/transactions` | Daftar transaksi tampil |
| 5. Klik Keluar | Diarahkan ke `/login` |

### Skenario 2: Peran kasir

| Langkah | Hasil yang diharapkan |
|---------|-----------------------|
| 1. Masuk sebagai `kasir@pos.test` | Diarahkan ke `/pos` |
| 2. Lihat navigasi | Hanya Kasir dan Transaksi, tanpa Produk dan Kategori, ada tombol Keluar |
| 3. Buka `/pos` dan `/transactions` | Keduanya tampil normal |
| 4. Buka `/products` lewat address bar | Halaman 403 |
| 5. Buka `/categories` lewat address bar | Halaman 403 |

### Skenario 3: Peran manager

| Langkah | Hasil yang diharapkan |
|---------|-----------------------|
| 1. Masuk sebagai `manager@pos.test` | Diarahkan ke `/pos` |
| 2. Lihat navigasi | Hanya Kasir dan Transaksi, nama dan peran "manager" |
| 3. Buka `/transactions` | Daftar transaksi tampil |
| 4. Buka `/products` | Halaman 403 |
| 5. Buka `/categories` | Halaman 403 |

### Skenario 4: Tamu (belum login)

| Langkah | Hasil yang diharapkan |
|---------|-----------------------|
| 1. Buka `/products` atau `/pos` | Diarahkan ke `/login` |
| 2. Buka `/info` | Halaman informasi tampil |
| 3. Masuk dengan kata sandi salah | Pesan "Email atau kata sandi salah." |
| 4. Jalankan `curl.exe -i http://127.0.0.1:8000/pos -H "Accept: application/json"` | Status 401, isi `{"message":"Unauthenticated."}` |
| 5. Masuk sebagai `kasir@pos.test` | Diarahkan ke `/pos` |
| 6. Masih login, buka `/info` lalu `/login` | Keduanya langsung diarahkan ke `/pos` |

### Skenario 5: Akun dinonaktifkan

| Langkah | Hasil yang diharapkan |
|---------|-----------------------|
| 1. Di `php artisan tinker`: `App\Models\User::where('email', 'kasir@pos.test')->update(['is_active' => false]);` | Keluar angka `1` |
| 2. Masuk sebagai `kasir@pos.test` | Pesan "Akun dinonaktifkan. Hubungi admin." |
| 3. Aktifkan lagi: `App\Models\User::where('email', 'kasir@pos.test')->update(['is_active' => true]);` lalu masuk | Berhasil masuk ke `/pos` |

### Catatan

Halaman `/categories` untuk admin belum memiliki tampilan (view `categories` belum pernah dibuat sejak pertemuan sebelumnya), sehingga tidak dimasukkan sebagai skenario "tampil". Pembatasan aksesnya tetap berlaku: kasir dan manager yang membuka `/categories` mendapat 403.
