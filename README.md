# E-SKRIPSI: Sistem Informasi Digital Library Skripsi Berbasis Website
### Studi Kasus: Perpustakaan FMIPA Universitas Pakuan (UNPAK)

> **Disusun Oleh:**  
> **Nama:** Faris Raja Ardana  
> **NPM:** 065120125  
> **Program Studi:** Ilmu Komputer  
> **Fakultas:** Matematika dan Ilmu Pengetahuan Alam (FMIPA)  
> **Universitas Pakuan - Bogor**

---

## 📖 Tentang Aplikasi

Aplikasi **E-SKRIPSI** adalah platform repositori dan perpustakaan digital (*digital library*) karya ilmiah skripsi berbasis web yang dirancang khusus untuk mempermudah civitas akademika di lingkungan **FMIPA Universitas Pakuan**.

Sistem ini mentransformasi proses pengarsipan skripsi konvensional (fisik rak buku) menjadi digital, mempermudah mahasiswa dalam mencari referensi tugas akhir, memungkinkan mahasiswa mengunggah skripsi mandiri dalam format PDF, memfasilitasi Ketua Program Studi (Kaprodi) dalam mereview dan memvalidasi kelayakan naskah, serta membantu staf perpustakaan (Operator) dalam mengelola dan mencetak laporan rekapitulasi skripsi.

---

## 🚀 Fitur Utama & Hak Akses (Multi-Role)

Sistem mengimplementasikan hak akses berbasis peran (*Role-Based Access Control*) yang terdiri dari:

### 1. 🎓 Mahasiswa
* **Registrasi & Login Akun:** Pendaftaran mandiri dengan validasi NIM dan Prodi.
* **Dashboard Mahasiswa:** Form unggah/kirim skripsi digital (NIM, Judul, Tahun, Format, Penjurusan, Dosen Pembimbing 1 & 2, serta file naskah PDF).
* **Lihat Hasil Review:** Memantau status pengajuan (*Pending*, *Disetujui*, atau *Perlu Revisi*) beserta catatan arahan dari Kaprodi/Operator.
* **Kumpulan Skripsi:** Pencarian dan eksplorasi katalog seluruh skripsi yang telah disetujui.
* **Edit Profile:** Pengelolaan informasi data diri dan kata sandi.

### 2. 👨‍🏫 Dosen / Kaprodi (Ketua Program Studi)
* **Dashboard Kaprodi:** Ringkasan statistik naskah skripsi masuk, disetujui, dan jumlah mahasiswa.
* **Validasi & Review Naskah:** Pemeriksaan berkas PDF skripsi mahasiswa dengan opsi status persetujuan (*Valid/Disetujui*, *Revisi*, *Menunggu*) dan form catatan evaluasi.
* **Laporan Skripsi:** Rekapitulasi karya skripsi mahasiswa per tahun dan penjurusan.

### 3. ⚙️ Operator / Admin (Staf Perpustakaan FMIPA)
* **Dashboard Operator:** Manajemen seluruh data skripsi mahasiswa (verifikasi, edit, unduh berkas PDF, hapus).
* **Kelola Data Mahasiswa:** Tambah mahasiswa manual, reset data, dan pemantauan akun.
* **Kelola Data Kaprodi:** Manajemen akun dosen/kaprodi.
* **Cetak Laporan Resmi:** Dilengkapi Kop Surat resmi FMIPA UNPAK, filter tahun akademik, peminatan/penjurusan, serta tanda tangan pengesahan siap cetak atau simpan ke PDF.

### 4. 📚 Katalog Digital Publik (*Public Search*)
* Pencarian cepat (*Live Search*) berdasarkan kata kunci, judul, nama mahasiswa, NIM, atau dosen pembimbing.
* Filter berdasarkan tahun kelulusan dan bidang peminatan (RPL, Jaringan, Data Mining/AI, dll).
* Detail naskah lengkap dengan **PDF Viewer bawaan** dan generator **Sitasi Otomatis (Format APA 7th Edition)** yang siap disalin.

---

## 🗄️ Struktur Basis Data (MySQL)

