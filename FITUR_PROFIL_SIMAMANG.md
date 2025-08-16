# 🎯 FITUR PROFIL SIMAMANG

## 📋 OVERVIEW
Fitur profil memungkinkan semua user (admin, pembimbing, siswa) untuk melihat dan mengelola profil mereka, termasuk upload foto profil.

## ✨ FITUR YANG TERSEDIA

### 🔍 Lihat Profil
- Tampilan lengkap informasi user
- Foto profil (jika sudah diupload)
- Informasi dasar: nama, username, email, no HP, alamat
- Informasi khusus berdasarkan role:
  - **Siswa**: NIS, kelas, jurusan, tempat magang, dll
  - **Pembimbing**: instansi, jabatan, bidang keahlian
  - **Admin**: informasi dasar admin

### 📝 Edit Profil
- Edit informasi personal
- Update data sesuai role
- Validasi input
- Feedback real-time

### 📸 Upload Foto Profil
- Upload foto profil baru
- Validasi tipe file (JPG, PNG, GIF)
- Validasi ukuran file (max 2MB)
- Auto-replace foto lama
- Preview foto sebelum upload

### 🔐 Ganti Password
- Verifikasi password lama
- Input password baru
- Konfirmasi password
- Validasi keamanan

## 🗄️ STRUKTUR DATABASE

### Field Baru yang Ditambahkan
```sql
-- Tabel admin, pembimbing, siswa
ALTER TABLE [nama_tabel] ADD COLUMN foto_profil VARCHAR(255) NULL AFTER alamat;
```

### Struktur Folder Upload
```
writable/
└── uploads/
    ├── bukti/          # Bukti log aktivitas
    └── profile/        # Foto profil user (BARU)
```

## 🚀 CARA MENGGUNAKAN

### 1. Akses Fitur Profil
- Login ke sistem
- Klik menu "Profil Saya" di sidebar
- Atau klik avatar user di pojok kanan atas → "Profil Saya"

### 2. Upload Foto Profil
- Buka halaman profil
- Klik tombol "Upload Foto Baru"
- Pilih file foto (JPG/PNG/GIF, max 2MB)
- Klik "Upload"

### 3. Edit Profil
- Di halaman profil, klik "Edit Profil"
- Ubah informasi yang diinginkan
- Klik "Simpan Perubahan"

### 4. Ganti Password
- Di halaman profil, klik "Ganti Password"
- Masukkan password lama
- Masukkan password baru
- Konfirmasi password baru
- Klik "Ganti Password"

## 📁 FILE YANG DIBUAT/DIMODIFIKASI

### Controllers
- `app/Controllers/Profile.php` - Controller utama fitur profil

### Views
- `app/Views/profile/index.php` - Halaman tampilan profil
- `app/Views/profile/edit.php` - Halaman edit profil

### Database
- `app/Database/Migrations/AddProfilePhoto.php` - Migration untuk field foto

### Routes
- `app/Config/Routes.php` - Route untuk fitur profil

### Layout
- `app/Views/layouts/main.php` - Layout utama dengan menu profil

### Scripts
- `add_profile_photo_simple.php` - Script untuk menambah field foto
- `run_profile_migration.php` - Script migration lengkap

## 🔧 INSTALASI & SETUP

### 1. Jalankan Migration
```bash
# Opsi 1: Menggunakan script sederhana
php add_profile_photo_simple.php

# Opsi 2: Menggunakan CodeIgniter migration
php spark migrate
```

### 2. Buat Folder Upload
```bash
mkdir -p writable/uploads/profile
chmod 755 writable/uploads/profile
```

### 3. Pastikan Helper Tersedia
File `app/Config/Autoload.php` sudah diupdate dengan:
```php
public $helpers = ['form', 'url', 'text'];
```

## 🌐 ROUTES YANG TERSEDIA

### Profile Routes
```
GET  /profile              - Tampilkan profil
GET  /profile/edit         - Form edit profil
POST /profile/update       - Update profil
POST /profile/update-photo - Upload foto profil
POST /profile/change-password - Ganti password
```

### Upload Routes
```
GET /uploads/profile/{filename} - Akses foto profil
```

## 🎨 FITUR UI/UX

### Responsive Design
- Layout responsive untuk mobile dan desktop
- Sidebar yang bisa di-collapse
- Grid system Bootstrap 5

### User Experience
- Breadcrumb navigation
- Flash messages untuk feedback
- Modal untuk upload foto dan ganti password
- Validasi form real-time
- Loading states

### Visual Elements
- Foto profil circular dengan fallback avatar
- Color-coded role badges
- Icon Bootstrap untuk setiap menu
- Gradient buttons dan cards

## 🔒 KEAMANAN

### File Upload Security
- Validasi tipe file (whitelist)
- Validasi ukuran file
- Random filename generation
- File storage di folder writable

### Authentication
- Session-based authentication
- Role-based access control
- CSRF protection (built-in CodeIgniter)
- Password hashing

### Data Validation
- Server-side validation
- Input sanitization
- SQL injection prevention
- XSS protection

## 🧪 TESTING

### Test Cases
1. **Login sebagai Admin**
   - Akses `/profile`
   - Upload foto profil
   - Edit informasi
   - Ganti password

2. **Login sebagai Pembimbing**
   - Akses `/profile`
   - Upload foto profil
   - Edit informasi khusus pembimbing
   - Ganti password

3. **Login sebagai Siswa**
   - Akses `/profile`
   - Upload foto profil
   - Edit informasi khusus siswa
   - Ganti password

### Error Handling
- File upload errors
- Database errors
- Validation errors
- Permission errors

## 🚨 TROUBLESHOOTING

### Common Issues

#### 1. Field foto_profil tidak ada
```bash
# Jalankan script migration
php add_profile_photo_simple.php
```

#### 2. Folder upload tidak bisa dibuat
```bash
# Buat manual
mkdir -p writable/uploads/profile
chmod 755 writable/uploads/profile
```

#### 3. Foto tidak bisa diupload
- Cek permission folder writable
- Cek ukuran file (max 2MB)
- Cek tipe file (JPG/PNG/GIF)

#### 4. Helper esc tidak tersedia
- Pastikan helper 'text' sudah di-load di Autoload.php
- Restart web server

## 📈 ROADMAP FITUR

### Versi 1.0 (Current)
- ✅ Lihat profil
- ✅ Edit profil
- ✅ Upload foto profil
- ✅ Ganti password

### Versi 1.1 (Future)
- 🔄 Crop dan resize foto
- 🔄 Multiple foto profil
- 🔄 Profile privacy settings
- 🔄 Profile completion percentage

### Versi 1.2 (Future)
- 🔄 Social media links
- 🔄 Profile themes
- 🔄 Profile export/import
- 🔄 Profile analytics

## 👥 TIM DEVELOPMENT

- **Lead Developer**: [Nama Developer]
- **UI/UX Designer**: [Nama Designer]
- **Database Admin**: [Nama DBA]
- **QA Tester**: [Nama Tester]

## 📞 SUPPORT

Untuk bantuan teknis atau pertanyaan tentang fitur profil:
- **Email**: support@simamang.com
- **Documentation**: [Link ke dokumentasi]
- **Issue Tracker**: [Link ke GitHub Issues]

---

**Dibuat pada:** <?= date('d F Y') ?>  
**Versi:** 1.0.0  
**Status:** ✅ Production Ready
