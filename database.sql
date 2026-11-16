-- SMARTLAMP - Database MySQL (DOM CLOUD, bayar Rupiah, tanpa Supabase)
-- Cara pakai: buat database baru di cPanel DOM CLOUD, lalu Import file ini lewat phpMyAdmin.
CREATE DATABASE IF NOT EXISTS smartlamp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE smartlamp;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  tanggal_daftar DATE NOT NULL,
  masa_aktif_sampai DATE NULL,
  paket VARCHAR(50) DEFAULT 'Free Trial 14 Hari',
  sudah_bayar TINYINT(1) DEFAULT 0,
  tanggal_bayar DATE NULL,
  jatuh_tempo DATE NULL,
  is_active TINYINT(1) DEFAULT 1,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PERANGKAT / LAMPU: KUNCI multi-user ada di sini -> user_id
-- API selalu filter WHERE user_id = user yang login. Andi TIDAK BISA lihat lampu Budi.
CREATE TABLE IF NOT EXISTS devices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  ruangan VARCHAR(100) NOT NULL,
  ruangan_index INT NOT NULL DEFAULT 0,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(150) NOT NULL,
  kode VARCHAR(150) DEFAULT '',
  fitting VARCHAR(10) DEFAULT 'E27',
  watt VARCHAR(30) DEFAULT '',
  warna VARCHAR(60) DEFAULT '',
  aplikasi VARCHAR(80) DEFAULT '',
  is_on TINYINT(1) DEFAULT 0,
  is_connected TINYINT(1) DEFAULT 0,
  vendor_device_id VARCHAR(150) DEFAULT NULL,
  vendor VARCHAR(50) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_devices_user (user_id)
);

CREATE TABLE IF NOT EXISTS schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_id INT NULL,
  ruangan VARCHAR(100) NOT NULL,
  aksi ENUM('on','off') NOT NULL,
  jam TIME NOT NULL,
  hari VARCHAR(20) DEFAULT 'Setiap hari',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
