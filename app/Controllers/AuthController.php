<?php
// app/Controllers/AuthController.php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__) . '/Helpers/Auth.php';
require_once dirname(__DIR__) . '/Helpers/Upload.php';
require_once dirname(__DIR__) . '/Models/Operator.php';
require_once dirname(__DIR__) . '/Models/Mahasiswa.php';
require_once dirname(__DIR__) . '/Models/Kaprodi.php';

class AuthController {
    public function showLogin() {
        if (Auth::check()) {
            $this->redirectBasedOnRole(Auth::role());
            return;
        }
        require_once dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = strtolower(trim($_POST['role'] ?? 'mahasiswa'));

        if (empty($username) || empty($password)) {
            Auth::setFlash('error', 'Username dan password wajib diisi.');
            header("Location: index.php?page=login&role={$role}");
            exit;
        }

        $user = null;
        if ($role === 'operator') {
            $model = new Operator();
            $user = $model->findByUsername($username);
        } elseif ($role === 'kaprodi') {
            $model = new Kaprodi();
            $user = $model->findByUsername($username) ?? $model->findByNidn($username);
        } else {
            $role = 'mahasiswa';
            $model = new Mahasiswa();
            $user = $model->findByUsername($username) ?? $model->findByNim($username);
        }

        if (!$user) {
            Auth::setFlash('error', 'Akun tidak ditemukan untuk hak akses ' . strtoupper($role) . '. Silakan periksa kembali username/NIM Anda.');
            header("Location: index.php?page=login&role={$role}");
            exit;
        }

        // Check password (supports bcrypt password_verify and plaintext password fallback)
        $passwordValid = false;
        if (password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif ($password === $user['password']) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            Auth::setFlash('error', 'Password yang Anda masukkan salah.');
            header("Location: index.php?page=login&role={$role}");
            exit;
        }

        // Set login session
        Auth::login($role, $user);
        Auth::setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['nama']) . '!');
        $this->redirectBasedOnRole($role);
    }

    public function showRegister() {
        if (Auth::check()) {
            $this->redirectBasedOnRole(Auth::role());
            return;
        }
        require_once dirname(__DIR__, 2) . '/views/auth/register.php';
    }

    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=register');
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
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($nim) || empty($nama) || empty($username) || empty($password)) {
            Auth::setFlash('error', 'Harap lengkapi semua kolom wajib.');
            header('Location: index.php?page=register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            Auth::setFlash('error', 'Konfirmasi password tidak cocok.');
            header('Location: index.php?page=register');
            exit;
        }

        $model = new Mahasiswa();
        if ($model->findByNim($nim)) {
            Auth::setFlash('error', "NIM {$nim} sudah terdaftar dalam sistem.");
            header('Location: index.php?page=register');
            exit;
        }

        if ($model->findByUsername($username)) {
            Auth::setFlash('error', "Username '{$username}' sudah digunakan. Silakan pilih username lain.");
            header('Location: index.php?page=register');
            exit;
        }

        // Handle optional photo
        $foto = 'default.png';
        if (!empty($_FILES['foto']['name'])) {
            try {
                $uploaded = Upload::profilePhoto($_FILES['foto']);
                if ($uploaded) {
                    $foto = $uploaded;
                }
            } catch (Exception $e) {
                Auth::setFlash('error', $e->getMessage());
                header('Location: index.php?page=register');
                exit;
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $newId = $model->create([
            'nim'           => $nim,
            'nama'          => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'tgl_lahir'     => $tglLahir,
            'alamat'        => $alamat,
            'agama'         => $agama,
            'username'      => $username,
            'password'      => $hashedPassword,
            'foto'          => $foto,
            'akses'         => 'mahasiswa',
            'id_prodi'      => $idProdi,
        ]);

        if ($newId) {
            $user = $model->find($newId);
            Auth::login('mahasiswa', $user);
            Auth::setFlash('success', 'Registrasi berhasil! Akun Anda telah aktif.');
            header('Location: index.php?page=mahasiswa_dashboard');
            exit;
        } else {
            Auth::setFlash('error', 'Terjadi kesalahan sistem saat mendaftar.');
            header('Location: index.php?page=register');
            exit;
        }
    }

    public function logout() {
        Auth::logout();
        header('Location: index.php?page=login');
        exit;
    }

    private function redirectBasedOnRole(string $role): void {
        switch ($role) {
            case 'operator':
                header('Location: index.php?page=operator_dashboard');
                break;
            case 'kaprodi':
                header('Location: index.php?page=kaprodi_dashboard');
                break;
            case 'mahasiswa':
            default:
                header('Location: index.php?page=mahasiswa_dashboard');
                break;
        }
        exit;
    }
}
