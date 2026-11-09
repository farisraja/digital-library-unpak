<?php
// app/Models/Mahasiswa.php
require_once __DIR__ . '/Model.php';

class Mahasiswa extends Model {
    protected string $table = 'mahasiswa';

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE username = ?");
        $stmt->execute([$username]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function findByNim(string $nim): ?array {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO mahasiswa (nim, nama, jenis_kelamin, tgl_lahir, alamat, agama, username, password, foto, akses, id_prodi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['jenis_kelamin'],
            $data['tgl_lahir'],
            $data['alamat'],
            $data['agama'],
            $data['username'],
            $data['password'],
            $data['foto'] ?? 'default.png',
            $data['akses'] ?? 'mahasiswa',
            $data['id_prodi'] ?? 1
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE mahasiswa 
                SET nama = ?, jenis_kelamin = ?, tgl_lahir = ?, alamat = ?, agama = ?, password = ?, foto = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['nama'],
                $data['jenis_kelamin'],
                $data['tgl_lahir'],
                $data['alamat'],
                $data['agama'],
                $data['password'],
                $data['foto'],
                $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE mahasiswa 
                SET nama = ?, jenis_kelamin = ?, tgl_lahir = ?, alamat = ?, agama = ?, foto = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['nama'],
                $data['jenis_kelamin'],
                $data['tgl_lahir'],
                $data['alamat'],
                $data['agama'],
                $data['foto'],
                $id
            ]);
        }
    }

    public function getAllWithProdi(): array {
        $sql = "
            SELECT m.*, p.nama_prodi 
            FROM mahasiswa m
            LEFT JOIN prodi p ON m.id_prodi = p.id
            ORDER BY m.id DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
