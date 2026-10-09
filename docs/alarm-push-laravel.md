# Alarm Push melalui Laravel

Tidak perlu deploy Firebase Cloud Functions. FCM HTTP v1 dikirim oleh Laravel.

- Kredensial privat: storage/app/firebase/service-account.json (tidak ikut Git).
- Di server hosting, unggah kredensial ke lokasi privat yang sama.
- Pastikan Firebase Cloud Messaging API V1 aktif.
- Service account harus memiliki izin Firebase Cloud Messaging dan Realtime Database.
- Server perlu PHP OpenSSL serta akses HTTPS ke Google OAuth, FCM, dan database Firebase.
- Jalankan php artisan config:clear setelah memasang konfigurasi server.
- Endpoint POST /api/alarm/store menggunakan Sanctum; hanya role 1 boleh mengirim.
- Login ulang aplikasi jika sesi lama belum memiliki auth_token.
- Jangan deploy Cloud Function stellingAlarmPush bersamaan: notifikasi bisa ganda.
- Jangan mengubah Firebase Rules menjadi publik; batasi penulisan alarm ke petugas.

Saat alarm diaktifkan, Laravel memperbarui /alarm/demo lalu mengirim FCM ke
stelling_alarm. Jika FCM gagal setelah database berhasil, aplikasi tetap bisa
menghentikan alarm dan menampilkan peringatan kegagalan push.

File service account telah dipasang lokal. Pengiriman nyata belum diuji:
pengujian hanya memakai mock agar tidak membunyikan alarm semua perangkat.
Gunakan perangkat uji dan koordinasikan sebelum mengaktifkan alarm.
