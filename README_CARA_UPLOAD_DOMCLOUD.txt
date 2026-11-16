SMARTLAMP - PAKET SIAP UPLOAD DOM CLOUD (PHP + MySQL, bayar Rupiah)
====================================================================
ISI PAKET:
- index.html        : tampilan SMARTLAMP V4 (12 merk, kode otomatis)
- database.sql      : tabel users + devices + schedules (import via phpMyAdmin)
- api/config.php    : ISI DULU host/nama database/user/password MySQL dari cPanel DOM CLOUD
- api/auth.php      : daftar / masuk / keluar (login beneran, bukan demo)
- api/devices.php   : ambil / tambah / nyala-mati lampu - SELALU difilter user_id
- api/admin.php     : lihat pengguna, ON/OFF, perpanjang +30 hari, HAPUS pengguna

CARA UPLOAD KE DOM CLOUD:
1. Login cPanel DOM CLOUD -> MySQL Databases -> Buat database + user baru -> catat nama & passwordnya.
2. phpMyAdmin -> pilih database itu -> Import -> pilih database.sql -> Go.
3. File Manager / FTP -> upload SEMUA file ini ke public_html/ (atau public_html/smartlamp/).
4. Buka api/config.php, isi DB_NAME, DB_USER, DB_PASS sesuai langkah 1. Simpan.
5. Buka web-nya. Daftar akun baru. Untuk jadi Admin: daftar akun baru lewat web, lalu di phpMyAdmin ubah role-nya jadi 'admin'.

APA YANG SUDAH BERES:
[x] Multi-user: tiap pengguna hanya lihat lampu miliknya sendiri (filter user_id)
[x] Admin: lihat semua pengguna, ON/OFF aktivasi, perpanjang, HAPUS pengguna
[x] Data tidak hilang kalau refresh / ganti HP (tersimpan di MySQL)
[x] Login beneran (password di-hash, bukan demo)

APA YANG BELUM (JUJUR):
[ ] Kontrol lampu FISIK (pencet NYALAKAN -> lampu beneran nyala) BELUM.
    Lampu WiFi (WiZ/BARDI/Tapo/Tuya) tidak bisa dikontrol langsung dari web tanpa API vendor.
    Butuh Tahap 2: sambungkan vendor_device_id di tabel devices ke salah satu:
      a) Tuya Cloud API (paling umum, karena BARDI/ACOME/Krisbow/Tuya pakai Tuya)
      b) WiZ Cloud API (untuk Philips WiZ)
      c) Tapo Cloud API (untuk TP-Link Tapo)
      d) Atau gateway lokal (ESP32 / Home Assistant) di rumah pengguna.
    Di api/devices.php sudah disiapkan tempatnya.

JAWABAN UNTUK PERTANYAAN LO:
- Koneksi WiFi/lampu fisik? -> BELUM, butuh Tahap 2 (API vendor).
- Dipakai rame-rame tanpa saling ganggu? -> SUDAH, lewat PHP+MySQL ini (filter user_id).
