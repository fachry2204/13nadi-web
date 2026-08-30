# 13 Nadi — Website Company Profile Label Musik

## 1. Ringkasan Proyek

Bangun website company profile untuk **13 Nadi** dengan arsitektur:

- **Frontend:** Vite + React + TypeScript
- **Backend:** Laravel 10/11 REST API
- **Database:** MySQL
- **ORM Backend:** Laravel Eloquent
- **Tidak menggunakan Prisma**
- **UI:** Tailwind CSS + shadcn/ui atau komponen custom
- **Icons:** Lucide React
- **HTTP Client:** Axios
- **Auth Admin:** Laravel Sanctum
- **Deployment:** Production-ready di Plesk
- **Tema:** Light Mode, modern, premium, dominan **biru muda**
- **Nuansa:** Music label / music industry dengan ornamen not musik, waveform, vinyl, equalizer, microphone, headphone, dan overlay abstrak audio pada card/section.

Target utama:
1. Landing page company profile yang modern dan responsif.
2. Semua konten landing page dapat dikelola melalui Admin CMS.
3. Login admin memakai **username + password**, bukan email.
4. Menu setting untuk logo, favicon, identitas website, user admin, dan SMTP.
5. CRUD lengkap untuk Slider Hero, Tentang, Rilisan, Artis, Gallery Foto, dan Gallery Video.
6. Semua halaman diuji agar tidak ada broken route, missing asset, API error, hydration issue, CORS problem, upload error, atau bug responsive.

---

## 2. Arah Desain

### 2.1 Warna

Gunakan light theme.

```txt
Primary 50    #F0F9FF
Primary 100   #E0F2FE
Primary 200   #BAE6FD
Primary 300   #7DD3FC
Primary 400   #38BDF8
Primary 500   #0EA5E9
Primary 600   #0284C7
Primary 700   #0369A1

Text Main     #0F172A
Text Muted    #64748B
Background    #F8FBFF
Surface       #FFFFFF
Border        #DBEAFE
Success       #16A34A
Danger        #DC2626
Warning       #F59E0B
```

### 2.2 Tipografi

Rekomendasi:

- Heading: **Poppins / Plus Jakarta Sans**
- Body: **Inter**
- Display accent: tetap sans-serif, jangan script font berlebihan.

### 2.3 Gaya Visual

Semua section dan card harus memiliki sentuhan musik, tetapi tetap profesional:

- pattern not musik opacity 3–8%
- waveform sebagai divider section
- icon headphone, mic, vinyl, equalizer
- glow biru muda tipis
- rounded corner 18–24 px
- shadow lembut
- border biru muda
- hover ringan
- tidak memakai background gelap
- tidak memakai neon berlebihan
- animasi masuk lembut dengan Framer Motion
- gunakan glass effect hanya tipis di area tertentu

---

# 3. Struktur Repository

```txt
13nadi/
├── frontend/
│   ├── src/
│   │   ├── assets/
│   │   ├── components/
│   │   ├── layouts/
│   │   ├── pages/
│   │   │   ├── public/
│   │   │   └── admin/
│   │   ├── routes/
│   │   ├── services/
│   │   ├── hooks/
│   │   ├── stores/
│   │   ├── types/
│   │   └── utils/
│   ├── public/
│   ├── .env
│   └── vite.config.ts
│
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Middleware/
│   │   │   └── Requests/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Support/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php
│   ├── storage/
│   ├── .env
│   └── composer.json
│
└── README.md
```

---

# 4. Landing Page

## 4.1 Header

Komponen:

- Logo 13 Nadi
- Navigation:
  - Beranda
  - Tentang
  - Rilisan
  - Artis
  - Gallery Foto
  - Gallery Video
- CTA optional: Hubungi Kami
- Sticky header
- Background putih transparan saat top, putih solid saat scroll
- Mobile menu drawer

Header diatur dari Settings:
- logo
- logo mobile
- favicon
- site name
- site tagline

---

## 4.2 Hero Slider

Slider full-width dan responsive.

Field per slide:

