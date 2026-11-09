<?php
// views/layouts/sidebar.php
require_once dirname(__DIR__, 2) . '/app/Helpers/Auth.php';
$role = Auth::role();
$user = Auth::user();
$currentPage = $_GET['page'] ?? 'dashboard';

$userPhoto = 'assets/img/default-avatar.svg';
if (!empty($user['foto']) && file_exists(dirname(__DIR__, 2) . '/uploads/profiles/' . $user['foto'])) {
    $userPhoto = 'uploads/profiles/' . $user['foto'];
} elseif (!empty($user['image']) && file_exists(dirname(__DIR__, 2) . '/uploads/profiles/' . $user['image'])) {
    $userPhoto = 'uploads/profiles/' . $user['image'];
}
?>
<aside class="sidebar" id="appSidebar">
    <div class="user-panel">
        <img src="<?= htmlspecialchars($userPhoto) ?>" alt="Avatar Pengguna">
        <div class="fw-bold text-white fs-6 text-truncate" title="<?= htmlspecialchars(Auth::name()) ?>">
            <?= htmlspecialchars(Auth::name()) ?>
        </div>
        <small class="text-warning text-uppercase fw-semibold" style="font-size: 0.75rem;">
            <?= htmlspecialchars($role) ?>
        </small>
        <?php if ($role === 'mahasiswa' && !empty($user['nim'])): ?>
            <div class="text-white-50 small mt-1">NIM: <?= htmlspecialchars($user['nim']) ?></div>
        <?php endif; ?>
    </div>

    <nav class="nav-menu">
        <?php if ($role === 'mahasiswa'): ?>
            <a href="index.php?page=mahasiswa_dashboard" class="nav-link <?= ($currentPage === 'mahasiswa_dashboard' || $currentPage === 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-house-door"></i>
                <span>Home / Kirim Skripsi</span>
            </a>
            <a href="index.php?page=profile" class="nav-link <?= ($currentPage === 'profile') ? 'active' : '' ?>">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
            <a href="index.php?page=mahasiswa_review" class="nav-link <?= ($currentPage === 'mahasiswa_review') ? 'active' : '' ?>">
                <i class="bi bi-clipboard-check"></i>
                <span>Lihat Hasil Review</span>
            </a>
            <a href="index.php?page=kumpulan_skripsi" class="nav-link <?= ($currentPage === 'kumpulan_skripsi') ? 'active' : '' ?>">
                <i class="bi bi-collection"></i>
                <span>Kumpulan Skripsi</span>
            </a>

        <?php elseif ($role === 'operator'): ?>
            <a href="index.php?page=operator_dashboard" class="nav-link <?= ($currentPage === 'operator_dashboard' || $currentPage === 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard / Skripsi</span>
            </a>
            <a href="index.php?page=operator_mahasiswa" class="nav-link <?= ($currentPage === 'operator_mahasiswa') ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Data Mahasiswa</span>
            </a>
            <a href="index.php?page=operator_kaprodi" class="nav-link <?= ($currentPage === 'operator_kaprodi') ? 'active' : '' ?>">
                <i class="bi bi-person-workspace"></i>
                <span>Data Kaprodi</span>
            </a>
            <a href="index.php?page=operator_laporan" class="nav-link <?= ($currentPage === 'operator_laporan') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Skripsi</span>
            </a>
            <a href="index.php?page=kumpulan_skripsi" class="nav-link <?= ($currentPage === 'kumpulan_skripsi') ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i>
                <span>Katalog Digital</span>
            </a>
            <a href="index.php?page=profile" class="nav-link <?= ($currentPage === 'profile') ? 'active' : '' ?>">
                <i class="bi bi-person-gear"></i>
                <span>Edit Profile</span>
            </a>

        <?php elseif ($role === 'kaprodi'): ?>
            <a href="index.php?page=kaprodi_dashboard" class="nav-link <?= ($currentPage === 'kaprodi_dashboard' || $currentPage === 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="index.php?page=kaprodi_review" class="nav-link <?= ($currentPage === 'kaprodi_review') ? 'active' : '' ?>">
                <i class="bi bi-check2-circle"></i>
                <span>Validasi & Review</span>
            </a>
            <a href="index.php?page=kumpulan_skripsi" class="nav-link <?= ($currentPage === 'kumpulan_skripsi') ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i>
                <span>Kumpulan Skripsi</span>
            </a>
            <a href="index.php?page=kaprodi_laporan" class="nav-link <?= ($currentPage === 'kaprodi_laporan') ? 'active' : '' ?>">
                <i class="bi bi-printer"></i>
                <span>Laporan Skripsi</span>
            </a>
            <a href="index.php?page=profile" class="nav-link <?= ($currentPage === 'profile') ? 'active' : '' ?>">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        <?php endif; ?>

        <hr class="my-3 mx-3 border-secondary">
        <a href="index.php?page=logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Log Out</span>
        </a>
    </nav>
</aside>