Sesuai dengan spesifikasi perancangan tabel pada laporan:
1. `operator`: Menyimpan data admin perpustakaan (`id`, `username`, `password`, `nama`, `image`, `akses`).
2. `mahasiswa`: Menyimpan data akun mahasiswa (`id`, `nim`, `nama`, `jenis_kelamin`, `tgl_lahir`, `alamat`, `agama`, `username`, `password`, `foto`, `akses`, `id_prodi`).
3. `kaprodi`: Menyimpan data kepala program studi (`id`, `nidn`, `nama_prodi`, `nama`, `username`, `password`, `foto`, `akses`).
4. `skripsi`: Menyimpan metadata karya ilmiah (`id`, `nim`, `judul`, `tahun`, `dokumen`, `format`, `penjurusan`, `dosbing`, `dosbing2`, `id_mahasiswa`, `id_prodi`, `nilai`, `catatan_review`).
5. `prodi`: Menyimpan daftar program studi FMIPA UNPAK.

---

## 🛠️ Persyaratan Sistem

* **PHP:** Versi 8.0, 8.1, atau 8.2+
* **Database:** MySQL 5.7+ atau MySQL 8.x / MariaDB
* **Web Server:** Apache / Nginx (melalui Laragon atau XAMPP) atau PHP Built-in Server

---

## 💻 Panduan Instalasi & Menjalankan

### Cara 1: Menggunakan Laragon / XAMPP (Rekomendasi)
1. Letakkan folder proyek `digital-library-unpak` ke dalam folder web server Anda:
   * **Laragon:** `C:\laragon\www\digital-library-unpak`
   * **XAMPP:** `C:\xampp\htdocs\digital-library-unpak`
2. Buka Laragon/XAMPP dan klik tombol **Start All** (pastikan Apache dan MySQL berjalan).
3. Buka browser dan akses alamat instalasi otomatis:
   ```
   http://localhost/digital-library-unpak/install.php?run=install
   ```
4. Sistem akan otomatis membuat database `digilib_unpak`, seluruh tabel, dan data awal (*seeds*).
5. Setelah selesai, buka aplikasi di:
   ```
   http://localhost/digital-library-unpak
   ```

### Cara 2: Menjalankan Langsung via Terminal / Command Prompt
1. Buka Terminal pada direktori proyek.
2. Jalankan perintah:
   ```bash
   php -S localhost:8000
   ```
3. Buka browser Anda dan akses:
   ```
   http://localhost:8000
   ```

---

## 🔑 Akun Login Uji Coba (Default)

| Role | Username / Identifier | Password | Nama Pengguna |
| :--- | :--- | :--- | :--- |
| **Mahasiswa** | `faris` (atau NIM: `065120125`) | `password123` | Faris Raja Ardana |
| **Kaprodi** | `kaprodi` (atau NIDN: `0412088501`) | `password123` | Arie Qur'ania, M.Kom. |
| **Operator** | `admin` | `password123` | Staf Perpustakaan FMIPA UNPAK |

*(Tersedia tombol pintas pada halaman login untuk mengisi kredensial di atas secara instan).*

---

## 📤 Panduan Push ke Repositori GitHub Anda

Jika Anda ingin menyimpan kode ini ke akun GitHub pribadi Anda (`https://github.com/<username-kamu>`):

1. Buat sebuah repository baru di akun GitHub Anda (misalnya beri nama `digital-library-unpak`). Biarkan repositori dalam keadaan kosong tanpa centang *Add a README file*.
2. Buka Terminal/PowerShell di folder proyek ini (`digital-library-unpak`), lalu jalankan perintah berikut secara berurutan:

```bash
# 1. Pastikan Anda berada di direktori proyek
git init

# 2. Hubungkan repository lokal dengan repository GitHub Anda
git remote add origin https://github.com/USERNAME_GITHUB_ANDA/digital-library-unpak.git

# 3. Ubah nama branch utama menjadi main
git branch -M main

# 4. Push seluruh kode dan riwayat commit ke GitHub
git push -u origin main
```

*(Ganti `USERNAME_GITHUB_ANDA` dengan username GitHub Anda yang sebenarnya).*
