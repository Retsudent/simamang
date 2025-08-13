# SIMAMANG - Sistem Monitoring Aktivitas Magang

![SIMAMANG Logo](https://img.shields.io/badge/SIMAMANG-Sistem%20Monitoring%20Aktivitas%20Magang-blue)
![CodeIgniter 4](https://img.shields.io/badge/CodeIgniter-4.0+-orange)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13+-blue)
![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5.3+-purple)

## 📋 Deskripsi

SIMAMANG adalah sistem web berbasis CodeIgniter 4 yang dirancang untuk memonitor aktivitas magang siswa. Sistem ini memungkinkan siswa mencatat aktivitas harian, pembimbing memberikan komentar dan validasi, serta admin mengelola data dan laporan.

## ✨ Fitur Utama

### 👨‍🎓 Untuk Siswa
- **Dashboard Interaktif** - Statistik aktivitas dan informasi pribadi
- **Input Log Harian** - Form input aktivitas dengan upload bukti
- **Riwayat Aktivitas** - Lihat semua log yang telah diinput
- **Cetak Laporan PDF** - Generate laporan magang otomatis

### 👨‍🏫 Untuk Pembimbing
- **Dashboard Pembimbing** - Overview aktivitas siswa
- **Review Log Siswa** - Lihat dan beri komentar pada log
- **Validasi Aktivitas** - Setujui atau minta revisi log

### 👨‍💼 Untuk Admin
- **Kelola Data Siswa** - Tambah, edit, hapus data siswa
- **Kelola Pembimbing** - Manajemen data pembimbing
- **Laporan Komprehensif** - Laporan semua siswa dalam format PDF

## 🛠️ Teknologi yang Digunakan

- **Backend**: CodeIgniter 4 (PHP 8.0+)
- **Database**: PostgreSQL 13+
- **Frontend**: Bootstrap 5.3, Bootstrap Icons
- **PDF**: DomPDF (untuk generate laporan)
- **Upload**: File handling untuk bukti aktivitas

## 📦 Instalasi

### Prerequisites
- PHP 8.0 atau lebih tinggi
- PostgreSQL 13 atau lebih tinggi
- Composer
- Web server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/simamang.git
   cd simamang
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Database**
   - Buat database PostgreSQL baru dengan nama `simamang`
   - Update konfigurasi database di `app/Config/Database.php`
   ```php
   'hostname' => 'localhost',
   'username' => 'postgres',
   'password' => 'your_password',
   'database' => 'simamang',
   'port' => 5432
   ```

4. **Buat Tabel Database**
   ```bash
   php create_tables_postgresql.php
   ```

5. **Buat Data Sample (Opsional)**
   ```bash
   php create_sample_data_postgresql.php
   ```

6. **Jalankan Aplikasi**
   ```bash
   php spark serve
   ```

7. **Akses Aplikasi**
   Buka browser dan akses: `http://localhost:8080`

## 👤 Akun Default

Setelah menjalankan script sample data, Anda dapat login dengan akun berikut:

### Admin
- **Username**: `admin`
- **Password**: `admin123`

### Pembimbing
- **Username**: `pembimbing1`
- **Password**: `pembimbing123`

### Siswa
- **Username**: `siswa1` atau `siswa2`
- **Password**: `siswa123`

## 📁 Struktur Aplikasi

```
simamang/
├── app/
│   ├── Config/           # Konfigurasi aplikasi
│   ├── Controllers/      # Controller untuk setiap role
│   ├── Views/           # Template view
│   ├── Models/          # Model database
│   ├── Filters/         # Filter autentikasi
│   └── Database/        # Migrasi dan seeder
├── public/              # Asset publik
├── writable/            # File upload dan cache
├── vendor/              # Dependencies Composer
└── create_*.php         # Script setup database
```

## 🔐 Sistem Autentikasi

Aplikasi menggunakan sistem autentikasi multi-role:
- **Session-based authentication**
- **Role-based access control**
- **Password hashing dengan bcrypt**
- **CSRF protection**

## 📊 Database Schema

### Tabel Utama
- `admin` - Data administrator
- `pembimbing` - Data pembimbing magang
- `siswa` - Data siswa magang
- `log_aktivitas` - Log aktivitas harian siswa
- `komentar_pembimbing` - Komentar dan validasi pembimbing

### Relasi
- Siswa → Pembimbing (Many-to-One)
- Siswa → Log Aktivitas (One-to-Many)
- Log Aktivitas → Komentar Pembimbing (One-to-Many)

## 🎨 Fitur UI/UX

### Design System
- **Modern & Responsive** - Menggunakan Bootstrap 5
- **Gradient Backgrounds** - Visual yang menarik
- **Icon Integration** - Bootstrap Icons untuk konsistensi
- **Mobile-First** - Responsif di semua device

### Komponen UI
- **Cards dengan shadow** - Untuk grouping konten
- **Progress bars** - Untuk statistik
- **Badges** - Untuk status dan kategori
- **Alerts** - Untuk feedback user
- **Forms dengan floating labels** - UX yang lebih baik

## 📈 Fitur Dashboard

### Dashboard Siswa
- Statistik total log aktivitas
- Log bulan ini
- Status disetujui vs menunggu
- Aktivitas terbaru
- Informasi pembimbing

### Dashboard Pembimbing
- Daftar siswa yang dibimbing
- Log aktivitas terbaru
- Statistik validasi

### Dashboard Admin
- Overview semua data
- Statistik sistem
- Quick actions

## 📄 Sistem Laporan

### Fitur Laporan
- **Export PDF** - Menggunakan DomPDF
- **Filter tanggal** - Range waktu yang fleksibel
- **Header otomatis** - Informasi siswa dan pembimbing
- **Format standar** - Sesuai kebutuhan institusi

### Template Laporan
- Biodata siswa
- Daftar aktivitas harian
- Komentar pembimbing
- Statistik aktivitas

## 🔧 Konfigurasi

### Environment Variables
Buat file `.env` berdasarkan `env`:
```env
CI_ENVIRONMENT = development
database.default.hostname = localhost
database.default.database = simamang
database.default.username = postgres
database.default.password = your_password
```

### Upload Configuration
- **Path**: `writable/uploads/bukti/`
- **Max Size**: 2MB
- **Allowed Types**: JPG, PNG, PDF, DOC, DOCX

## 🚀 Deployment

### Production Setup
1. Set `CI_ENVIRONMENT = production` di `.env`
2. Disable debug toolbar
3. Enable caching
4. Configure web server (Apache/Nginx)
5. Set proper file permissions

### Security Considerations
- Enable HTTPS
- Set secure session configuration
- Regular database backups
- Input validation and sanitization
- File upload security

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Pastikan PostgreSQL berjalan
   - Cek konfigurasi database
   - Verifikasi credentials

2. **Upload File Error**
   - Cek folder permissions di `writable/uploads/`
   - Pastikan file size tidak melebihi limit

3. **Session Error**
   - Cek folder permissions di `writable/session/`
   - Restart web server

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Untuk bantuan dan dukungan:
- Email: support@simamang.com
- Documentation: [docs.simamang.com](https://docs.simamang.com)
- Issues: [GitHub Issues](https://github.com/yourusername/simamang/issues)

## 🙏 Acknowledgments

- CodeIgniter 4 Team
- Bootstrap Team
- PostgreSQL Community
- Semua kontributor dan pengguna

---

**SIMAMANG** - Membuat monitoring magang lebih mudah dan efisien! 🎓✨
