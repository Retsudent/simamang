<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
        
        // Cek apakah user sudah login dan role-nya admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            redirect()->to('/login')->send();
            exit;
        }
    }

    public function dashboard()
    {
        // Load TimeHelper manually
        helper('TimeHelper');
        
        // Hitung statistik dari tabel terpisah
        $totalSiswa = $this->db->table('siswa')->where('status', 'aktif')->countAllResults();
        $totalPembimbing = $this->db->table('pembimbing')->where('status', 'aktif')->countAllResults();
        $totalLog = $this->db->table('log_aktivitas')->countAllResults();
        $logPending = $this->db->table('log_aktivitas')->where('status', 'menunggu')->countAllResults();
        
        // Ambil 5 aktivitas terbaru untuk ringkasan dashboard
        $recentActivities = $this->db->table('log_aktivitas')
                                   ->select('log_aktivitas.*, siswa.nama as siswa_nama, siswa.nis')
                                   ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                   ->orderBy('log_aktivitas.created_at', 'DESC')
                                   ->limit(5)
                                   ->get()
                                   ->getResultArray();
        
        $data = [
            'title' => 'Dashboard Admin - SIMAMANG',
            'totalSiswa' => $totalSiswa,
            'totalPembimbing' => $totalPembimbing,
            'totalLog' => $totalLog,
            'logPending' => $logPending,
            'recentActivities' => $recentActivities
        ];
        
        return view('admin/dashboard', $data);
    }

    public function kelolaSiswa()
    {
        $request = service('request');
        $q = trim((string) ($request->getGet('q') ?? ''));
        $builder = $this->db->table('siswa')->where('status', 'aktif');
        if ($q !== '') {
            $builder->groupStart()
                    ->like('nama', $q)
                    ->orLike('username', $q)
                    ->orLike('nis', $q)
                    ->orLike('tempat_magang', $q)
                    ->groupEnd();
        }
        $siswa = $builder->get()->getResultArray();
        
        $data = [
            'title' => 'Kelola Data Siswa - SIMAMANG',
            'siswa' => $siswa,
            'q' => $q,
        ];
        
        return view('admin/kelola_siswa', $data);
    }

    public function tambahSiswa()
    {
        $data = [
            'title' => 'Tambah Siswa - SIMAMANG'
        ];
        
        return view('admin/form_siswa', $data);
    }

    public function simpanSiswa()
    {
        try {
            $request = service('request');
            
            // Validasi input
            $rules = [
                'nama' => 'required|min_length[3]',
                'username' => 'required|min_length[3]',
                'password' => 'required|min_length[6]',
                'nis' => 'required|min_length[5]',
                'tempat_magang' => 'required|min_length[5]',
                'tanggal_mulai_magang' => 'required|valid_date',
                'tanggal_selesai_magang' => 'required|valid_date'
            ];
            
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            
            // Cek apakah username sudah ada di semua tabel
            if ($this->isUsernameExists($request->getPost('username'))) {
                return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
            }
            
            // Cek apakah NIS sudah ada
            if ($this->isNISExists($request->getPost('nis'))) {
                return redirect()->back()->withInput()->with('error', 'NIS sudah terdaftar');
            }
            
            // Hash password
            $password = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
            
            $siswaData = [
                'nama' => $request->getPost('nama'),
                'username' => $request->getPost('username'),
                'password' => $password,
                'nis' => $request->getPost('nis'),
                'tempat_magang' => $request->getPost('tempat_magang'),
                'alamat_magang' => $request->getPost('tempat_magang'),
                'tanggal_mulai_magang' => $request->getPost('tanggal_mulai_magang'),
                'tanggal_selesai_magang' => $request->getPost('tanggal_selesai_magang'),
                'status' => 'aktif'
            ];
            
            // Insert ke tabel users terlebih dahulu
            $userData = [
                'nama' => $request->getPost('nama'),
                'username' => $request->getPost('username'),
                'password' => $password,
                'role' => 'siswa',
                'status' => 'aktif'
            ];
            
            $this->db->table('users')->insert($userData);
            $userId = $this->db->insertID();
            
            // Insert ke tabel siswa dengan user_id
            $siswaData['user_id'] = $userId;
            if ($this->db->table('siswa')->insert($siswaData)) {
                return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil ditambahkan');
            } else {
                // Rollback jika gagal insert ke tabel siswa
                $this->db->table('users')->where('id', $userId)->delete();
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan siswa');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Exception in simpanSiswa: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function editSiswa($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'ID siswa tidak valid');
        }
        
        $siswa = $this->db->table('siswa')->where('id', $id)->get()->getRowArray();
        
        if (!$siswa) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Data Siswa - SIMAMANG',
            'siswa' => $siswa
        ];
        
        return view('admin/form_siswa', $data);
    }

    public function updateSiswa($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'ID siswa tidak valid');
        }
        
        $request = service('request');
        
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[5]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Cek apakah username sudah ada di tabel lain (kecuali siswa yang sedang diedit)
        if ($this->isUsernameExists($request->getPost('username'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
        }
        
        // Cek apakah NIS sudah ada (kecuali siswa yang sedang diedit)
        if ($this->isNISExists($request->getPost('nis'), $id)) {
            return redirect()->back()->withInput()->with('error', 'NIS sudah terdaftar');
        }
        
        $siswaData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'nis' => $request->getPost('nis'),
            'tempat_magang' => $request->getPost('tempat_magang'),
            'alamat_magang' => $request->getPost('tempat_magang'),
            'tanggal_mulai_magang' => $request->getPost('tanggal_mulai_magang'),
            'tanggal_selesai_magang' => $request->getPost('tanggal_selesai_magang')
        ];
        
        // Update password jika diisi
        if ($request->getPost('password')) {
            $siswaData['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        // Ambil user_id dari siswa
        $siswa = $this->db->table('siswa')->where('id', $id)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }
        
        // Update data di tabel users
        $userData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username')
        ];
        
        // Update password jika diisi
        if ($request->getPost('password')) {
            $userData['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        $this->db->table('users')->where('id', $siswa['user_id'])->update($userData);
        
        // Update data di tabel siswa
        if ($this->db->table('siswa')->where('id', $id)->update($siswaData)) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Data siswa berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data siswa');
        }
    }

    public function hapusSiswa($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'ID siswa tidak valid');
        }

        $request = service('request');
        $isPermanent = ($request->getGet('permanent') === '1');

        // Ambil user_id dari siswa
        $siswa = $this->db->table('siswa')->where('id', $id)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }

        if ($isPermanent) {
            // Hard delete dengan transaksi
            $this->db->transStart();
            try {
                // Hapus komentar terkait log siswa
                $logs = $this->db->table('log_aktivitas')->select('id')->where('siswa_id', $id)->get()->getResultArray();
                $logIds = array_column($logs, 'id');
                if (!empty($logIds)) {
                    $this->db->table('komentar_pembimbing')->whereIn('log_id', $logIds)->delete();
                }
                // Hapus log siswa
                $this->db->table('log_aktivitas')->where('siswa_id', $id)->delete();
                // Hapus siswa dan user
                $this->db->table('siswa')->where('id', $id)->delete();
                $this->db->table('users')->where('id', $siswa['user_id'])->delete();

                $this->db->transComplete();
                if ($this->db->transStatus() === false) {
                    return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menghapus permanen.');
                }
                return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil dihapus permanen.');
            } catch (\Throwable $e) {
                $this->db->transRollback();
                log_message('error', 'Error hard delete siswa: ' . $e->getMessage());
                return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
            }
        }

        // Soft delete - ubah status menjadi nonaktif di kedua tabel
        $this->db->table('users')->where('id', $siswa['user_id'])->update(['status' => 'nonaktif']);
        if ($this->db->table('siswa')->where('id', $id)->update(['status' => 'nonaktif'])) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa dinonaktifkan');
        }
        return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menonaktifkan siswa');
    }

    public function kelolaPembimbing()
    {
        $request = service('request');
        $q = trim((string) ($request->getGet('q') ?? ''));
        $builder = $this->db->table('pembimbing')->where('status', 'aktif');
        if ($q !== '') {
            $builder->groupStart()
                    ->like('nama', $q)
                    ->orLike('username', $q)
                    ->orLike('email', $q)
                    ->groupEnd();
        }
        $pembimbing = $builder->get()->getResultArray();
        
        $data = [
            'title' => 'Kelola Data Pembimbing - SIMAMANG',
            'pembimbing' => $pembimbing,
            'q' => $q,
        ];
        
        return view('admin/kelola_pembimbing', $data);
    }

    public function tambahPembimbing()
    {
        $data = [
            'title' => 'Tambah Pembimbing - SIMAMANG'
        ];
        
        return view('admin/form_pembimbing', $data);
    }

    public function simpanPembimbing()
    {
        $request = service('request');
        
        // Validasi input (hanya kolom yang ada di tabel)
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Cek apakah username sudah ada di semua tabel
        if ($this->isUsernameExists($request->getPost('username'))) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
        }
        
        // Hash password
        $password = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        
        $pembimbingData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $password,
            'email' => $request->getPost('email'),
            'no_hp' => $request->getPost('no_hp'),
            'status' => 'aktif'
        ];
        
        // Insert ke tabel users terlebih dahulu
        $userData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $password,
            'email' => $request->getPost('email'),
            'no_hp' => $request->getPost('no_hp'),
            'role' => 'pembimbing',
            'status' => 'aktif'
        ];
        
        $this->db->table('users')->insert($userData);
        $userId = $this->db->insertID();
        
        // Insert ke tabel pembimbing dengan user_id
        $pembimbingData['user_id'] = $userId;
        if ($this->db->table('pembimbing')->insert($pembimbingData)) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil ditambahkan');
        } else {
            // Rollback jika gagal insert ke tabel pembimbing
            $this->db->table('users')->where('id', $userId)->delete();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pembimbing');
        }
    }

    public function editPembimbing($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'ID pembimbing tidak valid');
        }
        
        $pembimbing = $this->db->table('pembimbing')->where('id', $id)->get()->getRowArray();
        
        if (!$pembimbing) {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Pembimbing tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Data Pembimbing - SIMAMANG',
            'pembimbing' => $pembimbing
        ];
        
        return view('admin/form_pembimbing', $data);
    }

    public function updatePembimbing($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'ID pembimbing tidak valid');
        }
        
        $request = service('request');
        
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'instansi' => 'required|min_length[3]',
            'jabatan' => 'required|min_length[3]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Cek apakah username sudah ada di tabel lain (kecuali pembimbing yang sedang diedit)
        if ($this->isUsernameExists($request->getPost('username'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan');
        }
        
        $pembimbingData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'email' => $request->getPost('email'),
            'no_hp' => $request->getPost('no_hp'),
            'alamat' => $request->getPost('alamat'),
            'instansi' => $request->getPost('instansi'),
            'jabatan' => $request->getPost('jabatan'),
            'bidang_keahlian' => $request->getPost('bidang_keahlian')
        ];
        
        // Update password jika diisi
        if ($request->getPost('password')) {
            $pembimbingData['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        if ($this->db->table('pembimbing')->where('id', $id)->update($pembimbingData)) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Data pembimbing berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data pembimbing');
        }
    }

    public function hapusPembimbing($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'ID pembimbing tidak valid');
        }

        $request = service('request');
        $isPermanent = ($request->getGet('permanent') === '1');

        $pembimbing = $this->db->table('pembimbing')->where('id', $id)->get()->getRowArray();
        if (!$pembimbing) {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Pembimbing tidak ditemukan');
        }

        if ($isPermanent) {
            $this->db->transStart();
            try {
                // Optional: hapus hubungan bimbingan pada siswa
                $this->db->table('siswa')->where('pembimbing_id', $id)->update(['pembimbing_id' => null]);

                // Hapus pembimbing dan users terkait
                $this->db->table('pembimbing')->where('id', $id)->delete();
                if (!empty($pembimbing['user_id'])) {
                    $this->db->table('users')->where('id', $pembimbing['user_id'])->delete();
                }

                $this->db->transComplete();
                if ($this->db->transStatus() === false) {
                    return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Gagal menghapus permanen.');
                }
                return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing dihapus permanen.');
            } catch (\Throwable $e) {
                $this->db->transRollback();
                log_message('error', 'Error hard delete pembimbing: ' . $e->getMessage());
                return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
            }
        }

        // Soft delete - ubah status menjadi nonaktif di pembimbing dan users
        if (!empty($pembimbing['user_id'])) {
            $this->db->table('users')->where('id', $pembimbing['user_id'])->update(['status' => 'nonaktif']);
        }
        if ($this->db->table('pembimbing')->where('id', $id)->update(['status' => 'nonaktif'])) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing dinonaktifkan');
        }
        return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Gagal menonaktifkan pembimbing');
    }

    public function laporanMagang()
    {
        // Ambil semua siswa aktif untuk dropdown
        $siswa = $this->db->table('siswa')
                          ->where('status', 'aktif')
                          ->get()
                          ->getResultArray();
        
        // Hitung statistik
        $totalLog = $this->db->table('log_aktivitas')->countAllResults();
        $logDisetujui = $this->db->table('log_aktivitas')->where('status', 'disetujui')->countAllResults();
        $logMenunggu = $this->db->table('log_aktivitas')->where('status', 'menunggu')->countAllResults();
        
        $data = [
            'title' => 'Laporan Magang - SIMAMANG',
            'siswa' => $siswa,
            'totalLog' => $totalLog,
            'logDisetujui' => $logDisetujui,
            'logMenunggu' => $logMenunggu
        ];
        
        return view('admin/laporan_magang', $data);
    }

    public function generateLaporanAdmin()
    {
        $request = service('request');
        $siswaId = $request->getPost('siswa_id');
        $startDate = $request->getPost('start_date');
        $endDate = $request->getPost('end_date');
        
        if (!$siswaId || !$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Semua field harus diisi');
        }
        
        // Ambil data siswa
        $siswa = $this->db->table('siswa')
                          ->where('id', $siswaId)
                          ->get()
                          ->getRowArray();
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }
        
        // Ambil log aktivitas dalam rentang tanggal + komentar pembimbing (left join)
        $logAktivitas = $this->db->table('log_aktivitas')
                                 ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                                 ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                                 ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                                 ->where('log_aktivitas.siswa_id', $siswaId)
                                 ->where('log_aktivitas.tanggal >=', $startDate)
                                 ->where('log_aktivitas.tanggal <=', $endDate)
                                 ->orderBy('log_aktivitas.tanggal', 'ASC')
                                 ->get()
                                 ->getResultArray();
        
        $data = [
            'title' => 'Laporan Magang - ' . $siswa['nama'],
            'siswa' => $siswa,
            // View mengharapkan variabel 'logs'
            'logs' => $logAktivitas,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        return view('admin/preview_laporan', $data);
    }

    public function aturBimbingan()
    {
        $request = service('request');
        $qp = trim((string) ($request->getGet('qp') ?? '')); // query pembimbing
        $qs = trim((string) ($request->getGet('qs') ?? '')); // query siswa

        // Ambil semua pembimbing aktif (dengan filter opsional)
        $pb = $this->db->table('pembimbing')->where('status', 'aktif');
        if ($qp !== '') {
            $pb->groupStart()->like('nama', $qp)->orLike('username', $qp)->orLike('instansi', $qp)->groupEnd();
        }
        $pembimbing = $pb->get()->getResultArray();
        
        // Ambil semua siswa aktif (dengan filter opsional)
        $sw = $this->db->table('siswa')->where('status', 'aktif');
        if ($qs !== '') {
            $sw->groupStart()->like('nama', $qs)->orLike('username', $qs)->orLike('nis', $qs)->orLike('tempat_magang', $qs)->groupEnd();
        }
        $siswa = $sw->get()->getResultArray();
        
        $data = [
            'title' => 'Atur Bimbingan - SIMAMANG',
            'pembimbing' => $pembimbing,
            'siswa' => $siswa,
            'qp' => $qp,
            'qs' => $qs,
        ];
        
        return view('admin/atur_bimbingan', $data);
    }

    public function aturBimbinganPembimbing($pembimbingId = null)
    {
        if (!$pembimbingId) {
            return redirect()->to('/admin/atur-bimbingan')->with('error', 'ID Pembimbing tidak valid');
        }
        
        // Ambil data pembimbing
        $pembimbing = $this->db->table('pembimbing')
                               ->where('id', $pembimbingId)
                               ->where('status', 'aktif')
                               ->get()
                               ->getRowArray();
        
        if (!$pembimbing) {
            return redirect()->to('/admin/atur-bimbingan')->with('error', 'Pembimbing tidak ditemukan');
        }
        
        // Ambil semua siswa aktif
        $semuaSiswa = $this->db->table('siswa')
                               ->where('status', 'aktif')
                               ->get()
                               ->getResultArray();
        
        // Ambil siswa yang sudah dibimbing oleh pembimbing ini
        $assignedSiswa = $this->db->table('siswa')
                                  ->where('pembimbing_id', $pembimbingId)
                                  ->where('status', 'aktif')
                                  ->get()
                                  ->getResultArray();
        
        $assignedIds = array_column($assignedSiswa, 'id');
        
        $data = [
            'title' => 'Atur Bimbingan - ' . $pembimbing['nama'],
            'pembimbing' => $pembimbing,
            'semuaSiswa' => $semuaSiswa,
            'assignedIds' => $assignedIds
        ];
        
        return view('admin/atur_bimbingan_pembimbing', $data);
    }

    public function simpanAturBimbingan($pembimbingId = null)
    {
        if (!$pembimbingId) {
            return redirect()->to('/admin/atur-bimbingan')->with('error', 'ID Pembimbing tidak valid');
        }
        
        $request = service('request');
        $siswaIds = $request->getPost('siswa_ids') ?? [];
        
        try {
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
            
            return redirect()->to('/admin/atur-bimbingan')->with('success', 'Pengaturan bimbingan berhasil disimpan');
        } catch (\Exception $e) {
            log_message('error', 'Error in simpanAturBimbingan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }



    private function isUsernameExists($username, $excludeId = null)
    {
        try {
            // Cek di tabel users (tabel utama)
            $query = $this->db->table('users')->where('username', $username);
            
            if ($excludeId) {
                // Jika excludeId adalah ID dari tabel role-specific, cari user_id yang sesuai
                $adminCheck = $this->db->table('admin')->where('id', $excludeId)->get()->getRowArray();
                $pembimbingCheck = $this->db->table('pembimbing')->where('id', $excludeId)->get()->getRowArray();
                $siswaCheck = $this->db->table('siswa')->where('id', $excludeId)->get()->getRowArray();
                
                if ($adminCheck) {
                    $query->where('id !=', $adminCheck['user_id']);
                } elseif ($pembimbingCheck) {
                    $query->where('id !=', $pembimbingCheck['user_id']);
                } elseif ($siswaCheck) {
                    $query->where('id !=', $siswaCheck['user_id']);
                }
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error in isUsernameExists: ' . $e->getMessage());
            return false; // Return false jika ada error, biarkan proses lanjut
        }
    }

    private function isNISExists($nis, $excludeId = null)
    {
        try {
            $query = $this->db->table('siswa')->where('nis', $nis);
            if ($excludeId) {
                $query->where('id !=', $excludeId);
            }
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error in isNISExists: ' . $e->getMessage());
            return false; // Return false jika ada error, biarkan proses lanjut
        }
    }
}

