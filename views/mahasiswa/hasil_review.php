<?php
// views/mahasiswa/hasil_review.php
$pageTitle = 'Hasil Review Skripsi';
require_once dirname(__DIR__) . '/layouts/header.php';
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Hasil Review Skripsi</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Status & Review</li>
                    </ol>
                </nav>
            </div>
            <a href="index.php?page=mahasiswa_dashboard" class="btn btn-unpak btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Kirim Skripsi Baru
            </a>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($mySkripsiList)): ?>
            <div class="card card-custom text-center p-5">
                <i class="bi bi-file-earmark-x display-4 text-muted mb-3"></i>
                <h5 class="fw-bold">Belum Ada Pengajuan Skripsi</h5>
                <p class="text-muted">Anda belum mengirimkan dokumen skripsi ke sistem Digital Library Perpustakaan FMIPA.</p>
                <div>
                    <a href="index.php?page=mahasiswa_dashboard" class="btn btn-unpak">
                        <i class="bi bi-upload me-2"></i>Kirim Skripsi Sekarang
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($mySkripsiList as $s): ?>
                <div class="col-12">
                    <div class="card card-custom border-start border-4 <?= $s['nilai'] == 1 ? 'border-success' : ($s['nilai'] == 2 ? 'border-danger' : 'border-warning') ?>">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <span class="badge bg-light text-dark border me-2">Tahun <?= htmlspecialchars($s['tahun']) ?></span>
                                    <span class="badge bg-primary me-2"><?= htmlspecialchars($s['penjurusan']) ?></span>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($s['format']) ?></span>
                                </div>
                                <div>
                                    <?php if ($s['nilai'] == 1): ?>
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui & Valid
                                        </span>
                                    <?php elseif ($s['nilai'] == 2): ?>
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Perlu Revisi
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="bi bi-clock-history me-1"></i> Sedang Dalam Proses Review
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark mb-3"><?= htmlspecialchars($s['judul']) ?></h5>

                            <div class="row g-3 text-muted small mb-3">
                                <div class="col-md-4">
                                    <strong><i class="bi bi-person me-1"></i>Dosen Pembimbing 1:</strong><br>
                                    <span class="text-dark"><?= htmlspecialchars($s['dosbing']) ?></span>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="bi bi-person me-1"></i>Dosen Pembimbing 2:</strong><br>
                                    <span class="text-dark"><?= htmlspecialchars($s['dosbing2']) ?></span>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="bi bi-calendar-event me-1"></i>Waktu Pengajuan:</strong><br>
                                    <span class="text-dark"><?= date('d F Y, H:i', strtotime($s['created_at'])) ?> WIB</span>
                                </div>
                            </div>

                            <!-- Catatan Review -->
                            <div class="p-3 rounded bg-light border mb-3">
                                <div class="fw-bold small mb-1 text-secondary">
                                    <i class="bi bi-chat-left-text me-1"></i>Catatan / Masukan dari Kaprodi / Operator:
                                </div>
                                <p class="mb-0 text-dark">
                                    <?= !empty($s['catatan_review']) ? nl2br(htmlspecialchars($s['catatan_review'])) : '<em>Belum ada catatan review khusus.</em>' ?>
                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="uploads/skripsi/<?= htmlspecialchars($s['dokumen']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat Naskah PDF
                                </a>
                                <a href="uploads/skripsi/<?= htmlspecialchars($s['dokumen']) ?>" download class="btn btn-unpak btn-sm">
                                    <i class="bi bi-download me-1"></i>Unduh Berkas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
