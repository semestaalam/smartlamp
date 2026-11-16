<?php
require __DIR__.'/config.php';
$act = $_GET['act'] ?? '';
$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($act === 'register') {
  $nama = trim($in['nama'] ?? ''); $email = trim($in['email'] ?? ''); $pass = $in['password'] ?? '';
  if (!$nama || !$email || !$pass) out(['ok'=>false,'error'=>'Lengkapi nama, email, dan kata sandi']);
  $hash = password_hash($pass, PASSWORD_DEFAULT);
  try {
    $st = $pdo->prepare("INSERT INTO users (nama,email,password_hash,tanggal_daftar,masa_aktif_sampai,jatuh_tempo) VALUES (?,?,?,CURDATE(),DATE_ADD(CURDATE(),INTERVAL 14 DAY),DATE_ADD(CURDATE(),INTERVAL 14 DAY))");
    $st->execute([$nama,$email,$hash]);
    out(['ok'=>true,'message'=>'Akun dibuat. Silakan masuk.']);
  } catch (Exception $e) { out(['ok'=>false,'error'=>'Email sudah dipakai']); }
}

if ($act === 'login') {
  $email = trim($in['email'] ?? ''); $pass = $in['password'] ?? '';
  $st = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1"); $st->execute([$email]);
  $u = $st->fetch();
  if (!$u || !password_verify($pass, $u['password_hash'])) out(['ok'=>false,'error'=>'Email / kata sandi salah']);
  if (!$u['is_active']) out(['ok'=>false,'error'=>'Akun dinonaktifkan Admin. Hubungi Admin.']);
  $_SESSION['user_id'] = (int)$u['id']; $_SESSION['role'] = $u['role']; $_SESSION['nama'] = $u['nama'];
  out(['ok'=>true,'user'=>['id'=>$u['id'],'nama'=>$u['nama'],'email'=>$u['email'],'role'=>$u['role']]]);
}

if ($act === 'logout') { session_destroy(); out(['ok'=>true]); }

if ($act === 'me') {
  if (empty($_SESSION['user_id'])) out(['ok'=>false]);
  out(['ok'=>true,'user'=>['id'=>$_SESSION['user_id'],'nama'=>$_SESSION['nama'],'role'=>$_SESSION['role']]]);
}
out(['ok'=>false,'error'=>'Aksi tidak dikenal']);
