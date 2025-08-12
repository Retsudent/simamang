# SIMAMANG - Sistem Monitoring Aktivitas Magang

Sistem untuk membantu siswa mencatat aktivitas magang setiap hari, serta memungkinkan pembimbing memberikan komentar dan menyusun laporan otomatis.

## 🚀 Fitur Utama

### Role Pengguna
- **Admin**: Kelola data siswa & pembimbing, lihat laporan semua siswa
- **Siswa**: Isi log harian, lihat komentar pembimbing, unduh laporan pribadi
- **Pembimbing**: Lihat log siswa, beri komentar, validasi laporan

### Fitur Utama
1. **🧾 Log Aktivitas Harian** - Input aktivitas magang siswa
2. **💬 Komentar Pembimbing** - Validasi dan feedback untuk siswa
3. **📄 Export Laporan PDF** - Laporan otomatis dengan format yang rapi

## 🛠️ Instalasi & Setup

### 1. Clone Repository
```bash
git clone [repository-url]
cd simamang
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Database
- Buat database MySQL/PostgreSQL
- Copy file `env` ke `.env`
- Sesuaikan konfigurasi database di `.env`
- Jalankan migration: `php spark migrate`

### 4. Buat Akun Admin Pertama
```bash
php create_admin.php
```
**Default Admin:**
- Username: `admin`
- Password: `admin123`

### 5. Buat Akun Pembimbing Sample (Opsional)
```bash
php create_pembimbing.php
```
**Sample Pembimbing:**
- Username: `pembimbing1`, Password: `pembimbing123`
- Username: `pembimbing2`, Password: `pembimbing123`
- Username: `pembimbing3`, Password: `pembimbing123`

## 🔐 Cara Login & Akses

### Admin
- **Login**: `/login` dengan username `admin`
- **Dashboard**: `/admin/dashboard`
- **Fitur**: Kelola siswa, kelola pembimbing, laporan

### Pembimbing
- **Login**: `/login` dengan akun pembimbing
- **Dashboard**: `/pembimbing/dashboard`
- **Fitur**: Validasi log siswa, beri komentar

### Siswa
- **Register**: `/register` (hanya untuk siswa)
- **Login**: `/login` dengan akun siswa
- **Dashboard**: `/siswa/dashboard`
- **Fitur**: Input log aktivitas, lihat komentar, export laporan

## ⚠️ Keamanan & Akses

- **Pembimbing TIDAK bisa register sendiri**
- **Hanya Admin yang bisa membuat akun pembimbing**
- **Siswa hanya bisa register untuk role siswa**
- **Setiap role memiliki akses terbatas sesuai fungsinya**

## 🏗️ Struktur Aplikasi

```
app/
├── Controllers/
│   ├── Admin.php          # Kelola data siswa & pembimbing
│   ├── Auth.php           # Login & register
│   ├── Pembimbing.php     # Validasi & komentar
│   └── Siswa.php          # Input log & laporan
├── Models/
│   ├── UserModel.php      # Manajemen user
│   ├── LogAktivitasModel.php # Log aktivitas
│   └── KomentarModel.php  # Komentar pembimbing
└── Views/
    ├── admin/             # Dashboard admin
    ├── pembimbing/        # Dashboard pembimbing
    ├── siswa/             # Dashboard siswa
    └── auth/              # Login & register
```

## 📱 Teknologi

- **Backend**: CodeIgniter 4 (MVC)
- **Frontend**: Bootstrap 5
- **Database**: MySQL/PostgreSQL
- **PDF Export**: DomPDF/TCPDF
- **Auth**: Session-based authentication

## 🎯 Alur Sistem

```
SISWA → Input Log Harian → PEMBIMBING → Validasi & Komentar → ADMIN → Laporan Lengkap
```

## 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi tim development.

---

**Dikerjakan oleh:** [Nama Developer]  
**Target Selesai:** 31 Agustus 2025
