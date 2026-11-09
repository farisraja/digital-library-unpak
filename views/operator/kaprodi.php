<?php
// views/operator/kaprodi.php
$pageTitle = 'Kelola Data Kaprodi';
require_once dirname(__DIR__) . '/layouts/header.php';
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Data Kepala Program Studi (Kaprodi)</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                        <li class="breadcrumb-item active">Data Kaprodi</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-unpak btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKaprodi">
                <i class="bi bi-person-plus me-1"></i>Tambah Kaprodi
            </button>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom">
            <div class="card-header fw-bold">
                <i class="bi bi-person-workspace me-2 text-primary"></i>Daftar Akun Kaprodi FMIPA UNPAK
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>NIDN</th>
                                <th>Nama Lengkap & Gelar</th>
                                <th>Program Studi</th>
                                <th>Username</th>
                                <th>Hak Akses</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kaprodiList as $idx => $k): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($k['nidn']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($k['nama']) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($k['nama_prodi']) ?></span></td>
                                <td><code><?= htmlspecialchars($k['username']) ?></code></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($k['akses']) ?></span></td>
                                <td class="text-center">
                                    <a href="index.php?action=delete_kaprodi&id=<?= $k['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Hapus akun Kaprodi ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah Kaprodi -->
<div class="modal fade" id="modalTambahKaprodi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?action=do_add_kaprodi" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Akun Kaprodi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIDN</label>
                        <input type="text" name="nidn" class="form-control" placeholder="Contoh: 0412088501" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Arie Qur'ania, M.Kom." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Program Studi</label>
                        <select name="nama_prodi" class="form-select" required>
                            <option value="Ilmu Komputer">Ilmu Komputer</option>
                            <option value="Matematika">Matematika</option>
                            <option value="Kimia">Kimia</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Farmasi">Farmasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" value="password123" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-unpak">Simpan Kaprodi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
