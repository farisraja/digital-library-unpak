<?php
// views/mahasiswa/profile.php
$pageTitle = 'Edit Profile Pengguna';
require_once dirname(__DIR__) . '/layouts/header.php';
$user = Auth::user();
$role = Auth::role();
$flashSuccess = Auth::getFlash('success');
$flashError = Auth::getFlash('error');

$userPhoto = 'assets/img/default-avatar.svg';
if (!empty($user['foto']) && file_exists(dirname(__DIR__, 2) . '/uploads/profiles/' . $user['foto'])) {
    $userPhoto = 'uploads/profiles/' . $user['foto'];
} elseif (!empty($user['image']) && file_exists(dirname(__DIR__, 2) . '/uploads/profiles/' . $user['image'])) {
    $userPhoto = 'uploads/profiles/' . $user['image'];
}
?>

<div class="app-container">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="mb-4">
            <h4 class="fw-bold mb-1 text-dark">Profil Pengguna</h4>
            <p class="text-muted small">Kelola data informasi akun dan kata sandi Anda.</p>
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

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom text-center p-4">
                    <img src="<?= htmlspecialchars($userPhoto) ?>" alt="Foto Profil" class="rounded-circle mx-auto mb-3 border border-3 border-warning" style="width: 120px; height: 120px; object-fit: cover;">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nama']) ?></h5>
                    <span class="badge bg-primary text-uppercase px-3 py-1 mb-2"><?= htmlspecialchars($role) ?></span>
                    <p class="text-muted small mb-0">Username: <strong><?= htmlspecialchars($user['username']) ?></strong></p>
                    <?php if (!empty($user['nim'])): ?>
                        <p class="text-muted small mb-0">NIM: <strong><?= htmlspecialchars($user['nim']) ?></strong></p>
                    <?php endif; ?>
                    <?php if (!empty($user['nidn'])): ?>
                        <p class="text-muted small mb-0">NIDN: <strong><?= htmlspecialchars($user['nidn']) ?></strong></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Perbarui Data Profil
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?action=do_update_profile" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                                </div>

                                <?php if ($role === 'mahasiswa'): ?>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select">
                                            <option value="Laki-laki" <?= ($user['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="Perempuan" <?= ($user['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control" value="<?= htmlspecialchars($user['tgl_lahir'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Agama</label>
                                        <input type="text" name="agama" class="form-control" value="<?= htmlspecialchars($user['agama'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($user['alamat'] ?? '') ?>">
                                    </div>
                                <?php elseif ($role === 'kaprodi'): ?>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Program Studi</label>
                                        <input type="text" name="nama_prodi" class="form-control" value="<?= htmlspecialchars($user['nama_prodi'] ?? 'Ilmu Komputer') ?>" required>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Foto Profil Baru (Opsional)</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto profil.</small>
                                </div>

                                <div class="col-12 mt-4 pt-3 border-top">
                                    <h6 class="fw-bold text-dark"><i class="bi bi-shield-lock me-1"></i>Ganti Kata Sandi (Opsional)</h6>
                                    <p class="text-muted small">Kosongkan kolom berikut jika tidak ingin mengubah password akun.</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="mt-4 pt-2">
                                <button type="submit" class="btn btn-unpak px-4">
                                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
