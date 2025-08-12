# STRUKTUR DATABASE SIMAMANG

## 📋 OVERVIEW
Database SIMAMANG telah direstrukturisasi dengan memisahkan tabel untuk admin, pembimbing, dan siswa secara terpisah untuk organisasi yang lebih baik dan keamanan yang lebih tinggi.

## 🗄️ STRUKTUR TABEL

### 1. 📱 TABEL ADMIN
**Fungsi:** Menyimpan data administrator sistem
```sql
admin (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NULL,
    no_hp VARCHAR(15) NULL,
    alamat TEXT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### 2. 👨‍💼 TABEL PEMBIMBING
**Fungsi:** Menyimpan data pembimbing magang
```sql
pembimbing (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NULL,
    no_hp VARCHAR(15) NULL,
    alamat TEXT NULL,
    instansi VARCHAR(100) NULL,
    jabatan VARCHAR(100) NULL,
    bidang_keahlian TEXT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### 3. 👨‍🎓 TABEL SISWA
**Fungsi:** Menyimpan data siswa magang
```sql
siswa (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nis VARCHAR(20) NOT NULL UNIQUE,
    nisn VARCHAR(20) NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NULL,
    jenis_kelamin ENUM('L', 'P') NULL,
    alamat TEXT NULL,
    no_hp VARCHAR(15) NULL,
    email VARCHAR(100) NULL,
    kelas VARCHAR(10) NULL,
    jurusan VARCHAR(50) NULL,
    tempat_magang VARCHAR(100) NULL,
    alamat_magang TEXT NULL,
    periode_magang VARCHAR(50) NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### 4. 📝 TABEL LOG_AKTIVITAS
**Fungsi:** Menyimpan log kegiatan harian siswa
```sql
log_aktivitas (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT(11) UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    uraian TEXT NOT NULL,
    kegiatan VARCHAR(255) NULL,
    output TEXT NULL,
    hambatan TEXT NULL,
    solusi TEXT NULL,
    bukti VARCHAR(255) NULL,
    status ENUM('menunggu', 'disetujui', 'revisi', 'ditolak') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)
```

### 5. 💬 TABEL KOMENTAR_PEMBIMBING
**Fungsi:** Menyimpan komentar dan rating dari pembimbing
```sql
komentar_pembimbing (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    log_id INT(11) UNSIGNED NOT NULL,
    pembimbing_id INT(11) UNSIGNED NOT NULL,
    komentar TEXT NOT NULL,
    rating INT(1) NULL CHECK (rating >= 1 AND rating <= 5),
    status ENUM('pending', 'dibaca') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE,
    FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE CASCADE
)
```

### 6. 📚 TABEL LAPORAN_MAGANG
**Fungsi:** Menyimpan laporan akhir magang siswa
```sql
laporan_magang (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT(11) UNSIGNED NOT NULL,
    judul_laporan VARCHAR(255) NOT NULL,
    abstrak TEXT NULL,
    kata_kunci TEXT NULL,
    bab1_pendahuluan TEXT NULL,
    bab2_landasan_teori TEXT NULL,
    bab3_metodologi TEXT NULL,
    bab4_hasil_dan_pembahasan TEXT NULL,
    bab5_penutup TEXT NULL,
    daftar_pustaka TEXT NULL,
    lampiran TEXT NULL,
    file_laporan VARCHAR(255) NULL,
    status ENUM('draft', 'submitted', 'reviewed', 'approved', 'rejected') DEFAULT 'draft',
    tanggal_submit DATE NULL,
    tanggal_review DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)
```

### 7. 🔔 TABEL NOTIFIKASI
**Fungsi:** Sistem notifikasi untuk semua user
```sql
notifikasi (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    user_type ENUM('admin', 'pembimbing', 'siswa') NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    link VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

## 🔗 RELASI ANTAR TABEL

```
1. admin ←→ notifikasi (user_id, user_type='admin')
2. pembimbing ←→ komentar_pembimbing ←→ log_aktivitas ←→ siswa
3. siswa ←→ laporan_magang
4. pembimbing ←→ notifikasi (user_id, user_type='pembimbing')
5. siswa ←→ notifikasi (user_id, user_type='siswa')
```

## 👤 DATA SAMPLE YANG TERSEDIA

### 📱 ADMIN
- **Username:** `admin`, **Password:** `admin123`
- **Username:** `admin2`, **Password:** `admin123`

### 👨‍💼 PEMBIMBING
- **Username:** `pembimbing1`, **Password:** `pembimbing123`
- **Username:** `pembimbing2`, **Password:** `pembimbing123`
- **Username:** `pembimbing3`, **Password:** `pembimbing123`

### 👨‍🎓 SISWA
- **Username:** `siswa1`, **Password:** `siswa123`
- **Username:** `siswa2`, **Password:** `siswa123`
- **Username:** `siswa3`, **Password:** `siswa123`
- **Username:** `siswa4`, **Password:** `siswa123`
- **Username:** `siswa5`, **Password:** `siswa123`

## 🚀 KEUNTUNGAN STRUKTUR BARU

1. **🔒 Keamanan Tinggi:** Setiap role memiliki tabel terpisah
2. **📊 Organisasi Data:** Data lebih terstruktur dan mudah dikelola
3. **🔍 Query Efisien:** Pencarian user lebih cepat dan spesifik
4. **📈 Skalabilitas:** Mudah menambah field baru untuk setiap role
5. **🔄 Maintenance:** Backup dan restore lebih mudah
6. **👥 Role Management:** Manajemen hak akses lebih fleksibel

## 🛠️ IMPLEMENTASI

- ✅ Database MySQL berhasil dibuat
- ✅ Semua tabel berhasil dibuat dengan foreign key constraints
- ✅ Data sample berhasil diinsert
- ✅ Controller Auth berhasil diupdate untuk multiple tables
- ✅ Login system berhasil ditest dan berfungsi
- ✅ Aplikasi berjalan di `http://localhost:8080`
- ✅ Tabel pembimbing_siswa berhasil dihapus

## 📝 CATATAN

- Semua password di-hash menggunakan `password_hash()`
- Status user menggunakan ENUM untuk konsistensi
- Timestamp otomatis untuk created_at dan updated_at
- Foreign key constraints dengan CASCADE untuk integritas data
- Unique constraints untuk username dan NIS
- **Relasi pembimbing-siswa sekarang langsung melalui tabel komentar_pembimbing dan log_aktivitas**
