<?php
// views/library/index.php
$pageTitle = 'Kumpulan Skripsi - Digital Library FMIPA UNPAK';
require_once dirname(__DIR__) . '/layouts/header.php';

$searchQuery = $_GET['q'] ?? '';
$filterTahun = $_GET['tahun'] ?? '';
$filterPenjurusan = $_GET['penjurusan'] ?? '';
?>

<div class="container py-4">
    <!-- Banner Header -->
    <div class="card card-custom bg-white border-0 shadow-sm p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-warning text-dark mb-2 fw-bold text-uppercase">
                    <i class="bi bi-book-half me-1"></i> Repository Karya Ilmiah
                </span>
                <h3 class="fw-bold text-dark mb-1">Kumpulan Skripsi Mahasiswa FMIPA</h3>
                <p class="text-muted mb-0">Temukan referensi penelitian, skripsi, dan tugas akhir Program Studi Ilmu Komputer Universitas Pakuan.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="index.php?page=login" class="btn btn-unpak">
                    <i class="bi bi-upload me-1"></i> Unggah Skripsi
                </a>
            </div>
        </div>

        <hr class="my-4">

        <!-- Search & Filter Bar -->
        <form method="GET" action="index.php" class="row g-2">
            <input type="hidden" name="page" value="kumpulan_skripsi">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari judul, kata kunci, NIM, penulis, pembimbing..." value="<?= htmlspecialchars($searchQuery) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    <?php 
                    $currentYear = (int)date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 6; $y--): ?>
                        <option value="<?= $y ?>" <?= ($filterTahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="penjurusan" class="form-select">
                    <option value="">Semua Penjurusan</option>
                    <option value="Rekayasa Perangkat Lunak" <?= ($filterPenjurusan === 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                    <option value="Jaringan & Keamanan Komputer" <?= ($filterPenjurusan === 'Jaringan & Keamanan Komputer') ? 'selected' : '' ?>>Jaringan & Keamanan Komputer</option>
                    <option value="Kecerdasan Buatan & Data Mining" <?= ($filterPenjurusan === 'Kecerdasan Buatan & Data Mining') ? 'selected' : '' ?>>Kecerdasan Buatan & Data Mining</option>
                    <option value="Sistem Informasi" <?= ($filterPenjurusan === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                    <option value="Multimedia & Game" <?= ($filterPenjurusan === 'Multimedia & Game') ? 'selected' : '' ?>>Multimedia & Game</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-gold w-100 fw-bold">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-muted fw-bold mb-0">
            Ditemukan <?= count($skripsiList) ?> naskah skripsi
            <?php if (!empty($searchQuery)): ?>
                untuk kata kunci "<em><?= htmlspecialchars($searchQuery) ?></em>"
            <?php endif; ?>
        </h6>
    </div>

    <?php if (empty($skripsiList)): ?>
        <div class="card card-custom text-center p-5">
            <i class="bi bi-search display-3 text-muted mb-3"></i>
            <h5 class="fw-bold">Tidak Ada Skripsi yang Sesuai</h5>
            <p class="text-muted">Coba ubah kata kunci pencarian atau reset filter tahun dan peminatan.</p>
            <div>
                <a href="index.php?page=kumpulan_skripsi" class="btn btn-outline-primary btn-sm">Reset Pencarian</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($skripsiList as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom h-100 d-flex flex-column border-top border-4 border-primary">
                    <div class="card-body flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($s['tahun']) ?>
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border">
                                <?= htmlspecialchars($s['penjurusan']) ?>
                            </span>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-3 line-clamp-2" style="min-height: 48px;">
                            <a href="index.php?page=skripsi_detail&id=<?= $s['id'] ?>" class="text-decoration-none text-dark hover-primary">
                                <?= htmlspecialchars($s['judul']) ?>
                            </a>
                        </h5>

                        <div class="small text-muted mb-2">
                            <i class="bi bi-person-circle me-1 text-primary"></i>
                            <strong><?= htmlspecialchars($s['nama_mahasiswa'] ?? 'Mahasiswa') ?></strong>
                            <span class="opacity-75">(NIM: <?= htmlspecialchars($s['nim']) ?>)</span>
                        </div>

                        <div class="small text-muted mb-3">
                            <i class="bi bi-person-lines-fill me-1"></i>
                            Pembimbing: <?= htmlspecialchars($s['dosbing']) ?>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <a href="index.php?page=skripsi_detail&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-info-circle me-1"></i>Detail & Baca
                        </a>
                        <a href="uploads/skripsi/<?= htmlspecialchars($s['dokumen']) ?>" target="_blank" class="btn btn-sm btn-unpak">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
