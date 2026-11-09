<?php
// index.php - Master Router & Entry Point
// E-SKRIPSI Digital Library FMIPA Universitas Pakuan

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/Auth.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/app/Controllers/OperatorController.php';
require_once __DIR__ . '/app/Controllers/KaprodiController.php';
require_once __DIR__ . '/app/Controllers/LibraryController.php';

Auth::init();

// Quick Dev Preview Auto-login for local screenshot rendering
if (!Auth::check() && isset($_GET['preview_as']) && in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'])) {
    $role = $_GET['preview_as'];
    if ($role === 'mahasiswa') {
        $u = (new Mahasiswa())->findByUsername('faris');
        if ($u) Auth::login('mahasiswa', $u);
    } elseif ($role === 'operator') {
        $u = (new Operator())->findByUsername('admin');
        if ($u) Auth::login('operator', $u);
    } elseif ($role === 'kaprodi') {
        $u = (new Kaprodi())->findByUsername('kaprodi');
        if ($u) Auth::login('kaprodi', $u);
    }
}

$action = $_GET['action'] ?? null;
$page = $_GET['page'] ?? null;

// ==========================================
// ACTION DISPATCHER (POST / Form Submissions)
// ==========================================
if ($action !== null) {
    switch ($action) {
        case 'do_login':
            (new AuthController())->doLogin();
            break;

        case 'do_register':
            (new AuthController())->doRegister();
            break;

        case 'do_upload_skripsi':
            (new MahasiswaController())->uploadSkripsi();
            break;

        case 'do_update_profile':
            $role = Auth::role();
            if ($role === 'operator') {
                (new OperatorController())->updateProfile();
            } elseif ($role === 'kaprodi') {
                (new KaprodiController())->updateProfile();
            } else {
                (new MahasiswaController())->updateProfile();
            }
            break;

        case 'do_add_mahasiswa':
            (new OperatorController())->addMahasiswa();
            break;

        case 'delete_mahasiswa':
            (new OperatorController())->deleteMahasiswa();
            break;

        case 'do_add_kaprodi':
            (new OperatorController())->addKaprodi();
            break;

        case 'delete_kaprodi':
            (new OperatorController())->deleteKaprodi();
            break;

        case 'delete_skripsi':
            (new OperatorController())->deleteSkripsi();
            break;

        case 'do_review_skripsi':
            $role = Auth::role();
            if ($role === 'kaprodi') {
                (new KaprodiController())->doReview();
            } else {
                (new OperatorController())->reviewSkripsi();
            }
            break;

        default:
            header('Location: index.php');
            exit;
    }
    exit;
}

// ==========================================
// PAGE DISPATCHER (Views / Routing)
// ==========================================
switch ($page) {
    case 'login':
        (new AuthController())->showLogin();
        break;

    case 'register':
        (new AuthController())->showRegister();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'kumpulan_skripsi':
        (new LibraryController())->index();
        break;

    case 'skripsi_detail':
        (new LibraryController())->detail();
        break;

    case 'profile':
        if (!Auth::check()) {
            header('Location: index.php?page=login');
            exit;
        }
        $role = Auth::role();
        if ($role === 'operator') {
            (new OperatorController())->profile();
        } elseif ($role === 'kaprodi') {
            (new KaprodiController())->profile();
        } else {
            (new MahasiswaController())->profile();
        }
        break;

    // Mahasiswa routes
    case 'mahasiswa_dashboard':
        (new MahasiswaController())->dashboard();
        break;

    case 'mahasiswa_review':
        (new MahasiswaController())->hasilReview();
        break;

    // Operator routes
    case 'operator_dashboard':
        (new OperatorController())->dashboard();
        break;

    case 'operator_mahasiswa':
        (new OperatorController())->mahasiswa();
        break;

    case 'operator_kaprodi':
        (new OperatorController())->kaprodi();
        break;

    case 'operator_laporan':
        (new OperatorController())->laporan();
        break;

    // Kaprodi routes
    case 'kaprodi_dashboard':
        (new KaprodiController())->dashboard();
        break;

    case 'kaprodi_review':
        (new KaprodiController())->review();
        break;

    case 'kaprodi_laporan':
        (new KaprodiController())->laporan();
        break;

    default:
        // Default Landing Page: If logged in, go to role dashboard, else go to login page
        if (Auth::check()) {
            $role = Auth::role();
            if ($role === 'operator') {
                header('Location: index.php?page=operator_dashboard');
            } elseif ($role === 'kaprodi') {
                header('Location: index.php?page=kaprodi_dashboard');
            } else {
                header('Location: index.php?page=mahasiswa_dashboard');
            }
        } else {
            (new AuthController())->showLogin();
        }
        break;
}
