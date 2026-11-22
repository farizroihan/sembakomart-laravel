# SembakoMart

**SembakoMart** adalah aplikasi web toko sembako berbasis **Laravel** dan **MySQL** dengan antarmuka responsif menggunakan **Bootstrap 5** agar nyaman diakses melalui PC, Android, maupun iPhone. Pelanggan dapat menelusuri katalog, mencari atau memfilter produk berdasarkan kategori, mengatur keranjang belanja, melakukan checkout dengan metode **Cash on Delivery (COD)**, dan memantau status pesanan. Sementara itu, admin dapat mengelola data produk sembako serta memperbarui status pesanan pelanggan.

---

## Teknologi dan Kebutuhan

| Komponen | Versi / Fungsi | Catatan |
| :--- | :--- | :--- |
| **Laravel** | 11 atau 12 | Backend MVC |
| **PHP** | 8.2 atau lebih baru | Bahasa pemrograman sisi server |
| **MySQL** | Database relasional | Menyimpan data pengguna, produk, dan transaksi |
| **Composer** | Dependency manager | Untuk memasang paket dan dependensi Laravel |
| **Bootstrap 5** | UI responsif | Mendukung tampilan desktop maupun layar kecil |

---

## Rancangan Database

Tabel `users` menggunakan pola autentikasi bawaan Laravel yang diperluas dengan kolom `phone`, `address`, dan `is_admin`. Tabel bisnis inti terdiri dari `categories`, `products`, `orders`, dan `order_items`.

| Tabel | Kolom Utama | Kegunaan |
| :--- | :--- | :--- |
| `users` | `id`, `name`, `email`, `phone`, `address`, `is_admin` | Menyimpan akun pelanggan dan admin |
| `categories` | `id`, `name`, `slug`, `description` | Kategori produk sembako |
| `products` | `id`, `category_id`, `name`, `slug`, `price`, `stock`, `image`, `is_active` | Katalog produk sembako yang dijual |
| `orders` | `id`, `user_id`, `order_number`, `customer_name`, `customer_phone`, `address`, `total`, `status` | Header transaksi pesanan pelanggan |
| `order_items` | `id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal` | Rincian item produk dalam setiap transaksi |

**Relasi Antar-Tabel:**
- `categories` (1-N) `products`
- `users` (1-N) `orders`
- `orders` (1-N) `order_items`
- `products` (1-N) `order_items`

Constraint *foreign key* diterapkan untuk menjaga konsistensi dan integritas hubungan data.

---

## Migration, Model, dan Controller

- **Migration (`database/migrations`)**: Mendefinisikan struktur tabel dalam kode PHP. Perintah `php artisan migrate` menerapkan skema ke MySQL, sedangkan **Database Seeder** mengisi akun admin default beserta data kategori dan produk contoh.
- **Model (`app/Models`)**: Merepresentasikan tabel database menggunakan Eloquent ORM (`User`, `Category`, `Product`, `Order`, `OrderItem`) dengan relasi seperti `Product belongsTo Category`, `Category hasMany Product`, dan `Order hasMany OrderItem`.
- **Controller (`app/Http/Controllers`)**: Mengatur request HTTP, validasi input, logika bisnis, dan pemanggilan view:
  - `HomeController`: Menampilkan halaman beranda, pencarian, dan detail katalog produk.
  - `AuthController`: Mengelola registrasi akun baru, login, dan logout pengguna.
  - `CartController`: Mengatur penambahan, pembaruan jumlah, dan penghapusan item keranjang.
  - `OrderController`: Memproses checkout COD, pengurangan stok, dan halaman riwayat pesanan pelanggan.
  - `Admin\ProductController`: Mengelola operasi CRUD (tambah, ubah, hapus) data produk oleh admin.
  - `Admin\OrderController`: Mengelola daftar pesanan masuk dan pembaruan status pesanan oleh admin.

---

## Instalasi dan Menjalankan Aplikasi

1. Pastikan **PHP 8.2+**, **Composer**, dan **MySQL** (misalnya melalui XAMPP) sudah terpasang.
2. Clone atau ekstrak proyek ini ke folder kerja lokal, lalu buka terminal di direktori root proyek (`SembakoMart_Laravel`).
3. Jalankan perintah berikut untuk mengunduh dependensi:
   ```bash
   composer install
   ```
4. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
5. Buat database kosong baru bernama `sembakomart` melalui phpMyAdmin atau klien MySQL.
6. Sesuaikan pengaturan database pada file `.env`:
   ```env
   DB_DATABASE=sembakomart
   DB_USERNAME=root
   DB_PASSWORD=
   ```
7. Generate application key:
   ```bash
   php artisan key:generate
   ```
8. Jalankan migrasi sekaligus mengisi data awal (seeder):
   ```bash
   php artisan migrate --seed
   ```
9. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Lalu buka `http://127.0.0.1:8000` di browser.

---

## Panduan Penggunaan

### Pelanggan
1. Buka halaman beranda, lalu cari produk melalui pencarian atau pilih kategori sembako.
2. Buka halaman detail produk, tentukan jumlah, lalu masukkan ke keranjang.
3. Daftar (*Register*) atau masuk (*Login*) sebelum melanjutkan proses checkout.
4. Lengkapi nama penerima, nomor HP, dan alamat pengiriman.
5. Pilih metode pembayaran **COD** lalu buat pesanan.
6. Buka menu **Pesanan Saya** untuk memantau status pesanan.

### Admin
> **Akun Demo Seeder:** `admin@sembakomart.test` / `Admin12345!`  
> *Gunakan hanya untuk pengujian lokal dan segera ganti password sebelum dipublikasikan.*

1. Login menggunakan akun admin (`is_admin = 1`).
2. Buka menu **Admin** untuk melihat ringkasan data.
3. Gunakan **Kelola Produk** untuk menambah, mengedit, atau menghapus produk.
4. Gunakan **Kelola Pesanan** untuk memperbarui status pesanan menjadi *Dikonfirmasi*, *Diproses*, *Dikirim*, *Selesai*, atau *Dibatalkan*.

---

## Pengujian dan Batasan Sistem

| Skenario Pengujian | Hasil yang Diharapkan | Lokasi Pemeriksaan |
| :--- | :--- | :--- |
| **Registrasi / Login** | Akun dapat dibuat dan masuk ke sistem | Tabel `users` |
| **Tambah Produk ke Keranjang** | Item dan jumlah tersimpan di dalam session | Halaman keranjang |
| **Checkout COD** | Transaksi dan item tercatat; stok produk berkurang | `orders`, `order_items`, `products` |
| **Update Status Admin** | Status pesanan berubah dan terlihat oleh pelanggan | Kolom `orders.status` |
| **Responsive** | Tampilan menyesuaikan ukuran layar perangkat | PC / Android / iPhone |

- **Batasan:** Metode pembayaran yang tersedia saat ini baru **COD**; belum tersedia integrasi *payment gateway*, kalkulasi ongkir otomatis, maupun notifikasi eksternal.
- **Deployment / Hosting:** Gunakan layanan hosting yang mendukung versi PHP Laravel yang sesuai, Composer/deploy folder `vendor`, database MySQL, HTTPS, serta *document root* yang diarahkan ke folder `public`.

---

## Dokumentasi Manual Book

File PDF manual book (termasuk pendalaman teknis dan kisi-kisi pertanyaan dosen) dapat disimpan di repository ini, misalnya pada `docs/manual_book_sembakomart.pdf` atau di root repository, agar mudah dibuka langsung oleh pemeriksa/dosen.