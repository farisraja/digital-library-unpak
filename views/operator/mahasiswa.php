<?php
// views/operator/mahasiswa.php
$pageTitle = 'Kelola Data Mahasiswa';
require_once dirname(__DIR__) . '/layouts/header.php';
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Data Mahasiswa</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                        <li class="breadcrumb-item active">Data Mahasiswa</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-unpak btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa">
                <i class="bi bi-person-plus me-1"></i>Tambah Mahasiswa
            </button>
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

        <div class="card card-custom">
            <div class="card-header fw-bold">
                <i class="bi bi-people me-2 text-primary"></i>Daftar Mahasiswa Terdaftar
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Program Studi</th>
                                <th>Username</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mahasiswaList)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data mahasiswa.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($mahasiswaList as $i => $m): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <img src="assets/img/default-avatar.svg" alt="Avatar" width="36" height="36" class="rounded-circle border">
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($m['nim']) ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($m['nama']) ?></td>
                                    <td><?= htmlspecialchars($m['jenis_kelamin']) ?></td>
                                    <td><?= htmlspecialchars($m['nama_prodi'] ?? 'Ilmu Komputer') ?></td>
                                    <td><code><?= htmlspecialchars($m['username']) ?></code></td>
                                    <td class="text-center">
                                        <a href="index.php?action=delete_mahasiswa&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus akun mahasiswa ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
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

<!-- Modal Tambah Mahasiswa Manual oleh Operator -->
<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="index.php?action=do_add_mahasiswa" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Data Mahasiswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Contoh: 065120125" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Program Studi</label>
                            <select name="id_prodi" class="form-select">
                                <option value="1">Ilmu Komputer</option>
                                <option value="2">Matematika</option>
                                <option value="3">Kimia</option>
                                <option value="4">Biologi</option>
                                <option value="5">Farmasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Agama</label>
                            <input type="text" name="agama" class="form-control" value="Islam" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Akun</label>
                            <input type="password" name="password" class="form-control" value="password123" required>
                            <small class="text-muted">Default: password123</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-unpak">Simpan Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
