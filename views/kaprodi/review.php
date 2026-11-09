<?php
// views/kaprodi/review.php
$pageTitle = 'Validasi & Review Skripsi - Kaprodi';
require_once dirname(__DIR__) . '/layouts/header.php';
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Validasi Naskah Skripsi</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Kaprodi</a></li>
                        <li class="breadcrumb-item active">Evaluasi & Validasi</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom">
            <div class="card-header fw-bold">
                <i class="bi bi-journal-check me-2 text-primary"></i>Seluruh Naskah Skripsi Mahasiswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-custom table-hover align-middle mb-0 datatable-init">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>NIM</th>
                                <th>Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Tahun</th>
                                <th>Penjurusan</th>
                                <th>Pembimbing</th>
                                <th>Berkas</th>
                                <th>Status Review</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($skripsiList)): ?>
                                <?php foreach ($skripsiList as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($s['nim']) ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($s['nama_mahasiswa'] ?? '-') ?></td>
                                    <td class="text-wrap" style="max-width: 280px;"><?= htmlspecialchars($s['judul']) ?></td>
                                    <td><?= htmlspecialchars($s['tahun']) ?></td>
                                    <td><?= htmlspecialchars($s['penjurusan']) ?></td>
                                    <td class="small">1. <?= htmlspecialchars($s['dosbing']) ?><br>2. <?= htmlspecialchars($s['dosbing2']) ?></td>
                                    <td>
                                        <a href="uploads/skripsi/<?= htmlspecialchars($s['dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($s['nilai'] == 1): ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php elseif ($s['nilai'] == 2): ?>
                                            <span class="badge bg-danger">Revisi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-unpak"
                                                onclick="openReviewModal(<?= $s['id'] ?>, <?= $s['nilai'] ?>, '<?= htmlspecialchars(addslashes($s['catatan_review'] ?? '')) ?>')">
                                            <i class="bi bi-pencil-square me-1"></i>Evaluasi
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

<!-- Modal Evaluasi -->
<div class="modal fade" id="modalReviewKaprodi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?action=do_review_skripsi" method="POST">
                <input type="hidden" name="id" id="reviewSkripsiId">
                <input type="hidden" name="redirect" value="kaprodi_review">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Evaluasi & Validasi Skripsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Validasi:</label>
                        <select name="nilai" id="reviewNilai" class="form-select" required>
                            <option value="1">Disetujui (Layak Arsip di Digital Library)</option>
                            <option value="2">Perlu Revisi (Kembalikan ke Mahasiswa)</option>
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
