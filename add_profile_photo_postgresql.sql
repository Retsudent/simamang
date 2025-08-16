-- =====================================================
-- SCRIPT SQL UNTUK MENAMBAHKAN FIELD FOTO PROFIL
-- SIMAMANG - Sistem Monitoring Aktivitas Magang
-- DATABASE: PostgreSQL
-- =====================================================

-- Pastikan database yang aktif adalah 'simamang'
-- \c simamang;

-- =====================================================
-- 1. TAMBAHKAN FIELD FOTO_PROFIL KE TABEL ADMIN
-- =====================================================

-- Cek apakah field foto_profil sudah ada
SELECT column_name 
FROM information_schema.columns 
WHERE table_name = 'admin' 
  AND column_name = 'foto_profil';

-- Jika field tidak ada, tambahkan
ALTER TABLE admin 
ADD COLUMN foto_profil VARCHAR(255);

-- Verifikasi field sudah ditambahkan
\d admin;

-- =====================================================
-- 2. TAMBAHKAN FIELD FOTO_PROFIL KE TABEL PEMBIMBING
-- =====================================================

-- Cek apakah field foto_profil sudah ada
SELECT column_name 
FROM information_schema.columns 
WHERE table_name = 'pembimbing' 
  AND column_name = 'foto_profil';

-- Jika field tidak ada, tambahkan
ALTER TABLE pembimbing 
ADD COLUMN foto_profil VARCHAR(255);

-- Verifikasi field sudah ditambahkan
\d pembimbing;

-- =====================================================
-- 3. TAMBAHKAN FIELD FOTO_PROFIL KE TABEL SISWA
-- =====================================================

-- Cek apakah field foto_profil sudah ada
SELECT column_name 
FROM information_schema.columns 
WHERE table_name = 'siswa' 
  AND column_name = 'foto_profil';

-- Jika field tidak ada, tambahkan
ALTER TABLE siswa 
ADD COLUMN foto_profil VARCHAR(255);

-- Verifikasi field sudah ditambahkan
\d siswa;

-- =====================================================
-- 4. VERIFIKASI SEMUA FIELD SUDAH DITAMBAHKAN
-- =====================================================

-- Cek struktur semua tabel user
SELECT 
    table_name,
    column_name,
    data_type,
    is_nullable,
    column_default
FROM information_schema.columns 
WHERE table_name IN ('admin', 'pembimbing', 'siswa')
  AND column_name = 'foto_profil'
ORDER BY table_name;

-- =====================================================
-- 5. UPDATE FIELD UPDATED_AT (OPSIONAL)
-- =====================================================

-- Jika field updated_at belum ada, tambahkan
-- Admin table
ALTER TABLE admin 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Pembimbing table  
ALTER TABLE pembimbing 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Siswa table
ALTER TABLE siswa 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- =====================================================
-- 6. VERIFIKASI FINAL
-- =====================================================

-- Tampilkan struktur lengkap semua tabel user
SELECT 'ADMIN TABLE' as table_info;
\d admin;

SELECT 'PEMBIMBING TABLE' as table_info;
\d pembimbing;

SELECT 'SISWA TABLE' as table_info;
\d siswa;

-- =====================================================
-- PESAN SUKSES
-- =====================================================
SELECT 
    '🎉 MIGRATION BERHASIL!' as status,
    'Field foto_profil telah ditambahkan ke semua tabel user' as message,
    NOW() as completed_at;

-- =====================================================
-- CATATAN PENGGUNAAN
-- =====================================================
/*
SETELAH MENJALANKAN SCRIPT INI:

1. ✅ Field foto_profil sudah tersedia di semua tabel user
2. ✅ User dapat mengupload foto profil melalui fitur /profile
3. ✅ Foto akan disimpan di folder writable/uploads/profile/
4. ✅ Fitur profil sudah siap digunakan

UNTUK TESTING:
1. Login ke sistem SIMAMANG
2. Akses menu "Profil Saya" 
3. Upload foto profil
4. Edit informasi profil
5. Ganti password

FITUR YANG TERSEDIA:
- Lihat profil lengkap
- Edit informasi profil
- Upload/ganti foto profil  
- Ganti password
- Validasi input
- File upload security

CATATAN POSTGRESQL:
- Gunakan \d nama_tabel untuk melihat struktur tabel
- Gunakan \c nama_database untuk pindah database
- Field VARCHAR(255) di PostgreSQL sama dengan MySQL
*/
