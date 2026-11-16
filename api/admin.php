<?php
require __DIR__.'/config.php';
need_admin();
$method = $_SERVER['REQUEST_METHOD'];
$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$act = $_GET['act'] ?? '';

if ($act === 'users' && $method === 'GET') {
  $rows = $pdo->query("SELECT id,nama,email,tanggal_daftar,masa_aktif_sampai,paket,sudah_bayar,tanggal_bayar,jatuh_tempo,is_active,role FROM users ORDER BY id ASC")->fetchAll();
  out(['ok'=>true,'users'=>$rows]);
}
if ($act === 'toggle' && $method === 'POST') {
  $st = $pdo->prepare("UPDATE users SET is_active=? WHERE id=?"); $st->execute([(int)$in['is_active'], (int)$in['id']]);
  out(['ok'=>true]);
}
if ($act === 'extend' && $method === 'POST') {
  $st = $pdo->prepare("UPDATE users SET jatuh_tempo=DATE_ADD(COALESCE(jatuh_tempo,CURDATE()),INTERVAL 30 DAY), masa_aktif_sampai=DATE_ADD(COALESCE(masa_aktif_sampai,CURDATE()),INTERVAL 30 DAY), sudah_bayar=1 WHERE id=?");
  $st->execute([(int)$in['id']]); out(['ok'=>true]);
}
if ($act === 'delete' && $method === 'POST') {
  if ((int)$in['id'] === (int)$_SESSION['user_id']) out(['ok'=>false,'error'=>'Tidak bisa menghapus akun Admin sendiri']);
  $st = $pdo->prepare("DELETE FROM users WHERE id=?"); $st->execute([(int)$in['id']]);
  out(['ok'=>true]);
}
out(['ok'=>false,'error'=>'Aksi admin tidak dikenal']);
