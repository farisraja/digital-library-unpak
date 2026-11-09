<?php
// app/Controllers/MahasiswaController.php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__) . '/Helpers/Auth.php';
require_once dirname(__DIR__) . '/Helpers/Upload.php';
require_once dirname(__DIR__) . '/Models/Mahasiswa.php';
require_once dirname(__DIR__) . '/Models/Skripsi.php';

class MahasiswaController {
    public function __construct() {
        Auth::requireRole('mahasiswa');
    }

    public function dashboard() {
        $skripsiModel = new Skripsi();
        $mySkripsiList = $skripsiModel->getByMahasiswaId(Auth::id());
        require_once dirname(__DIR__, 2) . '/views/mahasiswa/dashboard.php';
    }

    public function uploadSkripsi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=mahasiswa_dashboard');
            exit;
        }

        $user = Auth::user();
        $nim = $user['nim'];
        $judul = trim($_POST['judul'] ?? '');
        $tahun = (int)($_POST['tahun'] ?? date('Y'));
        $format = $_POST['format'] ?? 'PDF Format';
        $penjurusan = $_POST['penjurusan'] ?? 'Rekayasa Perangkat Lunak';
        $dosbing = trim($_POST['dosbing'] ?? '');
        $dosbing2 = trim($_POST['dosbing2'] ?? '');

        if (empty($judul) || empty($dosbing) || empty($dosbing2)) {
            Auth::setFlash('error', 'Semua kolom bertanda bintang wajib diisi.');
            header('Location: index.php?page=mahasiswa_dashboard');
            exit;
        }

        if (empty($_FILES['dokumen']['name'])) {
            Auth::setFlash('error', 'Harap lampirkan berkas naskah skripsi dalam format PDF.');
            header('Location: index.php?page=mahasiswa_dashboard');
            exit;
        }

        try {
            $dokumenFile = Upload::skripsi($_FILES['dokumen']);
            if (!$dokumenFile) {
                throw new Exception("Gagal mengunggah berkas PDF.");
            }

            $skripsiModel = new Skripsi();
            $skripsiModel->create([
                'nim'          => $nim,
                'judul'        => $judul,
                'tahun'        => $tahun,
                'dokumen'      => $dokumenFile,
                'format'       => $format,
                'penjurusan'   => $penjurusan,
                'dosbing'      => $dosbing,
                'dosbing2'     => $dosbing2,
                'id_mahasiswa' => Auth::id(),
                'id_prodi'     => $user['id_prodi'] ?? 1,
                'nilai'        => 0, // 0 = Menunggu Review
                'catatan_review' => 'Pengajuan naskah skripsi baru oleh mahasiswa. Menunggu review Kaprodi/Operator.'
            ]);

            Auth::setFlash('success', 'Naskah skripsi berhasil dikirim! Silakan pantau status review secara berkala.');
            header('Location: index.php?page=mahasiswa_review');
            exit;
        } catch (Exception $e) {
            Auth::setFlash('error', $e->getMessage());
            header('Location: index.php?page=mahasiswa_dashboard');
            exit;
        }
    }

    public function hasilReview() {
        $skripsiModel = new Skripsi();
        $mySkripsiList = $skripsiModel->getByMahasiswaId(Auth::id());
        require_once dirname(__DIR__, 2) . '/views/mahasiswa/hasil_review.php';
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
        $jenisKelamin = $_POST['jenis_kelamin'] ?? $user['jenis_kelamin'];
        $tglLahir = $_POST['tgl_lahir'] ?? $user['tgl_lahir'];
        $alamat = trim($_POST['alamat'] ?? $user['alamat']);
        $agama = trim($_POST['agama'] ?? $user['agama']);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $foto = $user['foto'];
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
            'nama'          => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'tgl_lahir'     => $tglLahir,
            'alamat'        => $alamat,
            'agama'         => $agama,
            'foto'          => $foto,
        ];

        if (!empty($password)) {
            if ($password !== $passwordConfirm) {
                Auth::setFlash('error', 'Konfirmasi password baru tidak cocok.');
                header('Location: index.php?page=profile');
                exit;
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $model = new Mahasiswa();
        $model->updateProfile(Auth::id(), $updateData);

        // Refresh session
        $updatedUser = $model->find(Auth::id());
        Auth::login('mahasiswa', $updatedUser);

        Auth::setFlash('success', 'Profil Anda berhasil diperbarui!');
        header('Location: index.php?page=profile');
        exit;
    }
}
