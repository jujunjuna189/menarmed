# Upgrade PHP 8.4 / Laravel 12

Proyek sekarang menargetkan PHP 8.4.x (`~8.4.0`) dan Laravel 12. Composer
menyelesaikan dependensi terhadap PHP 8.4.0 agar lock file tidak memerlukan
versi PHP yang lebih tinggi daripada target. Gunakan patch PHP 8.4 terbaru
yang tersedia di server. Bootstrap mengikuti struktur Laravel 12; Blade,
Laravel Mix, dan data lama dipertahankan.

## Struktur Laravel 12

- `bootstrap/app.php`: routing web/API/console, health endpoint `/up`, alias
  middleware `role`, redirect autentikasi, throttle API, proxy headers, dan
  konfigurasi exception. API tetap memakai prefix `/api` dan limit 60 request
  per menit per user/IP.
- `bootstrap/providers.php`: daftar provider aplikasi. Provider paket tetap
  didaftarkan lewat Composer package discovery.
- `app/Providers/AppServiceProvider.php`: definisi rate limiter `api`.
- `routes/console.php`: perintah console dan tempat menambahkan jadwal lewat
  facade `Schedule`. Sebelumnya tidak ada jadwal aktif.
- `public/index.php` dan `artisan`: entry point memakai `handleRequest` dan
  `handleCommand`. Tes memakai bootstrap bawaan Laravel 12.
- Kernel HTTP/console, exception handler, provider kosong/lama, dan middleware
  pembungkus bawaan dihapus. `RoleMiddleware` tetap ada karena khusus aplikasi.
  Listener verifikasi email disediakan provider event bawaan framework.
- Konfigurasi aplikasi dan alias facade lama yang masih dipakai dipertahankan;
  provider tidak lagi didaftarkan melalui `config/app.php`.

Perubahan utama:

- Laravel 8 → 12, Sanctum 2 → 4, Laravel UI 3 → 4.
- DomPDF 2 → 3, Spatie Query Builder 4 → 6, PHPUnit 9 → 11.
- Middleware CORS menggunakan implementasi bawaan Laravel; Fruitcake CORS
  dan Facade Ignition lama dihapus.
- Sanctum menggunakan konfigurasi middleware baru dan migration tambahan
  kolom nullable `personal_access_tokens.expires_at`. Token lama tetap ada;
  nilai null tidak menambahkan batas kedaluwarsa.
- Konfigurasi PostgreSQL menggunakan `search_path`.
- Handler dan session path cPanel di `.htaccess`, `.user.ini`, dan `php.ini`
  diarahkan dari `ea-php80` ke `ea-php84`.
- Tes menggunakan SQLite in-memory dan kunci aplikasi khusus testing.
  Tes impor/ekspor yang sebelumnya belum menyiapkan database kini menjalankan
  migration melalui `RefreshDatabase`. Ekspektasi tes root redirect, nama
  berkas ekspor, dan indeks kolom rekap diselaraskan dengan fitur yang ada.

## Pengembangan dan pengujian

Gunakan PHP 8.4, Composer 2, dan extension PDO untuk database aplikasi,
mbstring, openssl, fileinfo, curl, dom, SimpleXML, xml, xmlreader, xmlwriter,
gd, zip, ctype, iconv, tokenizer, session, dan filter. SQLite diperlukan untuk tes.

```sh
php -v
composer install
composer validate --strict
composer check-platform-reqs
php vendor/phpunit/phpunit/phpunit
php artisan route:list --except-vendor
php artisan view:cache
php artisan view:clear
```

Jangan menjalankan tes dengan config cache produksi. Tes harus menggunakan
SQLite `:memory:`, bukan database `.env`. Tes kompatibilitas juga memverifikasi
login bcrypt, token Sanctum tersimpan, dan keluaran XLSX/PDF dari library asli.

## Deployment staging dan produksi

1. Cadangkan database, kode rilis lama beserta `composer.lock`, `.env`, dan
   unggahan. Gunakan salinan database dan konfigurasi layanan staging untuk
   pemeriksaan awal; jangan menghubungkan staging ke push/email produksi.
2. Di cPanel MultiPHP Manager pilih PHP 8.4 untuk domain. Pastikan paket
   `ea-php84` tersedia, extension terpasang, dan direktori session
   `/var/cpanel/php/sessions/ea-php84` tersedia serta dapat ditulis.
   Periksa ulang MultiPHP INI Editor karena cPanel dapat menulis ulang file ini.
   Pada server non-cPanel, gunakan konfigurasi PHP-FPM milik server dan
   sesuaikan handler/path cPanel sebelum deployment.
3. Pastikan `php -v` pada CLI, PHP web/FPM, cron scheduler, dan worker queue
   semuanya menggunakan PHP 8.4. Tetap gunakan `APP_KEY` lama dan `APP_DEBUG=false`.
4. Pasang dependensi dari lock file di direktori rilis baru:

   ```sh
   composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
   composer check-platform-reqs --no-dev
   ```

5. Dalam maintenance window, setelah memastikan `.env` menunjuk database yang
   tepat, bersihkan config cache lama dan tinjau migration tertunda:

   ```sh
   php artisan down
   php artisan config:clear
   php artisan migrate:status
   php artisan migrate --force
   php artisan config:cache
   php artisan view:cache
   php artisan queue:restart
   php artisan up
   ```

   Tinjau seluruh migration tertunda sebelum `migrate --force`; perintah ini
   menjalankan semuanya, bukan hanya migration upgrade. Atur izin tulis
   `storage` dan `bootstrap/cache`, dan muat ulang PHP-FPM/OPcache sesuai server.
6. Periksa login/logout web, token API lama dan baru, role admin/personel,
   halaman layanan publik, absensi, impor/ekspor Excel, PDF beserta gambar,
   upload, scheduler, queue, dan integrasi email/push staging. DomPDF 3
   menonaktifkan remote assets secara default; periksa template PDF jika
   kemudian menggunakan gambar eksternal. Jangan mengaktifkan remote assets
   secara global tanpa meninjau sumbernya.

## Rollback

Kembalikan rilis kode dan vendor dari lock file lama beserta runtime PHP yang
sebelumnya digunakan, kemudian bangun ulang config/view cache dan restart
worker/FPM. Kolom `expires_at` nullable boleh tetap ada saat rollback kode;
tidak perlu menghapus tabel atau token. Bila perlu rollback migration, tinjau
batch migration terlebih dahulu agar migration lain tidak ikut dibatalkan.
Pulihkan backup database hanya bila memang ada perubahan data yang perlu
dipulihkan dan sudah memperhitungkan data baru sejak deployment.

Panduan yang ditinjau: [Laravel 9](https://laravel.com/docs/9.x/upgrade),
[Laravel 10](https://laravel.com/docs/10.x/upgrade),
[Laravel 11](https://laravel.com/docs/11.x/upgrade),
[Laravel 12](https://laravel.com/docs/12.x/upgrade), dan
[PHP 8.4](https://www.php.net/manual/en/migration84.php).
