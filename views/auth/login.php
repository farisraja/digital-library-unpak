<?php
// views/auth/login.php
$pageTitle = 'Login - E-SKRIPSI FMIPA UNPAK';
require_once dirname(__DIR__) . '/layouts/header.php';

$selectedRole = $_GET['role'] ?? 'mahasiswa';
$flashError = Auth::getFlash('error');
$flashSuccess = Auth::getFlash('success');
?>

<div class="container py-4">
    <div class="login-hero-card">
        <!-- Hero Header -->
        <div class="login-hero-header">
            <img src="assets/img/logo-unpak.svg" alt="UNPAK Logo" width="60" class="mb-2">
            <h2 class="fw-bold tracking-wide mb-1">E-SKRIPSI</h2>
            <p class="mb-3 opacity-90">SISTEM INFORMASI DIGITAL LIBRARY SKRIPSI<br>PERPUSTAKAAN FMIPA UNIVERSITAS PAKUAN</p>
            
            <div class="text-center">
                <img src="assets/img/hero-illustration.svg" alt="E-Library Skripsi" style="max-height: 180px; width: auto;" class="img-fluid drop-shadow">
            </div>
        </div>

        <div class="p-4 p-md-5">
            <?php if ($flashError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($flashSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <h5 class="text-center fw-bold text-muted mb-4">PILIH HAK AKSES PENGGUNA</h5>

            <!-- 3 Role Selection Cards (Sesuai Gambar 18 di Laporan) -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="role-selector-card <?= $selectedRole === 'mahasiswa' ? 'active' : '' ?>" onclick="selectRole('mahasiswa')">
                        <div class="role-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="role-title">MAHASISWA</div>
                        <small class="text-muted">Kirim & cari skripsi digital</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-selector-card <?= $selectedRole === 'kaprodi' ? 'active' : '' ?>" onclick="selectRole('kaprodi')">
                        <div class="role-icon">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="role-title">DOSEN / KAPRODI</div>
                        <small class="text-muted">Review & validasi karya ilmiah</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-selector-card <?= $selectedRole === 'operator' ? 'active' : '' ?>" onclick="selectRole('operator')">
                        <div class="role-icon">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div class="role-title">OPERATOR / ADMIN</div>
                        <small class="text-muted">Kelola data perpustakaan</small>
                    </div>
                </div>
            </div>

            <!-- Login Form (Sesuai Gambar 14 di Laporan) -->
            <div class="card bg-light border-0 p-4 rounded-4" style="max-width: 500px; margin: 0 auto;">
                <form action="index.php?action=do_login" method="POST">
                    <input type="hidden" name="role" id="inputRole" value="<?= htmlspecialchars($selectedRole) ?>">

                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted small fw-semibold">MASUK SEBAGAI:</span>
                        <span class="badge bg-primary text-uppercase px-3 py-2" id="roleBadge">
                            <?= htmlspecialchars($selectedRole) ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username / NIM / NIDN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-unpak w-100 py-2 fs-6 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Sistem
                    </button>
                </form>

                <div class="text-center mt-3 pt-3 border-top">
                    <span class="text-muted small">Mahasiswa baru belum memiliki akun?</span><br>
                    <a href="index.php?page=register" class="text-decoration-none fw-bold text-primary small">
                        <i class="bi bi-pencil-square me-1"></i>Daftar Akun Mahasiswa
                    </a>
                </div>
            </div>

            <!-- Demo Credentials Helper -->
            <div class="mt-4 p-3 border rounded bg-white" style="max-width: 600px; margin: 2rem auto 0;">
                <div class="fw-bold small text-muted text-uppercase mb-2"><i class="bi bi-info-circle me-1"></i> Akun Uji Coba Default (Sesuai Data Laporan):</div>
                <div class="row g-2 small">
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light cursor-pointer" onclick="fillLogin('faris', 'password123', 'mahasiswa')">
                            <strong>Mahasiswa:</strong> faris<br>
                            <code>password123</code>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light cursor-pointer" onclick="fillLogin('kaprodi', 'password123', 'kaprodi')">
                            <strong>Kaprodi:</strong> kaprodi<br>
                            <code>password123</code>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-light cursor-pointer" onclick="fillLogin('admin', 'password123', 'operator')">
                            <strong>Operator:</strong> admin<br>
                            <code>password123</code>
                        </div>
                    </div>
                </div>
                <small class="text-muted d-block mt-2 fst-italic">*Klik salah satu kotak di atas untuk mengisi form login secara otomatis.</small>
            </div>
        </div>
    </div>
</div>

<script>
function selectRole(role) {
    document.getElementById('inputRole').value = role;
    document.getElementById('roleBadge').textContent = role.toUpperCase();
    
    document.querySelectorAll('.role-selector-card').forEach(card => {
        card.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
}

function fillLogin(user, pass, role) {
    selectRole(role);
    document.getElementById('username').value = user;
    document.getElementById('password').value = pass;
}
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
