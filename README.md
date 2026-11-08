# SiPustaka HMVC (Laravel + nwidart/laravel-modules)

Zip ini berisi **kode sumber kustom** (overlay). Folder `vendor/` tidak disertakan,
jadi project Laravel dibuat dulu lewat Composer, lalu isi zip ini ditimpa ke dalamnya.

## Kebutuhan
PHP >= 8.2, Composer, MySQL/MariaDB + phpMyAdmin (XAMPP/Laragon), koneksi internet (Composer & CDN Bootstrap).

## Langkah 1 - Buat project & database
```bash
composer create-project laravel/laravel sipustaka_hmvc
cd sipustaka_hmvc
```
Buka phpMyAdmin -> buat database **db_sipustaka** (collation utf8mb4_unicode_ci).

Edit `.env`:
```
APP_NAME=SiPustaka
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipustaka
DB_USERNAME=root
DB_PASSWORD=
```
(Jika `.env` bawaan memakai sqlite, ganti sesuai di atas.)

## Langkah 2 - Pasang nwidart/laravel-modules & merge-plugin
```bash
composer require nwidart/laravel-modules
composer require wikimedia/composer-merge-plugin
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```
Jika Composer menanyakan izin plugin, jawab `y`. Lalu buka `composer.json` dan pastikan ada:
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/",
        "Modules\\": "Modules/"
    }
},
"extra": {
    "laravel": { "dont-discover": [] },
    "merge-plugin": {
        "include": [ "Modules/*/composer.json" ]
    }
},
"config": {
    "allow-plugins": {
        "wikimedia/composer-merge-plugin": true
    }
}
```
(Gabungkan dengan isi yang sudah ada; jangan menghapus key lain.)

## Langkah 3 - Buat 3 modul (kerangka)
```bash
php artisan module:make Buku Peminjaman UserManagement
composer dump-autoload
```

## Langkah 4 - Timpa dengan kode dari zip
Ekstrak zip ini ke **root** `sipustaka_hmvc` dan pilih **Replace/Timpa semua** (menimpa
`bootstrap/app.php`, `routes/web.php`, `app/Models/User.php`, serta file di `Modules/...`).

## Langkah 5 - Migrasi, seeder, storage link
```bash
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
php artisan serve
```
Buka http://127.0.0.1:8000

Akun seeder (password sama: `password`):
| Role  | Email                  |
|-------|------------------------|
| admin | admin@sipustaka.test   |
| user  | user@sipustaka.test    |

## Langkah 6 - Pengujian & bukti (screenshot untuk laporan)
1. **Register**: buka /register, daftar akun baru -> di phpMyAdmin kolom `role` = `user`.
2. **403**: login sebagai `user@sipustaka.test`, buka `/admin/users` (atau klik tombol merah di dashboard) -> muncul halaman 403. Screenshot halaman + URL.
3. **Buku**: login admin -> tambah/ubah/hapus buku (pesan sukses muncul), uji pencarian & pagination.
   Login user -> hanya daftar (tanpa tombol aksi); buka `/buku/create` -> 403.
4. **Peminjaman (AJAX)**: buka `/peminjaman`, tekan **F12 -> tab Network -> filter Fetch/XHR**, lalu tambah/ubah/hapus data.
   Screenshot daftar request (`data`, `peminjaman` POST, DELETE) tanpa halaman reload.
   Uji error 422: kosongkan form lalu simpan -> pesan merah tampil di bawah tiap input; di Network klik request berstatus 422 -> tab Response.
   Uji upload: jpg/png/pdf <= 2 MB -> tautan "Buka berkas" muncul; upload file > 2 MB atau .docx -> error 422.
5. **UserManagement**: login admin -> `/admin/users`. Tambah/ubah/hapus user. Coba ubah role akun sendiri
   menjadi user (ditolak, error di bawah input Role) dan hapus akun sendiri (tombol hapus tidak muncul; request manual ditolak).
6. **Storage**: buka folder `sipustaka_hmvc/storage/app/public/bukti-peminjaman/` -> screenshot isi file.
   Lalu phpMyAdmin -> db_sipustaka -> tabel `peminjaman` -> screenshot kolom `bukti`
   (nilai seperti `bukti-peminjaman/abc123.pdf`).
   Hapus data -> pastikan file hilang dari folder.

## Struktur
```
app/Http/Controllers/AuthController.php      (auth manual, di luar HMVC)
app/Http/Middleware/RoleMiddleware.php       (alias 'role' di bootstrap/app.php)
Modules/Buku            -> CRUD synchronous (admin: CRUD, user: lihat)
Modules/Peminjaman      -> CRUD AJAX (fetch + JSON + upload berkas)
Modules/UserManagement  -> CRUD user (admin only)
```