```txt
id
title
subtitle
description
desktop_image
mobile_image
button_text
button_url
secondary_button_text
secondary_button_url
text_position
overlay_opacity
sort_order
is_active
start_at nullable
end_at nullable
```

Fitur:

- autoplay configurable
- navigation arrow
- pagination bullet
- swipe di mobile
- lazy loading image
- desktop/mobile image berbeda
- optional schedule
- preview di admin
- fallback image
- overlay waveform/not musik

Rekomendasi dimensi:
- Desktop: 1920 × 900
- Mobile: 1080 × 1350

---

## 4.3 Tentang 13 Nadi

Konten:

- eyebrow text
- heading
- deskripsi
- image
- optional statistik:
  - total artis
  - total rilisan
  - tahun berdiri
  - partner/channel

Field:

```txt
title
subtitle
description
image
vision
mission
stats_json
is_active
```

Tampilan:

- layout dua kolom desktop
- image dengan music-note ornament
- waveform sebagai background decoration

---

## 4.4 Rilisan Saat Ini

Tampilan card.

Field:

```txt
id
title
slug
artist_id
cover_image
release_type
release_date
description
spotify_url
apple_music_url
youtube_music_url
youtube_url
other_link
is_featured
is_active
sort_order
```

Card menampilkan:

- cover
- judul
- nama artis
- tanggal
- tipe Single / EP / Album
- tombol dengarkan
- social DSP icons

Interaksi:

- hover cover scale halus
- icon vinyl / equalizer overlay
- detail dapat memakai modal atau route `/rilisan/:slug`

---

## 4.5 Artis Saat Ini — Slider

Field:

```txt
id
name
slug
photo
cover_image
short_bio
full_bio
genre
instagram_url
tiktok_url
youtube_url
spotify_url
is_featured
is_active
sort_order
```

Slider menampilkan:

- foto artis
- nama
- genre
- button lihat profil
- music ornament di card

Detail optional:

```txt
/artis/:slug
```

---

## 4.6 Gallery Foto

Grid masonry atau responsive grid.

Field:

```txt
id
title
image
thumbnail
caption
taken_at
sort_order
is_active
```

Fitur:

- lightbox
- lazy loading
- WebP
- zoom
- keyboard navigation desktop
- swipe mobile

---

## 4.7 Gallery Video

Gunakan embed YouTube/Vimeo, jangan upload video besar ke server untuk tahap awal.

Field:

```txt
id
title
provider
video_url
video_id
thumbnail
description
sort_order
is_active
```

Tampilan:

- card 16:9
- thumbnail
- play icon
- modal player
- lazy load iframe setelah user klik play

---

## 4.8 Footer

Konten dari Settings:

- logo
- deskripsi singkat
- address
- email
- phone
- WhatsApp
- Instagram
- TikTok
- YouTube
- Spotify
- copyright
- privacy link
- terms link

Tambahkan:
- waveform divider
- music icon ornament
- warna putih / biru sangat muda

---

# 5. Admin CMS

Route:

```txt
/admin/login
/admin
```

Jika ingin URL branded:

```txt
/nadiku/login
/nadiku
```

Direkomendasikan memakai `/nadiku`.

---

## 5.1 Login Admin

Login dengan:

```txt
username
password
remember_me
```

Tidak memakai email untuk login.

Security:

- bcrypt/argon password
- Sanctum
- login rate limit
- CSRF protection
- logout all sessions optional
- session expiration
- failed login log
- account active flag

---

## 5.2 Admin Layout

Sidebar:

- Dashboard
- Slider Hero
- Tentang 13 Nadi
- Rilisan
- Artis
- Gallery Foto
- Gallery Video
- Pengaturan
  - Identitas Website
  - Logo & Favicon
  - Social Media
  - SMTP Email
  - User Admin
- Activity Log
- Logout

Topbar:

- breadcrumb
- notification placeholder
- admin profile
- quick preview website

---

# 6. Dashboard Admin

Card:

- Total Slider
- Total Rilisan
- Total Artis
- Total Foto
- Total Video
- Total Admin

Widget:

