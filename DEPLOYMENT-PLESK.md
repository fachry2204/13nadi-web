# Deployment ke Plesk

1. Arahkan document root domain ke folder `public` proyek ini; aktifkan PHP 8.2+ dan ekstensi PDO MySQL, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON.
2. Unggah seluruh proyek, jalankan `composer install --no-dev --optimize-autoloader` dan `npm ci && npm run build`, salin `.env.example` ke `.env`, isi kredensial MySQL/SMTP/URL, lalu jalankan `php artisan key:generate` dan `php artisan migrate --seed --force`.
   Untuk kompatibilitas MariaDB di Plesk, gunakan `DB_CHARSET=utf8mb4` dan `DB_COLLATION=utf8mb4_unicode_ci`.
3. Jalankan `php artisan storage:link`, `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache`. Pastikan `storage` dan `bootstrap/cache` writable.
4. Tidak diperlukan domain frontend/API terpisah. Vue dilayani Laravel dan menggunakan endpoint `/api/v1` pada domain yang sama.
5. Aktifkan HTTPS, backup database, dan cron `php artisan schedule:run` setiap menit. Fallback SPA telah ditangani oleh rute Laravel.
6. Setelah deploy, uji `/up`, `/api/v1/public/home`, halaman publik, login admin, upload, dan CRUD.

Untuk produksi, ganti kredensial admin seed dan set `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`.
