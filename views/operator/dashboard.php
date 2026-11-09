<?php
// views/operator/dashboard.php
$pageTitle = 'Dashboard Operator - Data Skripsi';
require_once dirname(__DIR__) . '/layouts/header.php';
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <!-- Top Title (Sesuai Gambar 20 di Laporan) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Halaman Data Skripsi</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                        <li class="breadcrumb-item active">Kelola Skripsi</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php?page=operator_laporan" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer me-1"></i>Cetak Laporan
                </a>
            </div>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards Summary -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-purple">
                    <div>
                        <div class="stat-num"><?= $stats['total'] ?></div>
                        <div class="stat-label">Total Naskah Skripsi</div>
                    </div>
                    <i class="bi bi-journal-album stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-green">
                    <div>
                        <div class="stat-num"><?= $stats['approved'] ?></div>
                        <div class="stat-label">Disetujui / Terverifikasi</div>
                    </div>
                    <i class="bi bi-check2-circle stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-orange">
                    <div>
                        <div class="stat-num"><?= $stats['pending'] ?></div>
                        <div class="stat-label">Menunggu Validasi</div>
                    </div>
                    <i class="bi bi-hourglass-split stat-icon"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card stat-blue">
                    <div>
                        <div class="stat-num"><?= $stats['mahasiswa'] ?></div>
                        <div class="stat-label">Mahasiswa Terdaftar</div>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
        </div>

        <!-- Visualisasi Grafik Analytics (Chart.js) -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card card-custom h-100">
                    <div class="card-header bg-white">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Tren Pengarsipan Skripsi Tahunan
                    </div>
                    <div class="card-body">
                        <canvas id="chartTahun" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom h-100">
                    <div class="card-header bg-white">
                        <i class="bi bi-pie-chart-fill me-2 text-warning"></i>Distribusi Peminatan / Bidang
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartPenjurusan" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Skripsi (Sesuai Gambar 20 di Laporan) -->
        <div class="card card-custom">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="fw-bold"><i class="bi bi-table me-2 text-primary"></i>Daftar Seluruh Data Skripsi Mahasiswa</div>
                <div class="d-flex gap-2">
                    <form method="GET" action="index.php" class="d-flex gap-2">
                        <input type="hidden" name="page" value="operator_dashboard">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Menunggu Review</option>
                            <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Disetujui</option>
                            <option value="2" <?= (isset($_GET['status']) && $_GET['status'] === '2') ? 'selected' : '' ?>>Perlu Revisi</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-custom table-hover align-middle mb-0 datatable-init">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Tahun</th>
                                <th>Penjurusan</th>
                                <th>Pembimbing</th>
                                <th>Berkas</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($skripsiList)): ?>
                                <?php foreach ($skripsiList as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['nim']) ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($row['nama_mahasiswa'] ?? 'Mahasiswa') ?></td>
                                    <td class="text-wrap" style="max-width: 280px;">
                                        <?= htmlspecialchars($row['judul']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['tahun']) ?></td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary border"><?= htmlspecialchars($row['penjurusan']) ?></span></td>
                                    <td class="small">
                                        1. <?= htmlspecialchars($row['dosbing']) ?><br>
                                        2. <?= htmlspecialchars($row['dosbing2']) ?>
                                    </td>
                                    <td>
                                        <a href="uploads/skripsi/<?= htmlspecialchars($row['dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Buka PDF">
                                            <i class="bi bi-file-pdf"></i> PDF
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($row['nilai'] == 1): ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php elseif ($row['nilai'] == 2): ?>
                                            <span class="badge bg-danger">Revisi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-success" title="Validasi" 
                                                    onclick="openValidasiModal(<?= $row['id'] ?>, <?= $row['nilai'] ?>, '<?= htmlspecialchars(addslashes($row['catatan_review'] ?? '')) ?>')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <a href="index.php?page=skripsi_detail&id=<?= $row['id'] ?>" class="btn btn-outline-info" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="confirmDelete('index.php?action=delete_skripsi&id=<?= $row['id'] ?>', 'Hapus Naskah Skripsi Ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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

<!-- Modal Validasi / Review Status -->
<div class="modal fade" id="modalValidasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?action=do_review_skripsi" method="POST">
                <input type="hidden" name="id" id="modalSkripsiId">
                <input type="hidden" name="redirect" value="operator_dashboard">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Validasi Status Skripsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keputusan Status:</label>
                        <select name="nilai" id="modalNilai" class="form-select" required>
                            <option value="1">Disetujui / Valid (Layak Arsip)</option>
                            <option value="0">Menunggu Review / Pending</option>
                            <option value="2">Perlu Revisi (Kembalikan ke Mahasiswa)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan / Keterangan:</label>
                        <textarea name="catatan_review" id="modalCatatan" class="form-control" rows="3" placeholder="Masukkan catatan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openValidasiModal(id, nilai, catatan) {
    document.getElementById('modalSkripsiId').value = id;
    document.getElementById('modalNilai').value = nilai;
    document.getElementById('modalCatatan').value = catatan;
    new bootstrap.Modal(document.getElementById('modalValidasi')).show();
}

// Inisialisasi Chart.js
document.addEventListener('DOMContentLoaded', function() {
    const dataTahun = <?= json_encode($stats['by_tahun'] ?? []) ?>;
    const dataPenjurusan = <?= json_encode($stats['by_penjurusan'] ?? []) ?>;

    // Chart Tren Tahunan
    const ctxTahun = document.getElementById('chartTahun');
    if (ctxTahun) {
        new Chart(ctxTahun, {
            type: 'bar',
            data: {
                labels: dataTahun.map(d => d.tahun),
                datasets: [{
                    label: 'Jumlah Skripsi',
                    data: dataTahun.map(d => d.total),
                    backgroundColor: '#5e35b1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // Chart Distribusi Peminatan
    const ctxPenjurusan = document.getElementById('chartPenjurusan');
    if (ctxPenjurusan) {
        new Chart(ctxPenjurusan, {
            type: 'doughnut',
            data: {
                labels: dataPenjurusan.map(d => d.penjurusan),
                datasets: [{
                    data: dataPenjurusan.map(d => d.total),
                    backgroundColor: ['#5e35b1', '#fbc02d', '#2e7d32', '#0288d1', '#e64a19']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                }
            }
        });
    }
});
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
