<?php
require __DIR__.'/config.php';
$uid = need_login();
$method = $_SERVER['REQUEST_METHOD'];
$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($method === 'GET') {
  $st = $pdo->prepare("SELECT * FROM devices WHERE user_id=? ORDER BY ruangan_index ASC, id ASC");
  $st->execute([$uid]);
  out(['ok'=>true,'devices'=>$st->fetchAll()]);
}

if ($method === 'POST') {
  $st = $pdo->prepare("INSERT INTO devices (user_id,ruangan,ruangan_index,brand,model,kode,fitting,watt,warna,aplikasi,is_connected) VALUES (?,?,?,?,?,?,?,?,?,?,1)");
  $st->execute([$uid, $in['ruangan']??'', (int)($in['ruangan_index']??0), $in['brand']??'', $in['model']??'', $in['kode']??'', $in['fitting']??'E27', $in['watt']??'', $in['warna']??'', $in['aplikasi']??'']);
  out(['ok'=>true,'id'=>$pdo->lastInsertId()]);
}

if ($method === 'PUT') {
  $on = !empty($in['is_on']) ? 1 : 0;
  $st = $pdo->prepare("UPDATE devices SET is_on=? WHERE id=? AND user_id=?");
  $st->execute([$on, (int)$in['id'], $uid]);
  if ($st->rowCount()===0) out(['ok'=>false,'error'=>'Perangkat tidak ditemukan / bukan milik Anda']);
  out(['ok'=>true,'note'=>'Status tersimpan di database. Kontrol lampu fisik butuh integrasi API vendor (lihat README).']);
}

if ($method === 'DELETE') {
  $st = $pdo->prepare("DELETE FROM devices WHERE id=? AND user_id=?");
  $st->execute([(int)$in['id'], $uid]);
  out(['ok'=>true]);
}
out(['ok'=>false,'error'=>'Method tidak didukung']);
