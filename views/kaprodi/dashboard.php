<?php
// views/kaprodi/dashboard.php
$pageTitle = 'Dashboard Kaprodi - FMIPA UNPAK';
require_once dirname(__DIR__) . '/layouts/header.php';
$user = Auth::user();
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Dashboard Ketua Program Studi</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Kaprodi</a></li>
                        <li class="breadcrumb-item active">Monitoring Skripsi</li>
                    </ol>
                </nav>
            </div>
            <a href="index.php?page=kaprodi_review" class="btn btn-unpak btn-sm">
                <i class="bi bi-check2-circle me-1"></i>Buka Validasi & Review
            </a>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Card -->
        <div class="card card-custom mb-4 bg-white border-start border-4 border-warning">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-dark p-3 rounded-circle">
                        <i class="bi bi-person-workspace fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($user['nama']) ?></h5>
                        <p class="text-muted small mb-0">Kaprodi <strong><?= htmlspecialchars($user['nama_prodi'] ?? 'Ilmu Komputer') ?></strong> - FMIPA Universitas Pakuan | NIDN: <strong><?= htmlspecialchars($user['nidn'] ?? '-') ?></strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-purple">
                    <div>
                        <div class="stat-num"><?= $stats['total'] ?></div>
                        <div class="stat-label">Total Arsip Skripsi</div>
                    </div>
                    <i class="bi bi-journal-text stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-orange">
                    <div>
                        <div class="stat-num"><?= $stats['pending'] ?></div>
                        <div class="stat-label">Menunggu Validasi</div>
                    </div>
                    <i class="bi bi-hourglass-top stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-green">
                    <div>
                        <div class="stat-num"><?= $stats['approved'] ?></div>
                        <div class="stat-label">Telah Disetujui</div>
                    </div>
                    <i class="bi bi-check-circle stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-blue">
                    <div>
                        <div class="stat-num"><?= $stats['mahasiswa'] ?></div>
                        <div class="stat-label">Mahasiswa Aktif</div>
                    </div>
                    <i class="bi bi-mortarboard stat-icon"></i>
                </div>
            </div>
        </div>

        <!-- Pengajuan Skripsi Terbaru yang Membutuhkan Validasi -->
        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-bell me-2 text-warning"></i>Pengajuan Skripsi Menunggu Validasi Kaprodi</span>
                <a href="index.php?page=kaprodi_review" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Penjurusan</th>
                                <th>Dosen Pembimbing</th>
                                <th>Naskah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $pendingList = array_filter($skripsiList, fn($s) => $s['nilai'] == 0);
                            if (empty($pendingList)): 
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-check2-all text-success me-1"></i> Tidak ada pengajuan skripsi yang menunggu validasi saat ini.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($pendingList, 0, 5) as $row): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['nim']) ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($row['nama_mahasiswa'] ?? '-') ?></td>
                                    <td class="text-wrap" style="max-width: 250px;"><?= htmlspecialchars($row['judul']) ?></td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary border"><?= htmlspecialchars($row['penjurusan']) ?></span></td>
                                    <td class="small">1. <?= htmlspecialchars($row['dosbing']) ?><br>2. <?= htmlspecialchars($row['dosbing2']) ?></td>
                                    <td>
                                        <a href="uploads/skripsi/<?= htmlspecialchars($row['dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-unpak" 
                                                onclick="openReviewModal(<?= $row['id'] ?>, <?= $row['nilai'] ?>, '<?= htmlspecialchars(addslashes($row['catatan_review'] ?? '')) ?>')">
                                            <i class="bi bi-pencil-square me-1"></i>Review
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Review Kaprodi -->
<div class="modal fade" id="modalReviewKaprodi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?action=do_review_skripsi" method="POST">
                <input type="hidden" name="id" id="reviewSkripsiId">
                <input type="hidden" name="redirect" value="kaprodi_dashboard">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Validasi & Review Naskah Skripsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Validasi:</label>
                        <select name="nilai" id="reviewNilai" class="form-select" required>
                            <option value="1">Disetujui / Layak Masuk Digital Library</option>
                            <option value="2">Perlu Revisi (Naskah / Format Kurang Lengkap)</option>
                            <option value="0">Menunggu Review</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Evaluasi / Arahan:</label>
                        <textarea name="catatan_review" id="reviewCatatan" class="form-control" rows="4" placeholder="Tulis catatan evaluasi untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-unpak">Simpan Validasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReviewModal(id, nilai, catatan) {
    document.getElementById('reviewSkripsiId').value = id;
    document.getElementById('reviewNilai').value = nilai;
    document.getElementById('reviewCatatan').value = catatan;
    new bootstrap.Modal(document.getElementById('modalReviewKaprodi')).show();
}
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