- rilisan terbaru
- konten terakhir diubah
- user activity
- storage usage
- status SMTP
- website status

Quick action:

- Tambah Slider
- Tambah Rilisan
- Tambah Artis
- Upload Foto
- Tambah Video

---

# 7. CRUD Admin

Semua modul wajib memiliki:

- list
- search
- filter
- pagination
- create
- edit
- detail preview
- delete
- bulk delete optional
- toggle active
- drag-sort atau sort order
- form validation
- confirmation dialog
- toast success/error
- empty state
- loading skeleton

---

# 8. Media Upload

Backend Laravel.

Path:

```txt
storage/app/public/site
storage/app/public/sliders
storage/app/public/releases
storage/app/public/artists
storage/app/public/gallery/photos
storage/app/public/gallery/videos
```

Upload validation:

```txt
JPG
JPEG
PNG
WEBP
SVG hanya untuk logo jika tervalidasi
maksimum 5MB default
```

Gunakan image processing:

- resize
- compress
- convert WebP
- preserve original optional
- thumbnail generator

Library rekomendasi:
- Intervention Image

Laravel storage:

```bash
php artisan storage:link
```

---

# 9. Struktur Database

## users

```sql
id BIGINT PK
name VARCHAR(150)
username VARCHAR(100) UNIQUE
email VARCHAR(190) NULL
password VARCHAR(255)
role VARCHAR(50)
is_active BOOLEAN DEFAULT TRUE
last_login_at DATETIME NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

## hero_sliders

```sql
id BIGINT PK
title VARCHAR(255)
subtitle VARCHAR(255) NULL
description TEXT NULL
desktop_image VARCHAR(255)
mobile_image VARCHAR(255) NULL
button_text VARCHAR(100) NULL
button_url VARCHAR(255) NULL
secondary_button_text VARCHAR(100) NULL
secondary_button_url VARCHAR(255) NULL
text_position VARCHAR(50) DEFAULT 'left'
overlay_opacity DECIMAL(3,2) DEFAULT 0.15
sort_order INT DEFAULT 0
is_active BOOLEAN DEFAULT TRUE
start_at DATETIME NULL
end_at DATETIME NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

## about_sections

```sql
id BIGINT PK
title VARCHAR(255)
subtitle VARCHAR(255) NULL
description LONGTEXT
image VARCHAR(255) NULL
vision TEXT NULL
mission TEXT NULL
stats_json JSON NULL
is_active BOOLEAN DEFAULT TRUE
created_at TIMESTAMP
updated_at TIMESTAMP
```

## artists

```sql
id BIGINT PK
name VARCHAR(255)
slug VARCHAR(255) UNIQUE
photo VARCHAR(255)
cover_image VARCHAR(255) NULL
short_bio TEXT NULL
full_bio LONGTEXT NULL
genre VARCHAR(255) NULL
instagram_url VARCHAR(255) NULL
tiktok_url VARCHAR(255) NULL
youtube_url VARCHAR(255) NULL
spotify_url VARCHAR(255) NULL
is_featured BOOLEAN DEFAULT FALSE
is_active BOOLEAN DEFAULT TRUE
sort_order INT DEFAULT 0
created_at TIMESTAMP
updated_at TIMESTAMP
```

## releases

```sql
id BIGINT PK
artist_id BIGINT FK
title VARCHAR(255)
slug VARCHAR(255) UNIQUE
cover_image VARCHAR(255)
release_type ENUM('single','ep','album')
release_date DATE NULL
description LONGTEXT NULL
spotify_url VARCHAR(255) NULL
apple_music_url VARCHAR(255) NULL
youtube_music_url VARCHAR(255) NULL
youtube_url VARCHAR(255) NULL
other_link VARCHAR(255) NULL
is_featured BOOLEAN DEFAULT FALSE
is_active BOOLEAN DEFAULT TRUE
sort_order INT DEFAULT 0
created_at TIMESTAMP
updated_at TIMESTAMP
```

## gallery_photos

