-- Script SQL untuk memperbaiki masalah database profil
-- Jalankan script ini di database MySQL/MariaDB

-- 1. Tambahkan field foto_profil ke tabel pembimbing jika belum ada
ALTER TABLE pembimbing ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat;

-- 2. Tambahkan field foto_profil ke tabel siswa jika belum ada
ALTER TABLE siswa ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat;

-- 3. Tambahkan field foto_profil ke tabel admin jika belum ada
ALTER TABLE admin ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat;

-- 4. Periksa struktur tabel pembimbing
DESCRIBE pembimbing;

-- 5. Periksa data pembimbing yang ada
SELECT id, nama, username, email, no_hp, alamat, foto_profil, status, created_at 
FROM pembimbing 
ORDER BY id;

-- 6. Periksa apakah ada data pembimbing
SELECT COUNT(*) as total_pembimbing FROM pembimbing;

-- 7. Periksa session data yang mungkin bermasalah
-- Catatan: Session disimpan di aplikasi, bukan di database
-- Pastikan user_id di session sesuai dengan ID di tabel pembimbing

-- 8. Jika tabel pembimbing kosong, buat data sample
INSERT INTO pembimbing (nama, username, password, email, no_hp, alamat, instansi, jabatan, bidang_keahlian, status, created_at, updated_at) 
VALUES 
('Pembimbing Test 1', 'pembimbing1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pembimbing1@test.com', '08123456789', 'Jl. Test No. 1', 'Instansi Test', 'Jabatan Test', 'Bidang Test', 'aktif', NOW(), NOW()),
('Pembimbing Test 2', 'pembimbing2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pembimbing2@test.com', '08123456790', 'Jl. Test No. 2', 'Instansi Test 2', 'Jabatan Test 2', 'Bidang Test 2', 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 9. Periksa hasil setelah insert
SELECT id, nama, username, email, status FROM pembimbing ORDER BY id;

