<?php
// app/Helpers/Auth.php

class Auth {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(string $role, array $userData): void {
        self::init();
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_role'] = $role;
        $_SESSION['user_data'] = $userData;
        $_SESSION['username'] = $userData['username'];
        $_SESSION['nama'] = $userData['nama'];
    }

    public static function check(): bool {
        self::init();
        return !empty($_SESSION['user_id']) && !empty($_SESSION['user_role']);
    }

    public static function user(): ?array {
        self::init();
        return $_SESSION['user_data'] ?? null;
    }

    public static function id(): ?int {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    public static function role(): ?string {
        self::init();
        return $_SESSION['user_role'] ?? null;
    }

    public static function name(): string {
        self::init();
        return $_SESSION['nama'] ?? 'Pengguna';
    }

    public static function logout(): void {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function requireRole($allowedRoles): void {
        self::init();
        if (!self::check()) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
            header('Location: index.php?page=login');
            exit;
        }

        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        if (!in_array(self::role(), $allowedRoles, true)) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki hak akses ke halaman tersebut.';
            header('Location: index.php');
            exit;
        }
    }

    public static function setFlash(string $type, string $message): void {
        self::init();
        $_SESSION['flash_' . $type] = $message;
    }

    public static function getFlash(string $type): ?string {
        self::init();
        if (isset($_SESSION['flash_' . $type])) {
            $msg = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            return $msg;
        }
        return null;
    }
}
