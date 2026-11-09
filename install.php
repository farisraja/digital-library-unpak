<?php
// install.php - Database Initializer & Migration Tool
// E-SKRIPSI FMIPA Universitas Pakuan

require_once __DIR__ . '/config/database.php';

$message = '';
$status = 'info';

if (isset($_GET['run']) && $_GET['run'] === 'install') {
    try {
        $rootDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
        $pdo = new PDO($rootDsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $pdo->exec("USE `" . DB_NAME . "`;");
        
        $sql = file_get_contents(__DIR__ . '/database.sql');
        $pdo->exec($sql);
        
        $status = 'success';
        $message = "Database `" . DB_NAME . "` berhasil diinstal dan diisi dengan data awal (seed data) Universitas Pakuan!";
    } catch (Exception $e) {
        $status = 'danger';
        $message = "Gagal menginstal database: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - E-SKRIPSI UNPAK</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body { background: #f5f7fb; font-family: 'Segoe UI', Tahoma, sans-serif; padding: 50px 20px; }
        .setup-card { max-width: 620px; margin: 0 auto; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: none; }
    </style>
</head>
<body>
    <div class="card setup-card">
        <div class="card-header bg-white text-center py-4 border-bottom">
            <img src="assets/img/logo-unpak.svg" alt="Logo UNPAK" width="60" class="mb-2">
            <h4 class="fw-bold mb-0 text-dark">Setup & Migrasi Database</h4>
            <small class="text-muted">Sistem Informasi Digital Library Skripsi FMIPA UNPAK</small>
        </div>
        <div class="card-body p-4 p-md-5">
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $status ?> mb-4">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <p>Alat ini akan secara otomatis membuat database <code><?= DB_NAME ?></code> dan seluruh tabel yang didefinisikan dalam skripsi Anda:</p>
            <ul>
                <li><strong>operator</strong> (Admin / Staf Perpustakaan FMIPA)</li>
                <li><strong>mahasiswa</strong> (Data akun & profil mahasiswa S1)</li>
                <li><strong>kaprodi</strong> (Kepala Program Studi / Reviewer)</li>
                <li><strong>skripsi</strong> (Koleksi naskah karya ilmiah & status review)</li>
                <li><strong>prodi</strong> (Daftar Program Studi FMIPA UNPAK)</li>
            </ul>

            <div class="p-3 bg-light rounded border mb-4">
                <small class="text-muted d-block fw-bold mb-1">KONFIGURASI KONEKSI:</small>
                <div class="small">
                    Host: <code><?= DB_HOST ?>:<?= DB_PORT ?></code><br>
                    User: <code><?= DB_USER ?></code> | Database: <code><?= DB_NAME ?></code>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="install.php?run=install" class="btn btn-primary py-2 fw-semibold">
                    Jalankan Migrasi & Import Seed Data
                </a>
                <a href="index.php" class="btn btn-outline-secondary py-2">
                    Buka Aplikasi E-SKRIPSI
                </a>
            </div>
        </div>
    </div>
</body>
</html>
