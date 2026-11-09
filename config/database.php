<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'digilib_unpak');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB(): ?PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        // Connect directly to target database
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // If database doesn't exist, try creating it and importing database.sql
        try {
            $rootDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $rootPdo = new PDO($rootDsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            $sqlFile = dirname(__DIR__) . '/database.sql';
            if (file_exists($sqlFile)) {
                $sqlContent = file_get_contents($sqlFile);
                $rootPdo->exec($sqlContent);
            }
            
            // Re-connect
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $ex) {
            // Provide a graceful fallback screen if MySQL is offline
            renderDbErrorScreen($ex->getMessage());
            exit;
        }
    }
}

function renderDbErrorScreen($errorMessage) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error - E-SKRIPSI UNPAK</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .error-card { max-width: 650px; margin: 80px auto; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; }
        .header-err { background: #5e35b1; color: white; padding: 25px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card error-card">
            <div class="header-err text-center">
                <h4 class="mb-1">E-SKRIPSI FMIPA UNIVERSITAS PAKUAN</h4>
                <p class="mb-0 opacity-75">Koneksi Database MySQL Diperlukan</p>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-warning">
                    <strong>Pemberitahuan Sistem:</strong> Server database MySQL lokal belum aktif atau tidak dapat dijangkau.
                </div>
                <p><strong>Langkah penyelesaian cepat:</strong></p>
                <ol class="mb-4">
                    <li>Buka aplikasi <strong>Laragon</strong> atau <strong>XAMPP</strong> di komputer Anda.</li>
                    <li>Klik tombol <strong>Start All</strong> atau pastikan service <strong>MySQL</strong> dalam status <em>Running</em>.</li>
                    <li>Refresh halaman ini setelah MySQL aktif.</li>
                </ol>
                <div class="p-3 bg-light rounded text-muted small">
                    <strong>Pesan error teknis:</strong><br>
                    <code>{$errorMessage}</code>
                </div>
                <div class="mt-4 text-center">
                    <button onclick="window.location.reload();" class="btn btn-primary px-4">Muat Ulang Halaman</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
}
