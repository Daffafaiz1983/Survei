# Laravel PHP 8.4 Compatibility Patch

## Masalah
Laravel Framework v12.x menggunakan `PDO::connect()` yang hanya tersedia di PHP 8.4+, menyebabkan error PHPStan saat menggunakan PHP 8.3.

## Solusi
Patch ini memperbaiki masalah kompatibilitas dengan menambahkan pengecekan `method_exists()` sebelum menggunakan `PDO::connect()`.

## Cara Menggunakan

### 1. Terapkan Patch
```bash
php patch-laravel-php84.php
```

### 2. Setelah Composer Update
Jika Anda menjalankan `composer update`, patch akan hilang. Jalankan lagi:
```bash
php patch-laravel-php84.php
```

### 3. Otomatis dengan Composer Script
Tambahkan ke `composer.json`:
```json
{
    "scripts": {
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
            "php patch-laravel-php84.php"
        ]
    }
}
```

## File yang Dimodifikasi
- `vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php`

## Backup
Script ini otomatis membuat backup file asli dengan timestamp.

## Verifikasi
Jalankan `php artisan migrate:status` untuk memastikan aplikasi berjalan dengan baik.
