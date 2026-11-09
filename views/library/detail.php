<?php
// views/library/detail.php
$pageTitle = htmlspecialchars($skripsi['judul'] ?? 'Detail Skripsi');
require_once dirname(__DIR__) . '/layouts/header.php';

$apaCitation = htmlspecialchars($skripsi['nama_mahasiswa'] ?? 'Penulis') . " (" . htmlspecialchars($skripsi['tahun']) . "). " . htmlspecialchars($skripsi['judul']) . " [Skripsi, Program Studi Ilmu Komputer Universitas Pakuan]. Digital Library FMIPA UNPAK.";
?>

<div class="container py-4">
    <div class="mb-3">
        <a href="index.php?page=kumpulan_skripsi" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kumpulan Skripsi
        </a>
    </div>

    <div class="row g-4">
        <!-- Metadata Info -->
        <div class="col-lg-5">
            <div class="card card-custom p-4">
                <span class="badge bg-warning text-dark align-self-start mb-2 fw-bold">
                    <i class="bi bi-mortarboard me-1"></i> SKRIPSI PROGRAM SARJANA (S1)
                </span>
                <h4 class="fw-bold text-dark mb-3"><?= htmlspecialchars($skripsi['judul']) ?></h4>

                <div class="table-responsive">
                    <table class="table table-sm table-borderless small mb-4">
                        <tr>
                            <td class="text-muted" style="width: 140px;">Penulis</td>
                            <td class="fw-bold">: <?= htmlspecialchars($skripsi['nama_mahasiswa'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIM</td>
                            <td>: <?= htmlspecialchars($skripsi['nim']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Program Studi</td>
                            <td>: <?= htmlspecialchars($skripsi['nama_prodi'] ?? 'Ilmu Komputer') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fakultas / Instansi</td>
                            <td>: FMIPA Universitas Pakuan</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tahun Terbit</td>
                            <td>: <?= htmlspecialchars($skripsi['tahun']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Peminatan</td>
                            <td>: <span class="badge bg-primary bg-opacity-10 text-primary"><?= htmlspecialchars($skripsi['penjurusan']) ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pembimbing 1</td>
                            <td>: <?= htmlspecialchars($skripsi['dosbing']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pembimbing 2</td>
                            <td>: <?= htmlspecialchars($skripsi['dosbing2']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Format Berkas</td>
                            <td>: <?= htmlspecialchars($skripsi['format']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Digital Library</td>
                            <td>: 
                                <?php if ($skripsi['nilai'] == 1): ?>
                                    <span class="badge bg-success">Terverifikasi & Valid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Dalam Peninjauan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <a href="uploads/skripsi/<?= htmlspecialchars($skripsi['dokumen']) ?>" target="_blank" class="btn btn-unpak py-2">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Buka & Unduh Naskah PDF
                    </a>
                </div>

                <!-- Box Sitasi APA -->
                <div class="p-3 bg-light rounded border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-muted text-uppercase">Format Sitasi (APA 7th):</span>
                        <button class="btn btn-sm btn-link text-decoration-none p-0" onclick="copyCitation()">Salin</button>
                    </div>
                    <code class="d-block small text-dark p-2 bg-white rounded border" id="citationBox"><?= $apaCitation ?></code>
                </div>
            </div>
        </div>

        <!-- PDF Reader / Preview -->
        <div class="col-lg-7">
            <div class="card card-custom p-3" style="min-height: 650px;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center px-2 pt-1 pb-3">
                    <span class="fw-bold"><i class="bi bi-eye me-2 text-primary"></i>Pratinjau Dokumen PDF</span>
                    <a href="uploads/skripsi/<?= htmlspecialchars($skripsi['dokumen']) ?>" download class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Unduh Berkas
                    </a>
                </div>
                <div class="card-body p-0 pt-2 flex-grow-1">
                    <iframe src="uploads/skripsi/<?= htmlspecialchars($skripsi['dokumen']) ?>" width="100%" height="600" style="border: none; border-radius: 8px;">
                        <p class="p-4 text-center">Browser Anda tidak mendukung iframe PDF langsung. Silakan <a href="uploads/skripsi/<?= htmlspecialchars($skripsi['dokumen']) ?>" target="_blank">klik di sini untuk membuka PDF</a>.</p>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCitation() {
    const text = document.getElementById('citationBox').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Sitasi berhasil disalin ke clipboard!');
    });
}
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
