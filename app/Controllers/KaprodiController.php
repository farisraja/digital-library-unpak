<?php
// app/Controllers/KaprodiController.php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__) . '/Helpers/Auth.php';
require_once dirname(__DIR__) . '/Helpers/Upload.php';
require_once dirname(__DIR__) . '/Models/Kaprodi.php';
require_once dirname(__DIR__) . '/Models/Skripsi.php';

class KaprodiController {
    public function __construct() {
        Auth::requireRole('kaprodi');
    }

    public function dashboard() {
        $skripsiModel = new Skripsi();
        $skripsiList = $skripsiModel->getAllWithDetails();
        $stats = $skripsiModel->getStats();
        require_once dirname(__DIR__, 2) . '/views/kaprodi/dashboard.php';
    }

    public function review() {
        $skripsiModel = new Skripsi();
        $skripsiList = $skripsiModel->getAllWithDetails();
        require_once dirname(__DIR__, 2) . '/views/kaprodi/review.php';
    }

    public function doReview() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=kaprodi_review');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $nilai = (int)($_POST['nilai'] ?? 0);
        $catatan = trim($_POST['catatan_review'] ?? '');
        $redirect = $_POST['redirect'] ?? 'kaprodi_review';

        if ($id > 0) {
            $skripsiModel = new Skripsi();
            $skripsiModel->updateReview($id, $nilai, $catatan);
            Auth::setFlash('success', 'Validasi skripsi berhasil disimpan.');
        }

        header("Location: index.php?page={$redirect}");
        exit;
    }

    public function laporan() {
        $skripsiModel = new Skripsi();
        $tahun = !empty($_GET['tahun']) ? (int)$_GET['tahun'] : null;
        $penjurusan = !empty($_GET['penjurusan']) ? trim($_GET['penjurusan']) : null;
        $laporanList = $skripsiModel->search('', $tahun, $penjurusan);
        require_once dirname(__DIR__, 2) . '/views/operator/laporan.php';
    }

    public function profile() {
        require_once dirname(__DIR__, 2) . '/views/mahasiswa/profile.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=profile');
            exit;
        }

        $user = Auth::user();
        $nama = trim($_POST['nama'] ?? $user['nama']);
        $namaProdi = trim($_POST['nama_prodi'] ?? ($user['nama_prodi'] ?? 'Ilmu Komputer'));
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $foto = $user['foto'] ?? 'default.png';
        if (!empty($_FILES['foto']['name'])) {
            try {
                $uploaded = Upload::profilePhoto($_FILES['foto']);
                if ($uploaded) {
                    $foto = $uploaded;
                }
            } catch (Exception $e) {
                Auth::setFlash('error', $e->getMessage());
                header('Location: index.php?page=profile');
                exit;
            }
        }

        $updateData = [
            'nama'       => $nama,
            'nama_prodi' => $namaProdi,
            'foto'       => $foto,
        ];

        if (!empty($password)) {
            if ($password !== $passwordConfirm) {
                Auth::setFlash('error', 'Konfirmasi password baru tidak cocok.');
                header('Location: index.php?page=profile');
                exit;
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $model = new Kaprodi();
        $model->updateProfile(Auth::id(), $updateData);

        $updatedUser = $model->find(Auth::id());
        Auth::login('kaprodi', $updatedUser);

        Auth::setFlash('success', 'Profil Kaprodi berhasil diperbarui.');
        header('Location: index.php?page=profile');
        exit;
    }
}
