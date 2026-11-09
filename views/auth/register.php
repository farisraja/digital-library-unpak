<?php
// views/auth/register.php
$pageTitle = 'Registrasi Mahasiswa - E-SKRIPSI FMIPA UNPAK';
require_once dirname(__DIR__) . '/layouts/header.php';

$flashError = Auth::getFlash('error');
$flashSuccess = Auth::getFlash('success');
?>

<div class="container py-5">
    <div class="card card-custom mx-auto shadow" style="max-width: 650px;">
        <div class="card-header text-center bg-white border-bottom pt-4 pb-3">
            <img src="assets/img/logo-unpak.svg" alt="Logo Universitas Pakuan" width="70" class="mb-2">
            <h4 class="fw-bold mb-0 text-dark">Registrasi Mahasiswa</h4>
            <small class="text-muted">Perpustakaan FMIPA Universitas Pakuan</small>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if ($flashError): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="index.php?action=do_register" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIM (Nomor Pokok Mahasiswa) <span class="text-danger">*</span></label>
                        <input type="text" name="nim" class="form-control" placeholder="Contoh: 065120125" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTM" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                        <select name="id_prodi" class="form-select" required>
                            <option value="1" selected>Ilmu Komputer</option>
                            <option value="2">Matematika</option>
                            <option value="3">Kimia</option>
                            <option value="4">Biologi</option>
                            <option value="5">Farmasi</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                        <select name="agama" class="form-select" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen Protestan">Kristen Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Khonghucu">Khonghucu</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat domisili saat ini" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto Profil (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-unpak w-100 py-2 fs-6">
                        <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="text-center mt-3 pt-3 border-top">
                <span class="text-muted small">Sudah memiliki akun?</span>
                <a href="index.php?page=login&role=mahasiswa" class="fw-bold text-decoration-none small text-primary ms-1">Login di sini</a>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