```sql
id BIGINT PK
title VARCHAR(255) NULL
image VARCHAR(255)
thumbnail VARCHAR(255) NULL
caption TEXT NULL
taken_at DATE NULL
sort_order INT DEFAULT 0
is_active BOOLEAN DEFAULT TRUE
created_at TIMESTAMP
updated_at TIMESTAMP
```

## gallery_videos

```sql
id BIGINT PK
title VARCHAR(255)
provider VARCHAR(50)
video_url VARCHAR(255)
video_id VARCHAR(255) NULL
thumbnail VARCHAR(255) NULL
description TEXT NULL
sort_order INT DEFAULT 0
is_active BOOLEAN DEFAULT TRUE
created_at TIMESTAMP
updated_at TIMESTAMP
```

## settings

```sql
id BIGINT PK
group_name VARCHAR(100)
setting_key VARCHAR(150) UNIQUE
setting_value LONGTEXT NULL
setting_type VARCHAR(50) DEFAULT 'text'
is_public BOOLEAN DEFAULT FALSE
created_at TIMESTAMP
updated_at TIMESTAMP
```

## activity_logs

```sql
id BIGINT PK
user_id BIGINT NULL
action VARCHAR(100)
module VARCHAR(100)
record_id BIGINT NULL
description TEXT NULL
ip_address VARCHAR(45) NULL
user_agent TEXT NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

---

# 10. API

Base:

```txt
/api/v1
```

Public:

```txt
GET /public/site-settings
GET /public/home
GET /public/sliders
GET /public/about
GET /public/releases
GET /public/releases/{slug}
GET /public/artists
GET /public/artists/{slug}
GET /public/gallery/photos
GET /public/gallery/videos
```

Auth:

```txt
POST /auth/login
POST /auth/logout
GET  /auth/me
```

Admin:

```txt
GET    /admin/dashboard

GET    /admin/sliders
POST   /admin/sliders
GET    /admin/sliders/{id}
PUT    /admin/sliders/{id}
DELETE /admin/sliders/{id}

GET    /admin/about
PUT    /admin/about/{id}

GET    /admin/releases
POST   /admin/releases
PUT    /admin/releases/{id}
DELETE /admin/releases/{id}

GET    /admin/artists
POST   /admin/artists
PUT    /admin/artists/{id}
DELETE /admin/artists/{id}

GET    /admin/photos
POST   /admin/photos
PUT    /admin/photos/{id}
DELETE /admin/photos/{id}

GET    /admin/videos
POST   /admin/videos
PUT    /admin/videos/{id}
DELETE /admin/videos/{id}

GET    /admin/settings
PUT    /admin/settings

GET    /admin/users
POST   /admin/users
PUT    /admin/users/{id}
DELETE /admin/users/{id}

GET    /admin/activity-logs
```

Tambahkan endpoint:

```txt
PATCH /admin/{module}/{id}/status
POST  /admin/{module}/reorder
```

---

# 11. Frontend Routes

Public:

```txt
/
 /rilisan/:slug
 /artis/:slug
 /privacy
 /terms
 /404
