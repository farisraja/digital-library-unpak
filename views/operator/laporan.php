<?php
// views/operator/laporan.php
$pageTitle = 'Laporan Data Skripsi - Perpustakaan FMIPA UNPAK';
require_once dirname(__DIR__) . '/layouts/header.php';

$filterTahun = $_GET['tahun'] ?? '';
$filterPenjurusan = $_GET['penjurusan'] ?? '';
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <!-- Control Panel (Hidden on Print) -->
        <div class="card card-custom no-print mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-filter me-2 text-primary"></i>Filter Laporan Data Skripsi</span>
                <button onclick="window.print()" class="btn btn-unpak btn-sm">
                    <i class="bi bi-printer me-1"></i>Cetak Laporan / Simpan PDF
                </button>
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page'] ?? 'operator_laporan') ?>">
                    
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Filter Tahun:</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            <?php 
                            $currentYear = (int)date('Y');
                            for ($y = $currentYear; $y >= $currentYear - 6; $y--): ?>
                                <option value="<?= $y ?>" <?= ($filterTahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Filter Peminatan / Penjurusan:</label>
                        <select name="penjurusan" class="form-select form-select-sm">
                            <option value="">Semua Peminatan</option>
                            <option value="Rekayasa Perangkat Lunak" <?= ($filterPenjurusan === 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Jaringan & Keamanan Komputer" <?= ($filterPenjurusan === 'Jaringan & Keamanan Komputer') ? 'selected' : '' ?>>Jaringan & Keamanan Komputer</option>
                            <option value="Kecerdasan Buatan & Data Mining" <?= ($filterPenjurusan === 'Kecerdasan Buatan & Data Mining') ? 'selected' : '' ?>>Kecerdasan Buatan & Data Mining</option>
                            <option value="Sistem Informasi" <?= ($filterPenjurusan === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                            <option value="Multimedia & Game" <?= ($filterPenjurusan === 'Multimedia & Game') ? 'selected' : '' ?>>Multimedia & Game</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Official Report Sheet (Both Screen & Print Friendly) -->
        <div class="card card-custom p-4 p-md-5 bg-white shadow-sm">
            <!-- Kop Surat Universitas Pakuan FMIPA -->
            <div class="d-flex align-items-center gap-3 pb-3 border-bottom border-dark border-3 mb-4">
                <img src="assets/img/logo-unpak.svg" alt="Logo UNPAK" width="85" height="85">
                <div class="text-center flex-grow-1">
                    <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">YAYASAN PAKUAN SILIWANGI</h5>
                    <h4 class="mb-0 fw-bolder text-uppercase text-dark" style="letter-spacing: 1px;">UNIVERSITAS PAKUAN</h4>
                    <h5 class="mb-1 fw-bold text-uppercase" style="color: #5e35b1;">FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</h5>
                    <small class="text-muted d-block" style="font-size: 0.82rem;">
                        Jl. Pakuan, PO Box 452, Bogor 16143, Telp./Fax. (0251) 8375547 | Website: https://fmipa.unpak.ac.id
                    </small>
                </div>
            </div>

            <div class="text-center mb-4">
                <h5 class="fw-bold text-uppercase mb-1" style="text-decoration: underline;">LAPORAN REKAPITULASI ARSIP SKRIPSI MAHASISWA</h5>
                <p class="text-muted small mb-0">
                    Sistem Informasi Digital Library Perpustakaan FMIPA UNPAK
                    <?php if (!empty($filterTahun)): ?> - Tahun Akademik <?= htmlspecialchars($filterTahun) ?><?php endif; ?>
                </p>
            </div>

            <!-- Table of theses -->
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle" style="font-size: 0.88rem;">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 100px;">NIM</th>
                            <th style="width: 160px;">Nama Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th style="width: 60px;">Tahun</th>
                            <th style="width: 130px;">Penjurusan</th>
                            <th style="width: 160px;">Dosen Pembimbing</th>
                            <th style="width: 90px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanList)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Tidak ada data skripsi untuk kriteria filter ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($laporanList as $idx => $r): ?>
                            <tr>
                                <td class="text-center"><?= $idx + 1 ?></td>
                                <td class="text-center fw-semibold"><?= htmlspecialchars($r['nim']) ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($r['nama_mahasiswa'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['judul']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($r['tahun']) ?></td>
                                <td><?= htmlspecialchars($r['penjurusan']) ?></td>
                                <td class="small">
                                    1. <?= htmlspecialchars($r['dosbing']) ?><br>
                                    2. <?= htmlspecialchars($r['dosbing2']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r['nilai'] == 1): ?>
                                        <span class="badge bg-success">Disetujui</span>
                                    <?php elseif ($r['nilai'] == 2): ?>
                                        <span class="badge bg-danger">Revisi</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-5 pt-3">
                <div class="col-7">
                    <p class="small text-muted mb-1">Dicetak pada: <?= date('d F Y, H:i') ?> WIB</p>
                    <p class="small text-muted">Total Arsip Terdata: <strong><?= count($laporanList) ?> Judul</strong></p>
                </div>
                <div class="col-5 text-center">
                    <p class="mb-1">Bogor, <?= date('d F Y') ?></p>
                    <p class="mb-5">Kepala Perpustakaan FMIPA UNPAK,</p>
                    <div style="height: 50px;"></div>
                    <p class="fw-bold mb-0" style="text-decoration: underline;">Endang Suhendar, M.Kom.</p>
                    <small class="text-muted">NIDN / NIP. Perpustakaan FMIPA</small>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
