<?php
// views/layouts/header.php
require_once dirname(__DIR__, 2) . '/app/Helpers/Auth.php';
$isLoggedIn = Auth::check();
$userRole = Auth::role();
$userName = Auth::name();
$pageTitle = $pageTitle ?? 'Sistem Informasi Digital Library Skripsi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Perpustakaan FMIPA UNPAK</title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>

<!-- Top Header Navbar -->
<header class="top-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <?php if ($isLoggedIn): ?>
        <button class="btn btn-sm btn-outline-light d-md-none" id="sidebarToggle" type="button">
            <i class="bi bi-list fs-5"></i>
        </button>
        <?php endif; ?>
        <a href="index.php" class="d-flex align-items-center text-white text-decoration-none gap-2">
            <img src="assets/img/logo-unpak.svg" alt="Logo UNPAK" width="38" height="38">
            <div>
                <div class="brand-title lh-1">E-SKRIPSI</div>
                <small class="opacity-75" style="font-size: 0.72rem; letter-spacing: 0.5px;">PERPUSTAKAAN FMIPA UNIVERSITAS PAKUAN</small>
            </div>
        </a>
    </div>

    <div class="d-flex align-items-center gap-3">
        <a href="index.php?page=kumpulan_skripsi" class="text-white text-decoration-none small d-none d-sm-inline-block hover-opacity">
            <i class="bi bi-journal-bookmark me-1"></i> Kumpulan Skripsi
        </a>
        
        <?php if ($isLoggedIn): ?>
            <span class="badge-role">
                <i class="bi bi-shield-lock me-1"></i><?= htmlspecialchars($userRole) ?>
            </span>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle small" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-1 text-warning"></i>
                    <span class="d-none d-md-inline fw-semibold"><?= htmlspecialchars($userName) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                    <li><h6 class="dropdown-header"><?= htmlspecialchars($userName) ?> (<?= htmlspecialchars($userRole) ?>)</h6></li>
                    <li><a class="dropdown-item" href="index.php?page=profile"><i class="bi bi-person me-2"></i>Edit Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar / Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="index.php?page=login" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </a>
            <a href="index.php?page=register" class="btn btn-sm btn-gold rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i> Registrasi
            </a>
        <?php endif; ?>
    </div>
</header>
