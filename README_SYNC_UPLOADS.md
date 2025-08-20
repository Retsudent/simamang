# Panduan Sinkronisasi Uploads untuk Tim

## Masalah
File foto yang diupload tidak ter-sync antar tim karena folder `writable/uploads/` di-ignore oleh Git untuk keamanan.

## Solusi

### 1. Setelah Clone/Pull Repository
Jalankan script sinkronisasi:
```bash
php sync_uploads.php
```

Script ini akan:
- Membuat folder uploads yang diperlukan
- Membuat file index.html untuk keamanan
- Mengecek ketersediaan default avatar

### 2. 🆕 **Share Uploads Antar Tim**

#### **Untuk yang Upload PP/File:**
```bash
php share_uploads.php
```
- Script akan membuat folder `shared_uploads/` dengan semua file uploads
- Upload folder `shared_uploads/` ke Google Drive/Dropbox
- Share link ke tim

#### **Untuk yang Mau Lihat PP/File:**
1. Download folder `shared_uploads/` dari tim
2. Letakkan di root project (sejajar dengan folder `app/`, `public/`, dll)
3. Jalankan:
```bash
php get_shared_uploads.php
```

### 3. Jika Foto Tidak Muncul
- Foto yang diupload teman lain akan menggunakan **default avatar** (gambar default)
- Untuk melihat foto asli, gunakan script share/get di atas

### 4. Struktur Folder Uploads
```
writable/uploads/
├── profile/          # Foto profil user
├── logs/             # File lampiran log aktivitas
└── index.html        # File keamanan
```

### 5. Default Avatar
- File: `public/assets/img/default-avatar.png`
- Digunakan sebagai fallback jika foto tidak ditemukan
- Pastikan file ini selalu ada di repository

## Keuntungan Sistem Ini
1. **Keamanan**: File uploads tidak terekspos di Git
2. **Konsistensi**: Semua tim menggunakan struktur folder yang sama
3. **Fallback**: Default avatar mencegah error saat foto tidak ada
4. **Otomatis**: Script memudahkan setup folder
5. **🆕 Sharing**: Bisa share file uploads antar tim dengan mudah

## Workflow Tim
1. **Setelah pull dari Git**: `php sync_uploads.php`
2. **Setelah upload PP**: `php share_uploads.php` → upload folder ke cloud → share link
3. **Setelah dapat folder dari tim**: `php get_shared_uploads.php`

## Troubleshooting
- Jika script `sync_uploads.php` error, buat manual folder `writable/uploads/profile/` dan `writable/uploads/logs/`
- Pastikan permission folder `writable/` bisa ditulis oleh web server
- Jika default avatar tidak muncul, cek file `public/assets/img/default-avatar.png`
- Jika import gagal, pastikan folder `shared_uploads/` ada di root project
