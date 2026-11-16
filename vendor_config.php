<?php
// SMARTLAMP - KONFIGURASI VENDOR (diisi Admin 1x, bukan oleh pengguna)
// Setiap merk butuh 1 akun API. Ini daftar yang dipakai SMARTLAMP untuk cover 12 merk di dashboard:
// - TUYA: cover BARDI, ACOME, Krisbow Sync, SONOFF (sebagian), BlitzWolf, Tuya Generik, JETE (yang pakai Tuya)
//   Daftar di: https://iot.tuya.com -> buat Cloud Project -> ambil Access ID + Access Secret
// - WIZ: cover Philips WiZ
//   Daftar di: https://api.wizconnected.com (WiZ Developer)
// - TAPO: cover TP-Link Tapo
//   Pakai akun Tapo yang sama dengan aplikasi Tapo
// - YEELIGHT: cover Xiaomi / Yeelight
//   Aktifkan LAN Control / Mi Home Developer di aplikasi Yeelight
// - HANNOCHS: sebagian pakai Tuya, sebagian pakai aplikasi Hannochs sendiri (cek di dus: kalau ada logo Tuya, pakai driver Tuya)
// - MEROSS: pakai akun Meross / Apple Home
// Kalau ada merk baru nanti, tinggal tambah 1 driver baru di drivers.php, tanpa ubah tampilan SMARTLAMP.

return [
  'tuya' => [
    'enabled' => false, // ubah jadi true kalau sudah isi key di bawah
    'access_id' => 'ISI_ACCESS_ID_TUYA_DI_SINI',
    'access_secret' => 'ISI_ACCESS_SECRET_TUYA_DI_SINI',
    'region' => 'ID', // Indonesia
  ],
  'wiz' => [
    'enabled' => false,
    'api_key' => 'ISI_API_KEY_WIZ_DI_SINI',
  ],
  'tapo' => [
    'enabled' => false,
    'email' => 'ISI_EMAIL_TAPO_DI_SINI',
    'password' => 'ISI_PASSWORD_TAPO_DI_SINI',
  ],
  'yeelight' => [
    'enabled' => false,
    'token' => 'ISI_TOKEN_YEELIGHT_DI_SINI',
  ],
  'meross' => [
    'enabled' => false,
    'email' => 'ISI_EMAIL_MEROSS_DI_SINI',
    'password' => 'ISI_PASSWORD_MEROSS_DI_SINI',
  ],
  'hannochs' => [
    'enabled' => false,
    'note' => 'Kalau dus Hannochs ada logo Tuya, pakai driver tuya. Kalau tidak, pakai driver hannochs ini.',
  ],
  'sonoff' => [
    'enabled' => false,
    'note' => 'SONOFF eWeLink. Sebagian model SONOFF juga bisa lewat Tuya / Matter.',
  ],
];
