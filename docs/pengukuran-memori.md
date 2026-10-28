# Pengukuran Memori: get() vs lazyById()
Dataset: sekitar 52.500 row pada tabel transactions

| Cara baca data | Peak memory (MB) |
|---|---|
| Transaction::lazyById() | 38.0 MB[cite: 13] |
| Transaction::get() | 96.0 MB[cite: 13] |

Kesimpulan kelompok: `Transaction::lazyById()` jauh lebih hemat memori karena mengambil dan memproses data secara bertahap (per *chunk*) serta langsung membersihkannya dari RAM, sedangkan `Transaction::get()` memuat seluruh data ke dalam memori secara bersamaan[cite: 10, 12, 13].