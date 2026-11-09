-- ==============================================================
-- DATABASE: SISTEM INFORMASI DIGITAL LIBRARY SKRIPSI BERBASIS WEB
-- Studi Kasus: Perpustakaan FMIPA Universitas Pakuan (UNPAK)
-- Penulis: Faris Raja Ardana (NPM: 065120125)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `digilib_unpak` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `digilib_unpak`;

-- -------------------------------------------------------------
-- Tabel Program Studi
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `prodi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_prodi` VARCHAR(100) NOT NULL,
  `fakultas` VARCHAR(100) NOT NULL DEFAULT 'Matematika dan Ilmu Pengetahuan Alam',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `prodi` (`id`, `nama_prodi`, `fakultas`) VALUES
(1, 'Ilmu Komputer', 'FMIPA Universitas Pakuan'),
(2, 'Matematika', 'FMIPA Universitas Pakuan'),
(3, 'Kimia', 'FMIPA Universitas Pakuan'),
(4, 'Biologi', 'FMIPA Universitas Pakuan'),
(5, 'Farmasi', 'FMIPA Universitas Pakuan')
ON DUPLICATE KEY UPDATE `nama_prodi`=VALUES(`nama_prodi`);

-- -------------------------------------------------------------
-- Tabel 1. Operator (Admin Perpustakaan)
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `operator` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'default.png',
  `akses` VARCHAR(100) NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Tabel 2. Mahasiswa
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nim` VARCHAR(20) NOT NULL UNIQUE,
  `nama` VARCHAR(255) NOT NULL,
  `jenis_kelamin` VARCHAR(30) NOT NULL,
  `tgl_lahir` DATE NOT NULL,
  `alamat` TEXT NOT NULL,
  `agama` VARCHAR(50) NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `foto` VARCHAR(255) NOT NULL DEFAULT 'default.png',
  `akses` VARCHAR(100) NOT NULL DEFAULT 'mahasiswa',
  `id_prodi` INT(11) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Tabel 3. Kaprodi (Kepala Program Studi / Reviewer)
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kaprodi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nidn` VARCHAR(20) NOT NULL UNIQUE,
  `nama_prodi` VARCHAR(100) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `foto` VARCHAR(255) NOT NULL DEFAULT 'default.png',
  `akses` VARCHAR(50) NOT NULL DEFAULT 'kaprodi',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- Tabel 4. Skripsi
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `skripsi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nim` VARCHAR(20) NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `tahun` INT(11) NOT NULL,
  `dokumen` VARCHAR(255) NOT NULL,
  `format` VARCHAR(50) NOT NULL DEFAULT 'PDF Format',
  `penjurusan` VARCHAR(100) NOT NULL,
  `dosbing` VARCHAR(100) NOT NULL,
  `dosbing2` VARCHAR(100) NOT NULL,
  `id_mahasiswa` INT(11) NOT NULL,
  `id_prodi` INT(11) NOT NULL DEFAULT 1,
  `nilai` INT(11) NOT NULL DEFAULT 0, -- 0: Menunggu Review, 1: Disetujui/Valid, 2: Perlu Revisi
  `catatan_review` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_skripsi_mahasiswa` (`id_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
-- SEED DATA DEFAULT (Password: password123)
-- -------------------------------------------------------------

-- Akun Operator / Admin
INSERT INTO `operator` (`id`, `username`, `password`, `nama`, `image`, `akses`) VALUES
(1, 'admin', '$2y$10$5yD20CDr.mJrHyKUZFwKfu5IOqc8WrF0..V8dEOBUym.VCAzamfoW', 'Staf Perpustakaan FMIPA UNPAK', 'default.png', 'admin')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- Akun Kaprodi (Ibu Arie Qur\'ania, M.Kom.)
INSERT INTO `kaprodi` (`id`, `nidn`, `nama_prodi`, `nama`, `username`, `password`, `foto`, `akses`) VALUES
(1, '0412088501', 'Ilmu Komputer', 'Arie Qur\'ania, M.Kom.', 'kaprodi', '$2y$10$5yD20CDr.mJrHyKUZFwKfu5IOqc8WrF0..V8dEOBUym.VCAzamfoW', 'default.png', 'kaprodi')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- Akun Mahasiswa (Faris Raja Ardana)
INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jenis_kelamin`, `tgl_lahir`, `alamat`, `agama`, `username`, `password`, `foto`, `akses`, `id_prodi`) VALUES
(1, '065120125', 'Faris Raja Ardana', 'Laki-laki', '2002-05-15', 'Jl. Pakuan No. 1, Tegallega, Bogor', 'Islam', 'faris', '$2y$10$5yD20CDr.mJrHyKUZFwKfu5IOqc8WrF0..V8dEOBUym.VCAzamfoW', 'default.png', 'mahasiswa', 1),
(2, '065120110', 'Budi Santoso', 'Laki-laki', '2002-03-20', 'Jl. Pajajaran, Bogor', 'Islam', 'budi', '$2y$10$5yD20CDr.mJrHyKUZFwKfu5IOqc8WrF0..V8dEOBUym.VCAzamfoW', 'default.png', 'mahasiswa', 1),
(3, '065120115', 'Siti Rahmawati', 'Perempuan', '2002-08-11', 'Jl. Sukasari, Bogor', 'Islam', 'siti', '$2y$10$5yD20CDr.mJrHyKUZFwKfu5IOqc8WrF0..V8dEOBUym.VCAzamfoW', 'default.png', 'mahasiswa', 1)
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- Data Skripsi Awal
INSERT INTO `skripsi` (`id`, `nim`, `judul`, `tahun`, `dokumen`, `format`, `penjurusan`, `dosbing`, `dosbing2`, `id_mahasiswa`, `id_prodi`, `nilai`, `catatan_review`) VALUES
(1, '065120125', 'Sistem Informasi Digital Library Skripsi Berbasis Website (Studi Kasus: Perpustakaan MIPA Universitas Pakuan)', 2023, 'sample_skripsi_faris.pdf', 'PDF Format', 'Rekayasa Perangkat Lunak', 'Teguh Puja Negara, M.Si S.Si.', 'Endang Suhendar, M.Kom.', 1, 1, 1, 'Disetujui. Karya ilmiah lengkap dan valid.'),
(2, '065120110', 'Penerapan Algoritma K-Means untuk Pengelompokan Data Nilai Mahasiswa', 2023, 'sample_skripsi_budi.pdf', 'PDF Format', 'Data Mining & AI', 'Teguh Puja Negara, M.Si S.Si.', 'Dinar Munggaran A, M.Kom.', 2, 1, 1, 'Disetujui untuk diarsipkan di e-library.'),
(3, '065120115', 'Sistem Deteksi Objek Real-time Menggunakan Algoritma YOLO Berbasis Web', 2024, 'sample_skripsi_siti.pdf', 'PDF Format', 'Computer Vision', 'Arie Qur\'ania, M.Kom.', 'Endang Suhendar, M.Kom.', 3, 1, 0, 'Sedang dalam proses review oleh Kaprodi.')
ON DUPLICATE KEY UPDATE `judul`=VALUES(`judul`);
