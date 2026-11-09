<?php
// app/Models/Skripsi.php
require_once __DIR__ . '/Model.php';

class Skripsi extends Model {
    protected string $table = 'skripsi';

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO skripsi (nim, judul, tahun, dokumen, format, penjurusan, dosbing, dosbing2, id_mahasiswa, id_prodi, nilai, catatan_review)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nim'],
            $data['judul'],
            $data['tahun'],
            $data['dokumen'],
            $data['format'] ?? 'PDF Format',
            $data['penjurusan'],
            $data['dosbing'],
            $data['dosbing2'],
            $data['id_mahasiswa'],
            $data['id_prodi'] ?? 1,
            $data['nilai'] ?? 0,
            $data['catatan_review'] ?? null
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateSkripsi(int $id, array $data): bool {
        $fields = [
            'judul = ?',
            'tahun = ?',
            'format = ?',
            'penjurusan = ?',
            'dosbing = ?',
            'dosbing2 = ?'
        ];
        $params = [
            $data['judul'],
            $data['tahun'],
            $data['format'],
            $data['penjurusan'],
            $data['dosbing'],
            $data['dosbing2']
        ];

        if (!empty($data['dokumen'])) {
            $fields[] = 'dokumen = ?';
            $params[] = $data['dokumen'];
        }

        $params[] = $id;
        $sql = "UPDATE skripsi SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateReview(int $id, int $nilai, ?string $catatan): bool {
        $stmt = $this->db->prepare("
            UPDATE skripsi SET nilai = ?, catatan_review = ? WHERE id = ?
        ");
        return $stmt->execute([$nilai, $catatan, $id]);
    }

    public function getAllWithDetails(?string $filterStatus = null): array {
        $sql = "
            SELECT s.*, m.nama AS nama_mahasiswa, p.nama_prodi
            FROM skripsi s
            LEFT JOIN mahasiswa m ON s.id_mahasiswa = m.id
            LEFT JOIN prodi p ON s.id_prodi = p.id
        ";
        if ($filterStatus !== null && $filterStatus !== '') {
            $sql .= " WHERE s.nilai = " . intval($filterStatus);
        }
        $sql .= " ORDER BY s.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getByMahasiswaId(int $mahasiswaId): array {
        $stmt = $this->db->prepare("
            SELECT s.*, m.nama AS nama_mahasiswa, p.nama_prodi
            FROM skripsi s
            LEFT JOIN mahasiswa m ON s.id_mahasiswa = m.id
            LEFT JOIN prodi p ON s.id_prodi = p.id
            WHERE s.id_mahasiswa = ?
            ORDER BY s.id DESC
        ");
        $stmt->execute([$mahasiswaId]);
        return $stmt->fetchAll();
    }

    public function findWithDetails(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT s.*, m.nama AS nama_mahasiswa, m.foto AS foto_mahasiswa, p.nama_prodi
            FROM skripsi s
            LEFT JOIN mahasiswa m ON s.id_mahasiswa = m.id
            LEFT JOIN prodi p ON s.id_prodi = p.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function search(string $query = '', ?int $tahun = null, ?string $penjurusan = null): array {
        $sql = "
            SELECT s.*, m.nama AS nama_mahasiswa, p.nama_prodi
            FROM skripsi s
            LEFT JOIN mahasiswa m ON s.id_mahasiswa = m.id
            LEFT JOIN prodi p ON s.id_prodi = p.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (s.judul LIKE ? OR s.nim LIKE ? OR m.nama LIKE ? OR s.dosbing LIKE ? OR s.dosbing2 LIKE ?)";
            $q = "%{$query}%";
            $params = array_merge($params, [$q, $q, $q, $q, $q]);
        }

        if (!empty($tahun)) {
            $sql .= " AND s.tahun = ?";
            $params[] = $tahun;
        }

        if (!empty($penjurusan)) {
            $sql .= " AND s.penjurusan = ?";
            $params[] = $penjurusan;
        }

        $sql .= " ORDER BY s.tahun DESC, s.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getStats(): array {
        $total = $this->db->query("SELECT COUNT(*) FROM skripsi")->fetchColumn();
        $approved = $this->db->query("SELECT COUNT(*) FROM skripsi WHERE nilai = 1")->fetchColumn();
        $pending = $this->db->query("SELECT COUNT(*) FROM skripsi WHERE nilai = 0")->fetchColumn();
        $revision = $this->db->query("SELECT COUNT(*) FROM skripsi WHERE nilai = 2")->fetchColumn();
        $mahasiswaCount = $this->db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();

        // Data statistik per penjurusan
        $stmtPenjurusan = $this->db->query("
            SELECT penjurusan, COUNT(*) as total 
            FROM skripsi 
            GROUP BY penjurusan 
            ORDER BY total DESC
        ");
        $byPenjurusan = $stmtPenjurusan->fetchAll();

        // Data statistik per tahun
        $stmtTahun = $this->db->query("
            SELECT tahun, COUNT(*) as total 
            FROM skripsi 
            GROUP BY tahun 
            ORDER BY tahun ASC
        ");
        $byTahun = $stmtTahun->fetchAll();

        return [
            'total'         => (int) $total,
            'approved'      => (int) $approved,
            'pending'       => (int) $pending,
            'revision'      => (int) $revision,
            'mahasiswa'     => (int) $mahasiswaCount,
            'by_penjurusan' => $byPenjurusan,
            'by_tahun'      => $byTahun,
        ];
    }
}
