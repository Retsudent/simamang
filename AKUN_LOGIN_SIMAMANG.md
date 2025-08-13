# 🔐 AKUN LOGIN SIMAMANG

## 📋 Daftar Akun yang Tersedia

### 👨‍💼 **AKUN ADMIN**

#### 1. Admin Default
- **Username**: `admin`
- **Password**: `admin123`
- **Nama**: Administrator SIMAMANG
- **Status**: Aktif
- **Fitur**: Full access ke semua menu admin

#### 2. Super Admin
- **Username**: `superadmin`
- **Password**: `superadmin2024`
- **Nama**: Super Administrator
- **Status**: Aktif
- **Fitur**: Full access ke semua menu admin

---

### 👨‍🏫 **AKUN PEMBIMBING**

#### 1. Pembimbing 1
- **Username**: `pembimbing1`
- **Password**: `pembimbing123`
- **Nama**: Pak Ahmad
- **Email**: ahmad@email.com
- **Status**: Aktif

#### 2. Pembimbing 2
- **Username**: `pembimbing2`
- **Password**: `pembimbing123`
- **Nama**: Bu Sarah
- **Email**: sarah@email.com
- **Status**: Aktif

---

### 👨‍🎓 **AKUN SISWA**

#### 1. Siswa 1
- **Username**: `siswa1`
- **Password**: `siswa123`
- **Nama**: Budi Santoso
- **NIS**: 2024001
- **Tempat Magang**: PT. Teknologi Maju
- **Status**: Aktif

#### 2. Siswa 2
- **Username**: `siswa2`
- **Password**: `siswa123`
- **Nama**: Siti Nurhaliza
- **NIS**: 2024002
- **Tempat Magang**: CV. Digital Solutions
- **Status**: Aktif

#### 3. Siswa 3
- **Username**: `siswa3`
- **Password**: `siswa123`
- **Nama**: Ahmad Rizki
- **NIS**: 2024003
- **Tempat Magang**: PT. Inovasi Digital
- **Status**: Aktif

---

## 🚀 **CARA LOGIN**

### 1. Akses Aplikasi
```
URL: http://localhost:8080
```

### 2. Masukkan Kredensial
- Pilih salah satu akun di atas
- Masukkan username dan password
- Klik tombol "Masuk ke SIMAMANG"

### 3. Dashboard Sesuai Role
- **Admin**: Dashboard admin dengan menu kelola data
- **Pembimbing**: Dashboard pembimbing dengan menu review
- **Siswa**: Dashboard siswa dengan menu input log

---

## 🎯 **FITUR SETIAP ROLE**

### 👨‍💼 **ADMIN**
- ✅ Kelola data siswa (CRUD)
- ✅ Kelola data pembimbing (CRUD)
- ✅ Lihat laporan magang semua siswa
- ✅ Export data dalam format PDF
- ✅ Dashboard dengan statistik lengkap

### 👨‍🏫 **PEMBIMBING**
- ✅ Lihat aktivitas semua siswa
- ✅ Review dan komentar log siswa
- ✅ Validasi status (Disetujui/Revisi)
- ✅ Download bukti aktivitas
- ✅ Dashboard dengan log menunggu review

### 👨‍🎓 **SISWA**
- ✅ Input log aktivitas harian
- ✅ Upload bukti aktivitas (PDF, JPG, PNG, DOC, DOCX)
- ✅ Lihat riwayat aktivitas
- ✅ Lihat komentar pembimbing
- ✅ Generate laporan PDF pribadi

---

## 🔧 **TROUBLESHOOTING**

### Jika Login Gagal:
1. **Pastikan server berjalan**:
   ```bash
   php spark serve
   ```

2. **Pastikan database terhubung**:
   - PostgreSQL berjalan
   - Database 'simamang' sudah dibuat
   - Kredensial database benar

3. **Reset database jika perlu**:
   ```bash
   php reset_database.php
   php create_sample_data_postgresql.php
   php add_logs_with_fixed_names.php
   ```

### Jika Lupa Password:
1. **Admin**: Jalankan script create admin baru
2. **Pembimbing/Siswa**: Reset database dan buat ulang sample data

---

## 📊 **SAMPLE DATA YANG TERSEDIA**

### Log Aktivitas Sample:
- ✅ 5 log aktivitas untuk siswa1
- ✅ 2 log dengan bukti file (PDF & PNG)
- ✅ Komentar pembimbing untuk log yang disetujui

### File Bukti Sample:
- ✅ `bukti_aktivitas_sample.pdf`
- ✅ `screenshot_sample.png`
- ✅ `laporan_aktivitas_sample.doc`

---

## 💡 **TIPS PENGGUNAAN**

### Untuk Testing:
1. **Login sebagai Admin** untuk melihat fitur manajemen
2. **Login sebagai Pembimbing** untuk review log siswa
3. **Login sebagai Siswa** untuk input log dan lihat komentar

### Untuk Demo:
1. **Siswa** input log aktivitas
2. **Pembimbing** review dan beri komentar
3. **Admin** lihat laporan dan kelola data

---

## 🔒 **KEAMANAN**

### Password Policy:
- ✅ Semua password di-hash dengan bcrypt
- ✅ Session management yang aman
- ✅ CSRF protection aktif
- ✅ Input validation dan sanitization

### Rekomendasi:
- 🔄 Ganti password default setelah login pertama
- 🔒 Jangan bagikan kredensial
- 💾 Backup database secara berkala
- 🔍 Monitor log aktivitas

---

## 📞 **SUPPORT**

Jika mengalami masalah:
1. Periksa error log di `writable/logs/`
2. Pastikan semua dependency terinstall
3. Restart server jika diperlukan
4. Hubungi administrator sistem

---

**🎉 SELAMAT MENGGUNAKAN SIMAMANG!**
