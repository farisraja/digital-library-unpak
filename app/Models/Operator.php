<?php
// app/Models/Operator.php
require_once __DIR__ . '/Model.php';

class Operator extends Model {
    protected string $table = 'operator';

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM operator WHERE username = ?");
        $stmt->execute([$username]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO operator (username, password, nama, image, akses)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['username'],
            $data['password'],
            $data['nama'],
            $data['image'] ?? 'default.png',
            $data['akses'] ?? 'admin'
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE operator SET nama = ?, password = ?, image = ? WHERE id = ?
            ");
            return $stmt->execute([$data['nama'], $data['password'], $data['image'], $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE operator SET nama = ?, image = ? WHERE id = ?
            ");
            return $stmt->execute([$data['nama'], $data['image'], $id]);
        }
    }
}
