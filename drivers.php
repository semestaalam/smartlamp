<?php
// SMARTLAMP - DRIVER UNIVERSAL
// Satu fungsi: universal_lamp_control($vendor, $device_id, $action)
// Input: vendor = tuya / wiz / tapo / yeelight / meross / hannochs / sonoff
//        device_id = ID lampu di aplikasi vendor
//        action = 'on' / 'off'
// Output: ['ok'=>true] atau ['ok'=>false,'error'=>'...']
//
// PENTING - Jujur soal implementasi:
// File ini sekarang berisi kerangka + contoh pemanggilan Tuya yang benar.
// Untuk benar-benar menyalakan lampu fisik, Admin harus:
//  1. Isi API Key di vendor_config.php
//  2. Isi vendor_device_id di tabel devices (didapat dari aplikasi vendor / dashboard Tuya)
//  3. Aktifkan 1 driver di bawah ini (ganti return contoh jadi pemanggilan cURL ke API vendor).
// Tanpa API Key, SMARTLAMP tetap jalan sebagai pencatat status (mode database),
// tapi lampu fisik belum ikut nyala. Itu bukan bug, itu karena vendor tidak mengizinkan
// kontrol tanpa API Key.

function universal_lamp_control($vendor, $device_id, $action) {
  $vendor = strtolower(trim($vendor));
  $action = ($action === 'on') ? 'on' : 'off';

  switch ($vendor) {
    case 'tuya':
      return tuya_control($device_id, $action);
    case 'wiz':
      return wiz_control($device_id, $action);
    case 'tapo':
      return tapo_control($device_id, $action);
    case 'yeelight':
    case 'xiaomi':
      return yeelight_control($device_id, $action);
    case 'meross':
      return meross_control($device_id, $action);
    case 'hannochs':
      return hannochs_control($device_id, $action);
    case 'sonoff':
    case 'ewelink':
      return sonoff_control($device_id, $action);
    case 'jete':
      // JETE BL1 WiFi yang beredar umumnya pakai platform Tuya / JETE-SMART.
      // Kalau di dus ada logo Tuya, pakai driver Tuya.
      return tuya_control($device_id, $action);
    default:
      return ['ok'=>false,'error'=>'Vendor '.$vendor.' belum diaktifkan. Tambahkan driver di api/drivers.php'];
  }
}

// --- TUYA (cover paling banyak merk) ---
function tuya_control($device_id, $action) {
  $cfg = include __DIR__.'/vendor_config.php';
  $tuya = $cfg['tuya'] ?? [];
  if (empty($tuya['enabled']) || empty($tuya['access_id']) || $tuya['access_id'] === 'ISI_ACCESS_ID_TUYA_DI_SINI') {
    return ['ok'=>false,'error'=>'API Key Tuya belum diisi di api/vendor_config.php'];
  }
  // Di sini nanti: 1) minta token ke Tuya Cloud, 2) POST ke
  // https://openapi.tuyaus.com/v1.0/devices/{device_id}/commands  dengan perintah {"code":"switch_led","value":true/false}
  // Contoh kerangka cURL sudah disiapkan, tinggal isi token:
  // $ch = curl_init(...); curl_setopt(...); ...
  return ['ok'=>false,'error'=>'Driver Tuya sudah disiapkan, tinggal isi token + aktifkan cURL ke Tuya Cloud (lihat komentar di file ini).'];
}

function wiz_control($device_id, $action) {
  $cfg = include __DIR__.'/vendor_config.php';
  if (empty($cfg['wiz']['enabled'])) return ['ok'=>false,'error'=>'API Key WiZ belum diisi'];
  return ['ok'=>false,'error'=>'Driver WiZ siap, tinggal isi pemanggilan WiZ Cloud API.'];
}
function tapo_control($device_id, $action) {
  $cfg = include __DIR__.'/vendor_config.php';
  if (empty($cfg['tapo']['enabled'])) return ['ok'=>false,'error'=>'Akun Tapo belum diisi'];
  return ['ok'=>false,'error'=>'Driver Tapo siap, tinggal isi pemanggilan Tapo Cloud API.'];
}
function yeelight_control($device_id, $action) {
  return ['ok'=>false,'error'=>'Driver Yeelight siap, aktifkan LAN Control di aplikasi Yeelight lalu isi token.'];
}
function meross_control($device_id, $action) {
  return ['ok'=>false,'error'=>'Driver Meross siap, isi akun Meross di vendor_config.php.'];
}
function hannochs_control($device_id, $action) {
  // Kalau Hannochs pakai Tuya, alihkan ke Tuya
  return tuya_control($device_id, $action);
}
function sonoff_control($device_id, $action) {
  return ['ok'=>false,'error'=>'Driver SONOFF / eWeLink siap, isi akun eWeLink di vendor_config.php.'];
}

// PETA MERK -> VENDOR (dipakai dashboard PILIH PERANGKAT, biar otomatis terisi)
function brand_to_vendor($brand) {
  $map = [
    'Philips WiZ' => 'wiz',
    'BARDI' => 'tuya',
    'Xiaomi / Yeelight' => 'yeelight',
    'Hannochs' => 'tuya',
    'JETE' => 'tuya',
    'TP-Link Tapo' => 'tapo',
    'ACOME' => 'tuya',
    'Krisbow Sync' => 'tuya',
    'SONOFF' => 'sonoff',
    'Meross' => 'meross',
    'BlitzWolf' => 'tuya',
    'Tuya Generik' => 'tuya',
  ];
  return $map[$brand] ?? 'tuya';
}
