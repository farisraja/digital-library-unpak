<?php
// app/Controllers/OperatorController.php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__) . '/Helpers/Auth.php';
require_once dirname(__DIR__) . '/Helpers/Upload.php';
require_once dirname(__DIR__) . '/Models/Operator.php';
require_once dirname(__DIR__) . '/Models/Mahasiswa.php';
require_once dirname(__DIR__) . '/Models/Kaprodi.php';
require_once dirname(__DIR__) . '/Models/Skripsi.php';

class OperatorController {
    public function __construct() {
        Auth::requireRole('operator');
    }

    public function dashboard() {
        $skripsiModel = new Skripsi();
        $status = $_GET['status'] ?? null;
        $skripsiList = $skripsiModel->getAllWithDetails($status);
        $stats = $skripsiModel->getStats();
        require_once dirname(__DIR__, 2) . '/views/operator/dashboard.php';
    }

    public function mahasiswa() {
        $mhsModel = new Mahasiswa();
        $mahasiswaList = $mhsModel->getAllWithProdi();
        require_once dirname(__DIR__, 2) . '/views/operator/mahasiswa.php';
    }

    public function addMahasiswa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=operator_mahasiswa');
            exit;
        }

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $idProdi = (int)($_POST['id_prodi'] ?? 1);
        $jenisKelamin = $_POST['jenis_kelamin'] ?? 'Laki-laki';
        $tglLahir = $_POST['tgl_lahir'] ?? date('Y-m-d');
        $alamat = trim($_POST['alamat'] ?? '');
        $agama = trim($_POST['agama'] ?? 'Islam');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? 'password123';

        $model = new Mahasiswa();
        if ($model->findByNim($nim)) {
            Auth::setFlash('error', "NIM {$nim} sudah terdaftar.");
            header('Location: index.php?page=operator_mahasiswa');
            exit;
        }

        $model->create([
            'nim'           => $nim,
            'nama'          => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'tgl_lahir'     => $tglLahir,
            'alamat'        => $alamat,
            'agama'         => $agama,
            'username'      => $username,
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'foto'          => 'default.png',
            'akses'         => 'mahasiswa',
            'id_prodi'      => $idProdi,
        ]);

        Auth::setFlash('success', 'Mahasiswa baru berhasil ditambahkan.');
        header('Location: index.php?page=operator_mahasiswa');
        exit;
    }

    public function deleteMahasiswa() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $mhsModel = new Mahasiswa();
            $mhsModel->delete($id);
            Auth::setFlash('success', 'Data mahasiswa berhasil dihapus.');
        }
        header('Location: index.php?page=operator_mahasiswa');
        exit;
    }

    public function kaprodi() {
        $kaprodiModel = new Kaprodi();
        $kaprodiList = $kaprodiModel->all();
        require_once dirname(__DIR__, 2) . '/views/operator/kaprodi.php';
    }

    public function addKaprodi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=operator_kaprodi');
            exit;
        }

        $nidn = trim($_POST['nidn'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $namaProdi = trim($_POST['nama_prodi'] ?? 'Ilmu Komputer');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? 'password123';

        $model = new Kaprodi();
        if ($model->findByNidn($nidn)) {
            Auth::setFlash('error', "NIDN {$nidn} sudah terdaftar.");
            header('Location: index.php?page=operator_kaprodi');
            exit;
        }

        $model->create([
            'nidn'       => $nidn,
            'nama'       => $nama,
            'nama_prodi' => $namaProdi,
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'foto'       => 'default.png',
            'akses'      => 'kaprodi',
        ]);

        Auth::setFlash('success', 'Akun Kaprodi berhasil ditambahkan.');
        header('Location: index.php?page=operator_kaprodi');
        exit;
    }

    public function deleteKaprodi() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Kaprodi();
            $model->delete($id);
            Auth::setFlash('success', 'Akun Kaprodi berhasil dihapus.');
        }
        header('Location: index.php?page=operator_kaprodi');
        exit;
    }

    public function laporan() {
        $skripsiModel = new Skripsi();
        $tahun = !empty($_GET['tahun']) ? (int)$_GET['tahun'] : null;
        $penjurusan = !empty($_GET['penjurusan']) ? trim($_GET['penjurusan']) : null;
        $laporanList = $skripsiModel->search('', $tahun, $penjurusan);
        require_once dirname(__DIR__, 2) . '/views/operator/laporan.php';
    }

    public function deleteSkripsi() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $skripsiModel = new Skripsi();
            $skripsiModel->delete($id);
            Auth::setFlash('success', 'Data skripsi berhasil dihapus dari perpustakaan.');
        }
        header('Location: index.php?page=operator_dashboard');
        exit;
    }

    public function reviewSkripsi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=operator_dashboard');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $nilai = (int)($_POST['nilai'] ?? 0);
        $catatan = trim($_POST['catatan_review'] ?? '');
        $redirect = $_POST['redirect'] ?? 'operator_dashboard';

        if ($id > 0) {
            $skripsiModel = new Skripsi();
            $skripsiModel->updateReview($id, $nilai, $catatan);
            Auth::setFlash('success', 'Status validasi naskah skripsi berhasil diperbarui.');
        }

        header("Location: index.php?page={$redirect}");
        exit;
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
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $image = $user['image'] ?? 'default.png';
        if (!empty($_FILES['foto']['name'])) {
            try {
                $uploaded = Upload::profilePhoto($_FILES['foto']);
                if ($uploaded) {
                    $image = $uploaded;
                }
            } catch (Exception $e) {
                Auth::setFlash('error', $e->getMessage());
                header('Location: index.php?page=profile');
                exit;
            }
        }

        $updateData = [
            'nama'  => $nama,
            'image' => $image,
        ];

        if (!empty($password)) {
            if ($password !== $passwordConfirm) {
                Auth::setFlash('error', 'Konfirmasi password tidak cocok.');
                header('Location: index.php?page=profile');
                exit;
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $model = new Operator();
        $model->updateProfile(Auth::id(), $updateData);

        $updatedUser = $model->find(Auth::id());
        Auth::login('operator', $updatedUser);

        Auth::setFlash('success', 'Profil Operator berhasil diperbarui.');
        header('Location: index.php?page=profile');
        exit;
    }
}