```

Admin:

```txt
/nadiku/login
/nadiku
/nadiku/slider
/nadiku/slider/create
/nadiku/slider/:id/edit
/nadiku/tentang
/nadiku/rilisan
/nadiku/rilisan/create
/nadiku/rilisan/:id/edit
/nadiku/artis
/nadiku/artis/create
/nadiku/artis/:id/edit
/nadiku/gallery/foto
/nadiku/gallery/video
/nadiku/settings/general
/nadiku/settings/branding
/nadiku/settings/social
/nadiku/settings/smtp
/nadiku/settings/users
/nadiku/activity
```

Gunakan protected route untuk semua `/nadiku/*` selain login.

---

# 12. Frontend Component Map

```txt
components/
├── public/
│   ├── Header.tsx
│   ├── HeroSlider.tsx
│   ├── AboutSection.tsx
│   ├── CurrentReleases.tsx
│   ├── ArtistSlider.tsx
│   ├── PhotoGallery.tsx
│   ├── VideoGallery.tsx
│   ├── MusicOrnament.tsx
│   ├── WaveformDivider.tsx
│   └── Footer.tsx
│
├── admin/
│   ├── AdminSidebar.tsx
│   ├── AdminTopbar.tsx
│   ├── StatsCard.tsx
│   ├── DataTable.tsx
│   ├── ImageUploader.tsx
│   ├── ConfirmDialog.tsx
│   ├── StatusToggle.tsx
│   ├── SortableList.tsx
│   └── FormActions.tsx
│
└── ui/
```

---

# 13. State & Data Fetching

Gunakan salah satu:

- TanStack Query untuk server-state
- Zustand hanya untuk global UI state jika diperlukan

Rekomendasi:

```txt
TanStack Query + Axios
```

Jangan simpan seluruh response CMS di localStorage.

Axios:

- base URL dari env
- interceptor 401
- CSRF/Sanctum config
- normalized error handling

---

# 14. SEO

Setiap public page wajib:

- title
- description
- canonical
- Open Graph
- Twitter card
- favicon
- structured data Organization
- Artist schema optional
- MusicAlbum / MusicRecording optional

Landing:

```txt
13 Nadi — Music Label
```

Sitemap:

```txt
/sitemap.xml
/robots.txt
```

---

# 15. Accessibility

Wajib:

- alt image
- keyboard accessible slider
- focus states
- aria-label icon buttons
- contrast WCAG AA
- semantic heading hierarchy
- reduced motion support
- no autoplay video dengan suara

---

# 16. Responsive

Breakpoint minimal:

```txt
mobile    < 640
tablet    640–1023
desktop   >= 1024
wide      >= 1440
```

Tes:

- 360×800
- 390×844
- 768×1024
- 1024×768
- 1366×768
- 1440×900
- 1920×1080

---

# 17. SMTP Settings

Admin dapat mengatur:

```txt
MAIL_MAILER
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD
MAIL_ENCRYPTION
MAIL_FROM_ADDRESS
MAIL_FROM_NAME
```

Jangan kirim password SMTP ke endpoint public.

Backend:

- encrypt SMTP password sebelum disimpan
- endpoint test email
- mask secret saat edit

Menu:
`/nadiku/settings/smtp`

Button:
**Kirim Email Test**

---

# 18. User Admin

Field:

```txt
name
username
email optional
password
password_confirmation
role
is_active
```

Role awal:

```txt
super_admin
admin
editor
```

Rules:

- username unique
- super_admin tidak boleh menghapus dirinya sendiri
- minimal satu super_admin aktif
- reset password
- force logout optional

---

# 19. Security

Laravel:

- validation FormRequest
- Eloquent mass-assignment protection
- sanitize rich text
- rate limiting login
- CORS whitelist
- CSRF
- Sanctum
- secure cookie production
- HTTPS only
- validation MIME
- randomized upload filename
- block executable uploads
- role middleware
- activity log
- `.env` tidak boleh public

Production:

```txt
APP_ENV=production
APP_DEBUG=false
```

---

# 20. Performance

Frontend:

- code splitting
- lazy route
- lazy images
- `loading="lazy"`
- WebP
- responsive image
- preconnect Google Fonts bila digunakan
- minimize bundle

Backend:

- query eager loading
- indexes
- API pagination
- cache public settings
- cache home payload
- config cache
- route cache

Laravel production:

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

# 21. Plesk Deployment

Contoh:

```txt
public_html/
├── index.html
├── assets/
└── ...

laravel/
├── app/
├── bootstrap/
├── config/
├── public/
├── routes/
├── storage/
└── vendor/
```

Opsi production yang disarankan:

### Frontend

Build:

```bash
cd frontend
npm ci
npm run build
```

Upload isi `dist` ke domain public.

### Backend

```bash
cd backend
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Set document root backend/API ke:

```txt
backend/public
```

Jika frontend dan API terpisah:

```txt
https://13nadi.id
https://api.13nadi.id
```

Atau:

```txt
https://13nadi.id
https://13nadi.id/api
```

Sesuaikan proxy / document root Plesk.

---

# 22. Environment Frontend

`.env.production`

```env
VITE_APP_NAME="13 Nadi"
VITE_API_BASE_URL="https://api.domainanda.com/api/v1"
```

---

# 23. Environment Laravel

```env
APP_NAME="13 Nadi"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.domainanda.com

FRONTEND_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=13nadi
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_DOMAIN=.domainanda.com
SANCTUM_STATEFUL_DOMAINS=domainanda.com,www.domainanda.com
```

---

# 24. Seed Default Admin

Buat seeder.

Contoh akun hanya untuk instalasi lokal:

```txt
username: admin
password: ganti-segera
```

Saat login pertama:

- paksa ganti password.

Jangan hardcode credential production di repository.

---

# 25. UX Admin

Semua form:

- label jelas
- helper text
- validation inline
- preview image
- drag-drop upload
- progress upload
- save
- save & continue editing
- cancel
- dirty form warning
- toast notification
- loading state
- disabled state saat request berjalan

---

# 26. Music Ornament System

Buat reusable component:

```tsx
<MusicOrnament
  type="notes|waveform|vinyl|equalizer|headphone"
  position="top-right"
  opacity={0.06}
/>
```

Gunakan maksimal 1–3 ornament visual per section agar tidak ramai.

Contoh penerapan:

- Hero → waveform + floating notes
- About → treble clef
- Releases → vinyl / equalizer
- Artists → microphone / note
- Photo → note cluster
- Video → play + waveform
- Footer → continuous waveform

---

# 27. Validasi Modul — Definition of Done

## Public Landing

- [ ] Header tampil
- [ ] Logo tampil dari Settings
- [ ] Hero slider terhubung API
- [ ] Hero mobile image bekerja
- [ ] Tentang tampil dari CMS
- [ ] Rilisan tampil dari CMS
- [ ] Artist slider tampil dari CMS
- [ ] Gallery foto tampil
- [ ] Lightbox bekerja
- [ ] Gallery video tampil
- [ ] Video modal bekerja
- [ ] Footer tampil dari Settings
- [ ] Empty state aman
- [ ] API error fallback aman
- [ ] Loading skeleton ada
- [ ] Tidak ada horizontal overflow

## Admin

- [ ] Login username bekerja
- [ ] Logout bekerja
- [ ] Protected route bekerja
- [ ] Dashboard bekerja
- [ ] CRUD slider bekerja
- [ ] CRUD Tentang bekerja
- [ ] CRUD rilisan bekerja
- [ ] CRUD artis bekerja
- [ ] CRUD foto bekerja
- [ ] CRUD video bekerja
- [ ] Setting branding bekerja
- [ ] Setting sosial bekerja
- [ ] Setting SMTP bekerja
- [ ] Test SMTP bekerja
- [ ] User admin CRUD bekerja
- [ ] Permission role bekerja
- [ ] Upload media bekerja
- [ ] Delete media aman
- [ ] Activity log bekerja

---

# 28. Bug & QA Checklist

Sebelum production jalankan:

```bash
npm run lint
npm run build
php artisan test
php artisan migrate:status
```

Frontend QA:

- [ ] tidak ada TypeScript error
- [ ] tidak ada ESLint error blocking
- [ ] tidak ada console error
- [ ] tidak ada network request 404/500
- [ ] refresh direct route tidak 404
- [ ] image fallback berfungsi
- [ ] CORS benar
- [ ] login tidak loop
- [ ] logout menghapus session
- [ ] form double-submit dicegah
- [ ] route 404 tersedia
- [ ] responsive semua breakpoint

Backend QA:

- [ ] migration fresh berhasil
- [ ] seeder berhasil
- [ ] semua API response konsisten
- [ ] authorization diuji
- [ ] validation diuji
- [ ] upload file invalid ditolak
- [ ] SQL injection tidak mungkin melalui raw input
- [ ] N+1 query diperiksa
- [ ] endpoint public tidak expose secret
- [ ] APP_DEBUG false di production

Browser:

- [ ] Chrome
- [ ] Edge
- [ ] Firefox
- [ ] Safari iPhone
- [ ] Chrome Android

---

# 29. Automated Tests

Backend:

```txt
AuthLoginTest
HeroSliderCrudTest
AboutCrudTest
ReleaseCrudTest
ArtistCrudTest
PhotoGalleryCrudTest
VideoGalleryCrudTest
SettingTest
AdminUserTest
PublicHomeApiTest
UploadSecurityTest
```

Frontend:

- Vitest
- React Testing Library
- Playwright untuk E2E

E2E minimum:

```txt
login admin
create slider
edit slider
delete slider
create artist
create release
update branding
logout
open public page
verify public content
```

---

# 30. Error Handling Standard

API response:

```json
{
  "success": true,
  "message": "Data berhasil disimpan",
  "data": {}
}
```

Validation error:

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "title": ["Judul wajib diisi"]
  }
}
```

Frontend wajib menampilkan pesan manusiawi, bukan raw stack trace.

---

# 31. Tahapan Implementasi Codex

Kerjakan secara berurutan:

1. Inisialisasi Laravel.
2. Setup MySQL.
3. Buat migration.
4. Buat model + relationship.
5. Buat seeder.
6. Setup Sanctum.
7. Buat auth username.
8. Buat FormRequest.
9. Buat service upload.
10. Buat semua Admin API.
11. Buat Public API.
12. Buat test Laravel.
13. Inisialisasi Vite React TypeScript.
14. Setup Tailwind.
15. Setup React Router.
16. Setup Axios.
17. Setup TanStack Query.
18. Bangun Landing.
19. Bangun Admin Login.
20. Bangun Admin Layout.
21. Bangun Dashboard.
22. Bangun semua CRUD.
23. Bangun Settings.
24. Tambah music ornament system.
25. Responsive test.
26. Run lint.
27. Run unit test.
28. Run E2E test.
29. Build production.
30. Verifikasi Plesk config.
31. Uji website setelah deployment.

---

# 32. Instruksi Penting Untuk Codex

> Implementasikan seluruh sistem secara utuh. Jangan membuat halaman placeholder yang tidak berfungsi.

Codex wajib:

- menghubungkan frontend ke API nyata
- menggunakan Laravel Eloquent
- menggunakan MySQL
- **tidak menggunakan Prisma**
- menggunakan login username
- memastikan setiap sidebar menu mempunyai route dan halaman
- memastikan setiap form mempunyai API endpoint
- memastikan upload benar-benar tersimpan
- memastikan edit menampilkan data sebelumnya
- memastikan delete mempunyai confirmation
- memastikan empty state tersedia
- memastikan loading dan error state tersedia
- memastikan semua list mempunyai pagination jika data banyak
- memastikan website responsive
- memastikan tidak ada broken import
- memastikan tidak ada undefined component
- memastikan tidak ada missing route
- memastikan build Vite berhasil
- memastikan Laravel test berhasil
- memastikan migration berjalan
- memastikan storage link valid
- memastikan production `.env` aman

---

# 33. Acceptance Criteria

Proyek dianggap selesai hanya jika:

- Landing page lengkap.
- CMS admin lengkap.
- Slider dapat dibuat/edit/hapus.
- Konten Tentang dapat diedit.
- Rilisan dapat CRUD.
- Artis dapat CRUD.
- Gallery foto dapat CRUD.
- Gallery video dapat CRUD.
- Logo dan favicon dapat diganti.
- Social media dapat diedit.
- SMTP dapat diedit dan dites.
- Admin user dapat dikelola.
- Login menggunakan username.
- Semua data MySQL.
- Tidak memakai Prisma.
- Responsive.
- Light mode.
- Primary biru muda.
- Ornamen musik konsisten.
- Tidak ada console error.
- Tidak ada API 500.
- Tidak ada broken page.
- Build production sukses.
- Siap deploy Plesk.

---

# 34. Referensi Visual

Gunakan gambar rencana proyek yang dibuat bersama dokumen ini sebagai acuan visual utama.

Nama file konsep visual:

```txt
13-nadi-project-plan.png
```

Karakter desain:
- bright
- airy
- premium
- music-oriented
- corporate but modern
- blue-white dominant
- rounded cards
- subtle waveform / musical note overlay
