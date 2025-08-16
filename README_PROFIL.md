# 📱 FITUR PROFIL SIMAMANG

## 🎯 DESKRIPSI
Fitur profil memungkinkan semua user (admin, pembimbing, siswa) untuk melihat, mengedit, dan mengelola profil mereka dengan kemampuan upload foto profil.

## ✨ FITUR UTAMA

### 🔍 Lihat Profil
- **Informasi Lengkap**: Nama, username, email, no HP, alamat
- **Foto Profil**: Tampilan foto profil dengan fallback avatar
- **Informasi Role**: Data khusus berdasarkan role user
- **Status Akun**: Status aktif/nonaktif dan tanggal bergabung

### 📝 Edit Profil
- **Edit Personal Info**: Update nama, email, no HP, alamat
- **Role-Specific Fields**: 
  - Siswa: tempat lahir, tanggal lahir, jenis kelamin, kelas, jurusan
  - Pembimbing: instansi, jabatan, bidang keahlian
- **Validasi Input**: Server-side validation dengan feedback

### 📸 Upload Foto Profil
- **File Types**: JPG, PNG, GIF
- **File Size**: Maksimal 2MB
- **Auto-Replace**: Foto lama otomatis diganti
- **Preview**: Tampilan foto sebelum upload

### 🔐 Ganti Password
- **Verifikasi**: Password lama harus sesuai
- **Security**: Password baru minimal 6 karakter
- **Confirmation**: Konfirmasi password baru

## 🚀 CARA PENGGUNAAN

### 1. Akses Fitur Profil
```
Login → Sidebar "Profil Saya" atau User Menu → "Profil Saya"
```

### 2. Upload Foto Profil
```
Profil → "Upload Foto Baru" → Pilih File → Upload
```

### 3. Edit Profil
```
Profil → "Edit Profil" → Ubah Data → "Simpan Perubahan"
```

### 4. Ganti Password
```
Profil → "Ganti Password" → Input Password → "Ganti Password"
```

## 🗄️ STRUKTUR DATABASE

### Field Baru
```sql
-- Ditambahkan ke semua tabel user
ALTER TABLE [table_name] ADD COLUMN foto_profil VARCHAR(255) NULL AFTER alamat;
```

### Tabel yang Diupdate
- `admin` - Administrator sistem
- `pembimbing` - Pembimbing magang
- `siswa` - Siswa magang

## 📁 STRUKTUR FILE

```
app/
├── Controllers/
│   └── Profile.php              # Controller profil
├── Views/
│   └── profile/
│       ├── index.php            # Halaman profil
│       └── edit.php             # Form edit profil
├── Database/
│   └── Migrations/
│       └── AddProfilePhoto.php  # Migration foto profil
└── Config/
    ├── Routes.php               # Route profil
    └── Autoload.php            # Helper loading

writable/
└── uploads/
    └── profile/                 # Folder foto profil

Scripts/
├── add_profile_photo.sql       # SQL migration
├── add_profile_photo_simple.php # PHP migration script
└── test_profile_feature.php    # Test script
```

## 🔧 INSTALASI

### 1. Jalankan Migration Database
```bash
# Opsi A: SQL Script
mysql -u username -p database_name < add_profile_photo.sql

# Opsi B: PHP Script
php add_profile_photo_simple.php

# Opsi C: CodeIgniter Migration
php spark migrate
```

### 2. Buat Folder Upload
```bash
mkdir -p writable/uploads/profile
chmod 755 writable/uploads/profile
```

### 3. Verifikasi Helper
Pastikan `app/Config/Autoload.php` memiliki:
```php
public $helpers = ['form', 'url', 'text'];
```

## 🌐 ROUTES

### Profile Routes
```
GET  /profile                    # Tampilkan profil
GET  /profile/edit              # Form edit profil
POST /profile/update            # Update profil
POST /profile/update-photo      # Upload foto
POST /profile/change-password   # Ganti password
```

### Upload Routes
```
GET /uploads/profile/{filename} # Akses foto profil
```

## 🎨 UI/UX FEATURES

### Responsive Design
- Bootstrap 5 grid system
- Mobile-friendly layout
- Collapsible sidebar

### User Experience
- Breadcrumb navigation
- Flash messages
- Modal dialogs
- Form validation
- Loading states

### Visual Elements
- Circular profile photos
- Role-based color coding
- Bootstrap icons
- Gradient buttons

## 🔒 KEAMANAN

### File Upload
- File type validation
- File size limits
- Random filename generation
- Secure storage location

### Authentication
- Session-based auth
- Role-based access
- CSRF protection
- Password hashing

### Data Validation
- Server-side validation
- Input sanitization
- SQL injection prevention
- XSS protection

## 🧪 TESTING

### Manual Testing
1. **Login sebagai Admin**
   - Akses `/profile`
   - Upload foto profil
   - Edit informasi
   - Ganti password

2. **Login sebagai Pembimbing**
   - Test semua fitur profil
   - Verifikasi field khusus

3. **Login sebagai Siswa**
   - Test semua fitur profil
   - Verifikasi field khusus

### Automated Testing
```bash
php test_profile_feature.php
```

## 🚨 TROUBLESHOOTING

### Common Issues

#### Field foto_profil tidak ada
```sql
-- Jalankan SQL migration
ALTER TABLE [table_name] ADD COLUMN foto_profil VARCHAR(255) NULL AFTER alamat;
```

#### Foto tidak bisa diupload
- Cek permission folder `writable/uploads/profile/`
- Cek ukuran file (max 2MB)
- Cek tipe file (JPG/PNG/GIF)

#### Helper esc tidak tersedia
- Pastikan helper 'text' sudah di-load
- Restart web server

#### Route tidak ditemukan
- Cek file `app/Config/Routes.php`
- Pastikan route group profile sudah ditambahkan

## 📊 STATUS FITUR

### ✅ Completed
- [x] Controller Profile
- [x] Views (index & edit)
- [x] Database migration
- [x] Routes configuration
- [x] Layout integration
- [x] File upload system
- [x] Security features
- [x] Validation system

### 🔄 In Progress
- [ ] Database migration execution
- [ ] End-to-end testing
- [ ] User acceptance testing

### 📋 TODO
- [ ] Performance optimization
- [ ] Additional features
- [ ] Documentation updates

## 👥 CONTRIBUTORS

- **Developer**: [Nama Developer]
- **Designer**: [Nama Designer]
- **Tester**: [Nama Tester]

## 📞 SUPPORT

- **Email**: support@simamang.com
- **Documentation**: [Link ke dokumentasi]
- **Issues**: [Link ke issue tracker]

## 📝 CHANGELOG

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Basic profile management
- ✅ Photo upload functionality
- ✅ Password change feature
- ✅ Role-based profile fields

### Version 1.1.0 (Planned)
- 🔄 Photo cropping
- 🔄 Multiple photo support
- 🔄 Privacy settings
- 🔄 Profile completion tracking

---

**Last Updated**: <?= date('d F Y H:i:s') ?>  
**Version**: 1.0.0  
**Status**: 🚀 Production Ready
