<?php
// views/mahasiswa/dashboard.php
$pageTitle = 'Dashboard Mahasiswa - Kirim Skripsi';
require_once dirname(__DIR__) . '/layouts/header.php';
$user = Auth::user();
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <!-- Top Breadcrumb / Title (Sesuai Gambar 19 di Laporan) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Halaman Dashboard</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kirim Skripsi</li>
                    </ol>
                </nav>
            </div>
            <span class="badge bg-light text-dark border p-2">
                <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y') ?>
            </span>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Banner -->
        <div class="card card-custom mb-4 bg-white border-start border-4 border-primary">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                        <i class="bi bi-person-badge fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($user['nama']) ?>!</h5>
                        <p class="text-muted small mb-0">NIM: <strong><?= htmlspecialchars($user['nim']) ?></strong> | Program Studi: <strong>Ilmu Komputer FMIPA UNPAK</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Kirim Skripsi (Sesuai Gambar 15 & Gambar 19 di Laporan) -->
        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-upload me-2 text-primary"></i>Kirim Skripsi</span>
                <small class="text-muted fw-normal">Unggah berkas naskah skripsi digital dalam format PDF</small>
            </div>
            <div class="card-body p-4">
                <form action="index.php?action=do_upload_skripsi" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIM</label>
                            <input type="text" name="nim" class="form-control bg-light" value="<?= htmlspecialchars($user['nim'] ?? '') ?>" readonly required>
                            <small class="text-muted">NIM terisi otomatis sesuai akun Anda.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun Lulus / Pengajuan <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-select" required>
                                <?php 
                                $currentYear = (int)date('Y');
                                for ($y = $currentYear; $y >= $currentYear - 6; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Skripsi <span class="text-danger">*</span></label>
                            <textarea name="judul" class="form-control" rows="2" placeholder="Masukkan judul lengkap skripsi" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Format Dokumen <span class="text-danger">*</span></label>
                            <select name="format" class="form-select" required>
                                <option value="PDF Format" selected>PDF Format</option>
                                <option value="DOCX Format">DOCX Format</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penjurusan / Peminatan <span class="text-danger">*</span></label>
                            <select name="penjurusan" class="form-select" required>
                                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak (RPL)</option>
                                <option value="Jaringan & Keamanan Komputer">Jaringan & Keamanan Komputer</option>
                                <option value="Kecerdasan Buatan & Data Mining">Kecerdasan Buatan & Data Mining</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Multimedia & Game">Multimedia & Game</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen Pembimbing 1 <span class="text-danger">*</span></label>
                            <input type="text" name="dosbing" class="form-control" placeholder="Contoh: Teguh Puja Negara, M.Si S.Si." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen Pembimbing 2 <span class="text-danger">*</span></label>
                            <input type="text" name="dosbing2" class="form-control" placeholder="Contoh: Endang Suhendar, M.Kom." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">File Skripsi (Berupa PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="dokumen" class="form-control" accept=".pdf,application/pdf" required>
                            <small class="text-muted">Unggah naskah lengkap atau gabungan cover s/d bab akhir dalam 1 file PDF (maks. 30 MB).</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-unpak px-4 py-2">
                            <i class="bi bi-send-check me-2"></i>Kirim Skripsi
                        </button>
                        <a href="index.php?page=mahasiswa_review" class="btn btn-outline-secondary px-3 py-2 ms-2">
                            Lihat Status Skripsi
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Pengajuan Skripsi Terakhir Anda -->
        <?php if (!empty($mySkripsiList)): ?>
        <div class="card card-custom">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pengajuan Skripsi Anda
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Skripsi</th>
                                <th>Tahun</th>
                                <th>Penjurusan</th>
                                <th>Status Review</th>
                                <th>Berkas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mySkripsiList as $idx => $s): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td class="fw-semibold text-wrap" style="max-width: 320px;">
                                    <?= htmlspecialchars($s['judul']) ?>
                                    <div class="small text-muted">Pembimbing: <?= htmlspecialchars($s['dosbing']) ?> & <?= htmlspecialchars($s['dosbing2']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($s['tahun']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($s['penjurusan']) ?></span></td>
                                <td>
                                    <?php if ($s['nilai'] == 1): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui / Valid</span>
                                    <?php elseif ($s['nilai'] == 2): ?>
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>Perlu Revisi</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Review</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="uploads/skripsi/<?= htmlspecialchars($s['dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf me-1"></i>Unduh PDF
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
