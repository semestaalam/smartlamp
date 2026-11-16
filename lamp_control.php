<?php
// SMARTLAMP - UNIVERSAL LAMP CONTROL
// Tujuan: 1 tombol NYALAKAN di SMARTLAMP bisa dipakai untuk SEGALA merk / jenis / kode lampu WiFi.
// Cara kerja: SMARTLAMP tidak ngomong langsung ke lampu. SMARTLAMP ngomong ke "Driver Vendor",
// lalu Driver Vendor yang ngomong ke server merk tersebut.
//
// Contoh:
// SMARTLAMP -> Driver Tuya -> Server Tuya -> Lampu BARDI / ACOME / Krisbow / BlitzWolf / Tuya
// SMARTLAMP -> Driver WiZ  -> Server WiZ  -> Lampu Philips WiZ
// SMARTLAMP -> Driver Tapo -> Server Tapo -> Lampu TP-Link Tapo
// SMARTLAMP -> Driver Yeelight -> Server Yeelight -> Lampu Xiaomi / Yeelight
//
// Syarat supaya lampu bisa dikontrol SMARTLAMP:
// 1. Lampu SUDAH dipairing dulu di aplikasi asli merk-nya (WiZ / BARDI / Tapo / dll) - 1x saja di awal
// 2. Admin memasukkan API Key / akun vendor di api/vendor_config.php
// 3. Di tabel devices, kolom vendor_device_id diisi dengan ID lampu di aplikasi vendor tersebut
// Kalau 3 ini sudah ada, merk apapun bisa dipakai, tinggal tambah driver baru tanpa ubah tampilan SMARTLAMP.

require __DIR__.'/config.php';
require __DIR__.'/vendor_config.php';
require __DIR__.'/drivers.php';

$uid = need_login();
$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$device_id = (int)($in['device_id'] ?? 0);
$action = $in['action'] ?? ''; // 'on' atau 'off'

// 1. Ambil lampu - HANYA yang milik pengguna yang login (multi-user tetap aman)
$st = $pdo->prepare("SELECT * FROM devices WHERE id=? AND user_id=? LIMIT 1");
$st->execute([$device_id, $uid]);
$dev = $st->fetch();
if (!$dev) out(['ok'=>false,'error'=>'Perangkat tidak ditemukan / bukan milik Anda']);

// 2. Simpan dulu status di MySQL (biar tampilan langsung berubah, walau API vendor lagi lambat)
$is_on = ($action === 'on') ? 1 : 0;
$pdo->prepare("UPDATE devices SET is_on=? WHERE id=? AND user_id=?")->execute([$is_on, $device_id, $uid]);

// 3. Kalau lampu belum punya vendor_device_id, berarti belum dihubungkan ke vendor.
//    Status tetap tersimpan, tapi lampu fisik belum ikut nyala. Kasih tahu pengguna dengan jujur.
if (empty($dev['vendor_device_id']) || empty($dev['vendor'])) {
  out(['ok'=>true,'is_on'=>$is_on,'physical'=>false,'message'=>'Status tersimpan di SMARTLAMP. Lampu fisik belum terhubung: isi Vendor + ID Lampu di aplikasi vendor di menu Admin -> Hubungkan Lampu Fisik.']);
}

// 4. Kirim perintah ke driver vendor yang sesuai merk
$result = universal_lamp_control($dev['vendor'], $dev['vendor_device_id'], $action);

if (!empty($result['ok'])) {
  out(['ok'=>true,'is_on'=>$is_on,'physical'=>true,'message'=>'Perintah '.$action.' terkirim ke lampu fisik via '.$dev['vendor']]);
} else {
  out(['ok'=>true,'is_on'=>$is_on,'physical'=>false,'message'=>'Status tersimpan, tapi perintah ke lampu fisik gagal: '.($result['error'] ?? 'cek API Key vendor di vendor_config.php')]);
}
