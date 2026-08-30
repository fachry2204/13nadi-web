# 13 Nadi Records

Website publik dan CMS Nadiku dalam satu proyek Laravel + Vue 3 + TypeScript. Laravel menyajikan aplikasi Vue dan API pada origin yang sama.

## Menjalankan lokal

```powershell
D:\xampp\php\composer.bat install
npm install
npm run build
D:\xampp\php\php.exe artisan migrate
D:\xampp\php\php.exe artisan serve --port=8002
```

Buka `http://127.0.0.1:8002`. Portal admin berada di `/nadiku/login`; autentikasi menggunakan username dan sesi Sanctum. Untuk pengembangan aset secara langsung, jalankan `npm run dev` pada terminal kedua.

## Pemeriksaan

```powershell
npm run lint
npm run build
D:\xampp\php\php.exe artisan test
```

API memakai prefix `/api/v1`. Endpoint publik tidak membutuhkan token; endpoint admin memakai Bearer token Laravel Sanctum.
