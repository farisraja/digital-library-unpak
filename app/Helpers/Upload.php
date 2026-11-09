<?php
// app/Helpers/Upload.php

class Upload {
    public static function skripsi(array $file): ?string {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedExts = ['pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts, true)) {
            throw new Exception("Format berkas harus berupa dokumen PDF (.pdf)");
        }

        // Limit size: 30MB
        if ($file['size'] > 30 * 1024 * 1024) {
            throw new Exception("Ukuran berkas skripsi maksimal adalah 30MB.");
        }

        $uploadDir = dirname(__DIR__, 2) . '/uploads/skripsi/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'skripsi_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception("Gagal mengunggah berkas ke server.");
        }

        return $filename;
    }

    public static function profilePhoto(array $file): ?string {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts, true)) {
            throw new Exception("Format foto harus JPG, PNG, atau WebP.");
        }

        // Limit size: 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("Ukuran foto maksimal adalah 5MB.");
        }

        $uploadDir = dirname(__DIR__, 2) . '/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'user_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception("Gagal mengunggah foto profil.");
        }

        return $filename;
    }
}
