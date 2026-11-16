<?php
// SMARTLAMP - Koneksi MySQL DOM CLOUD
// CARA ISI: lihat di cPanel DOM CLOUD -> MySQL Databases.
header('Content-Type: application/json; charset=utf-8');
session_start();

$DB_HOST = 'localhost';
$DB_NAME = 'smartlamp';
$DB_USER = 'root';
$DB_PASS = '';

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'Gagal konek database. Cek api/config.php']);
  exit;
}
function out($arr){ echo json_encode($arr); exit; }
function need_login(){
  if (empty($_SESSION['user_id'])) { http_response_code(401); out(['ok'=>false,'error'=>'Belum login']); }
  return $_SESSION['user_id'];
}
function need_admin(){
  if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403); out(['ok'=>false,'error'=>'Khusus Admin']);
  }
  return $_SESSION['user_id'];
}
