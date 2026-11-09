<?php
// app/Models/Kaprodi.php
require_once __DIR__ . '/Model.php';

class Kaprodi extends Model {
    protected string $table = 'kaprodi';

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM kaprodi WHERE username = ?");
        $stmt->execute([$username]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function findByNidn(string $nidn): ?array {
        $stmt = $this->db->prepare("SELECT * FROM kaprodi WHERE nidn = ?");
        $stmt->execute([$nidn]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO kaprodi (nidn, nama_prodi, nama, username, password, foto, akses)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nidn'],
            $data['nama_prodi'],
            $data['nama'],
            $data['username'],
            $data['password'],
            $data['foto'] ?? 'default.png',
            $data['akses'] ?? 'kaprodi'
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE kaprodi SET nama = ?, nama_prodi = ?, password = ?, foto = ? WHERE id = ?
            ");
            return $stmt->execute([$data['nama'], $data['nama_prodi'], $data['password'], $data['foto'], $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE kaprodi SET nama = ?, nama_prodi = ?, foto = ? WHERE id = ?
            ");
            return $stmt->execute([$data['nama'], $data['nama_prodi'], $data['foto'], $id]);
        }
    }
}
