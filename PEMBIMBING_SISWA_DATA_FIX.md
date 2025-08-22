# Perbaikan Data Pembimbing Siswa

## 🔍 Masalah yang Ditemukan
User melaporkan inkonsistensi dalam tampilan status bimbingan siswa di halaman "Atur Bimbingan". Beberapa siswa menunjukkan status "Sudah Terbimbing" tapi checkbox-nya tidak tercentang, sementara yang lain menunjukkan "Belum Ada Pembimbing" padahal seharusnya sudah dibimbing.

## 🔧 Penyebab Masalah
1. **Inkonsistensi Database**: Controller menggunakan field `pembimbing_id` di tabel `siswa`, padahal seharusnya menggunakan tabel `pembimbing_siswa` untuk relasi many-to-many
2. **Data Tidak Sinkron**: Data bimbingan tersimpan di dua tempat berbeda (tabel `siswa` dan tabel `pembimbing_siswa`)
3. **Logika Query Salah**: Query mengambil data dari field yang salah

## ✅ Perbaikan yang Dilakukan

### 1. **Memperbaiki Controller `aturBimbinganPembimbing`**
**File**: `app/Controllers/Admin.php`

**Sebelum:**
```php
// Ambil siswa yang sudah dibimbing oleh pembimbing ini
$assignedSiswa = $this->db->table('siswa')
                          ->where('pembimbing_id', $pembimbingId)
                          ->where('status', 'aktif')
                          ->get()
                          ->getResultArray();

$assignedIds = array_column($assignedSiswa, 'id');
```

**Sesudah:**
```php
// Ambil siswa yang sudah dibimbing oleh pembimbing ini menggunakan tabel pembimbing_siswa
$assignedSiswa = $this->db->table('pembimbing_siswa')
                          ->select('siswa_id')
                          ->where('pembimbing_id', $pembimbingId)
                          ->get()
                          ->getResultArray();

$assignedIds = array_column($assignedSiswa, 'siswa_id');
```

### 2. **Memperbaiki Controller `simpanAturBimbingan`**
**File**: `app/Controllers/Admin.php`

**Sebelum:**
```php
// Reset semua pembimbing_id untuk pembimbing ini
$this->db->table('siswa')
         ->where('pembimbing_id', $pembimbingId)
         ->update(['pembimbing_id' => null]);

// Set pembimbing_id baru untuk siswa yang dipilih
if (!empty($siswaIds)) {
    $this->db->table('siswa')
             ->whereIn('id', $siswaIds)
             ->update(['pembimbing_id' => $pembimbingId]);
}
```

**Sesudah:**
```php
// Reset semua relasi untuk pembimbing ini
$this->db->table('pembimbing_siswa')
         ->where('pembimbing_id', $pembimbingId)
         ->delete();

// Set relasi baru untuk siswa yang dipilih
if (!empty($siswaIds)) {
    $dataToInsert = [];
    foreach ($siswaIds as $siswaId) {
        $dataToInsert[] = [
            'pembimbing_id' => $pembimbingId,
            'siswa_id' => $siswaId
        ];
    }
    $this->db->table('pembimbing_siswa')->insertBatch($dataToInsert);
}
```

### 3. **Script Perbaikan Data**
**File**: `fix_pembimbing_siswa_data.php`

Script untuk memindahkan data dari field `pembimbing_id` di tabel `siswa` ke tabel `pembimbing_siswa`:
- Mengambil semua siswa yang memiliki `pembimbing_id`
- Memasukkan data ke tabel `pembimbing_siswa`
- Verifikasi data
- Opsional: menghapus field `pembimbing_id` dari tabel `siswa`

## 🧪 Cara Menjalankan Perbaikan

### 1. **Jalankan Script Perbaikan Data**
```bash
php fix_pembimbing_siswa_data.php
```

Script akan:
- Menampilkan jumlah siswa yang ditemukan
- Memindahkan data ke tabel `pembimbing_siswa`
- Menampilkan hasil perbaikan
- Menawarkan untuk menghapus field `pembimbing_id` dari tabel `siswa`

### 2. **Verifikasi Perbaikan**
1. Buka halaman "Atur Bimbingan" untuk pembimbing tertentu
2. Periksa apakah checkbox tercentang sesuai dengan status bimbingan
3. Test menyimpan pengaturan bimbingan
4. Verifikasi notifikasi muncul dengan benar

## 📊 Struktur Database yang Benar

### **Tabel `pembimbing_siswa`** (Relasi Many-to-Many)
```sql
CREATE TABLE pembimbing_siswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pembimbing_id INT NOT NULL,
    siswa_id INT NOT NULL,
    UNIQUE KEY unique_pembimbing_siswa (pembimbing_id, siswa_id)
);
```

### **Tabel `siswa`** (Tanpa field `pembimbing_id`)
```sql
-- Field pembimbing_id dihapus dari tabel siswa
-- Relasi bimbingan hanya melalui tabel pembimbing_siswa
```

## 🎯 Hasil yang Diharapkan

### ✅ **Setelah Perbaikan:**
- Checkbox tercentang sesuai dengan status bimbingan yang sebenarnya
- Status "Sudah Terbimbing" = checkbox tercentang
- Status "Belum Ada Pembimbing" = checkbox tidak tercentang
- Data bimbingan tersimpan dengan konsisten di tabel `pembimbing_siswa`
- Notifikasi berfungsi dengan benar saat menyimpan pengaturan

### 🔧 **Fitur yang Tetap Berfungsi:**
- Select all checkbox
- Individual checkbox selection
- Simpan pengaturan bimbingan
- Validasi data
- Notifikasi success/error

## 📋 Langkah Testing

### 1. **Test Tampilan Status**
1. Buka halaman "Atur Bimbingan" untuk pembimbing tertentu
2. **Verifikasi**: Checkbox tercentang untuk siswa yang sudah dibimbing
3. **Verifikasi**: Checkbox tidak tercentang untuk siswa yang belum dibimbing
4. **Verifikasi**: Status badge sesuai dengan checkbox

### 2. **Test Simpan Pengaturan**
1. Centang/uncentang beberapa siswa
2. Klik "Simpan Pengaturan"
3. **Verifikasi**: Notifikasi "Pengaturan bimbingan berhasil disimpan" muncul
4. **Verifikasi**: Data tersimpan dengan benar

### 3. **Test Select All**
1. Klik checkbox "Pilih Semua"
2. **Verifikasi**: Semua checkbox tercentang
3. Klik lagi untuk uncheck semua
4. **Verifikasi**: Semua checkbox tidak tercentang

## 🚀 Cara Menggunakan

1. **Jalankan Script Perbaikan:**
   ```bash
   php fix_pembimbing_siswa_data.php
   ```

2. **Verifikasi Data:**
   - Cek apakah data sudah pindah ke tabel `pembimbing_siswa`
   - Cek apakah checkbox dan status sudah konsisten

3. **Test Fitur:**
   - Buka halaman "Atur Bimbingan"
   - Test centang/uncentang siswa
   - Test simpan pengaturan

**Kesimpulan**: Masalah inkonsistensi data pembimbing siswa sudah diperbaiki dengan menggunakan tabel `pembimbing_siswa` yang benar dan memindahkan data yang sudah ada.
