<?php
// app/Controllers/LibraryController.php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__) . '/Helpers/Auth.php';
require_once dirname(__DIR__) . '/Models/Skripsi.php';

class LibraryController {
    public function index() {
        $skripsiModel = new Skripsi();
        $q = trim($_GET['q'] ?? '');
        $tahun = !empty($_GET['tahun']) ? (int)$_GET['tahun'] : null;
        $penjurusan = !empty($_GET['penjurusan']) ? trim($_GET['penjurusan']) : null;

        $skripsiList = $skripsiModel->search($q, $tahun, $penjurusan);
        require_once dirname(__DIR__, 2) . '/views/library/index.php';
    }

    public function detail() {
        $id = (int)($_GET['id'] ?? 0);
        $skripsiModel = new Skripsi();
        $skripsi = $skripsiModel->findWithDetails($id);

        if (!$skripsi) {
            Auth::setFlash('error', 'Dokumen skripsi tidak ditemukan.');
            header('Location: index.php?page=kumpulan_skripsi');
            exit;
        }

        require_once dirname(__DIR__, 2) . '/views/library/detail.php';
    }
}
