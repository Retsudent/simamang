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
        $totalSiswa = $this->db->table('siswa')->countAllResults();
        $totalPembimbing = $this->db->table('pembimbing')->countAllResults();
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
        $siswa = $this->db->table('siswa')->get()->getResultArray();
        
        $data = [
            'title' => 'Kelola Data Siswa - SIMAMANG',
            'siswa' => $siswa
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
            
            // Mulai transaction
            $this->db->transStart();
            
            // Insert ke tabel siswa
            $siswaData = [
                'nama' => $request->getPost('nama'),
                'username' => $request->getPost('username'),
                'password' => $password,
                'nis' => $request->getPost('nis'),
                'tempat_magang' => $request->getPost('tempat_magang'),
                'alamat_magang' => $request->getPost('alamat_magang') ?: $request->getPost('tempat_magang'),
                'tanggal_mulai_magang' => $request->getPost('tanggal_mulai_magang'),
                'tanggal_selesai_magang' => $request->getPost('tanggal_selesai_magang'),
                'status' => 'aktif',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('siswa')->insert($siswaData);
            
            // Commit transaction
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan siswa. Silakan coba lagi.');
            }
            
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil ditambahkan');
            
        } catch (\Exception $e) {
            // Rollback jika ada error
            $this->db->transRollback();
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
        
        try {
            // Mulai transaction untuk memastikan konsistensi data
            $this->db->transStart();
            
            // Ambil data siswa untuk mendapatkan username
            $siswa = $this->db->table('siswa')->where('id', $id)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->to('/admin/kelola-siswa')->with('error', 'Data siswa tidak ditemukan');
            }
            
            // Hapus foto profil jika ada (jika ada field foto_profil)
            if (isset($siswa['foto_profil']) && $siswa['foto_profil']) {
                $photoPath = WRITEPATH . 'uploads/profile/' . $siswa['foto_profil'];
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            
            // Hapus data log aktivitas siswa terlebih dahulu (karena ada foreign key)
            $this->db->table('log_aktivitas')->where('siswa_id', $id)->delete();
            
            // Hapus data komentar pembimbing yang terkait dengan log siswa ini
            $logIds = $this->db->table('log_aktivitas')->where('siswa_id', $id)->get()->getResultArray();
            if (!empty($logIds)) {
                $logIdsArray = array_column($logIds, 'id');
                $this->db->table('komentar_pembimbing')->whereIn('log_id', $logIdsArray)->delete();
            }
            
            // Hapus data siswa dari database (hard delete)
            if ($this->db->table('siswa')->where('id', $id)->delete()) {
                $this->db->transComplete();
                return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil dihapus');
            } else {
                $this->db->transRollback();
                return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menghapus siswa');
            }
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error in hapusSiswa: ' . $e->getMessage());
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function kelolaPembimbing()
    {
        $pembimbing = $this->db->table('pembimbing')->get()->getResultArray();
        
        $data = [
            'title' => 'Kelola Data Pembimbing - SIMAMANG',
            'pembimbing' => $pembimbing
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
        try {
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
            
            // Mulai transaction
            $this->db->transStart();
            
            // Insert ke tabel pembimbing
            $pembimbingData = [
                'nama' => $request->getPost('nama'),
                'username' => $request->getPost('username'),
                'password' => $password,
                'email' => $request->getPost('email'),
                'no_hp' => $request->getPost('no_hp'),
                'alamat' => $request->getPost('alamat'),
                'instansi' => $request->getPost('instansi'),
                'jabatan' => $request->getPost('jabatan'),
                'bidang_keahlian' => $request->getPost('bidang_keahlian'),
                'status' => 'aktif',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('pembimbing')->insert($pembimbingData);
            
            // Commit transaction
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pembimbing. Silakan coba lagi.');
            }
            
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil ditambahkan');
            
        } catch (\Exception $e) {
            // Rollback jika ada error
            $this->db->transRollback();
            log_message('error', 'Exception in simpanPembimbing: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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
            'username' => 'required|min_length[3]'
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
        
        try {
            // Mulai transaction untuk memastikan konsistensi data
            $this->db->transStart();
            
            // Ambil data pembimbing untuk mendapatkan username
            $pembimbing = $this->db->table('pembimbing')->where('id', $id)->get()->getRowArray();
            if (!$pembimbing) {
                return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Data pembimbing tidak ditemukan');
            }
            
            // Hapus foto profil jika ada (jika ada field foto_profil)
            if (isset($pembimbing['foto_profil']) && $pembimbing['foto_profil']) {
                $photoPath = WRITEPATH . 'uploads/profile/' . $pembimbing['foto_profil'];
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            
            // Hapus data komentar pembimbing terlebih dahulu
            $this->db->table('komentar_pembimbing')->where('pembimbing_id', $id)->delete();
            
            // Hapus data pembimbing dari database (hard delete)
            if ($this->db->table('pembimbing')->where('id', $id)->delete()) {
                $this->db->transComplete();
                return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil dihapus');
            } else {
                $this->db->transRollback();
                return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Gagal menghapus pembimbing');
            }
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error in hapusPembimbing: ' . $e->getMessage());
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
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
        // Ambil semua pembimbing aktif
        $pembimbing = $this->db->table('pembimbing')
                               ->where('status', 'aktif')
                               ->get()
                               ->getResultArray();
        
        // Ambil semua siswa aktif
        $siswa = $this->db->table('siswa')
                          ->where('status', 'aktif')
                          ->get()
                          ->getResultArray();
        
        $data = [
            'title' => 'Atur Bimbingan - SIMAMANG',
            'pembimbing' => $pembimbing,
            'siswa' => $siswa
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
        
        // Ambil siswa yang sudah dibimbing oleh pembimbing ini melalui komentar_pembimbing
        $assignedSiswa = $this->db->table('komentar_pembimbing')
                                  ->select('log_aktivitas.siswa_id, siswa.nama, siswa.nis')
                                  ->join('log_aktivitas', 'log_aktivitas.id = komentar_pembimbing.log_id')
                                  ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                  ->where('komentar_pembimbing.pembimbing_id', $pembimbingId)
                                  ->where('siswa.status', 'aktif')
                                  ->groupBy('log_aktivitas.siswa_id')
                                  ->get()
                                  ->getResultArray();
        
        $assignedIds = array_column($assignedSiswa, 'siswa_id');
        
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
            // Hapus semua komentar pembimbing yang ada untuk pembimbing ini
            $this->db->table('komentar_pembimbing')
                     ->where('pembimbing_id', $pembimbingId)
                     ->delete();
            
            // Buat komentar pembimbing baru untuk siswa yang dipilih
            if (!empty($siswaIds)) {
                // Ambil log aktivitas terbaru dari setiap siswa
                foreach ($siswaIds as $siswaId) {
                    $latestLog = $this->db->table('log_aktivitas')
                                         ->where('siswa_id', $siswaId)
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(1)
                                         ->get()
                                         ->getRowArray();
                    
                    if ($latestLog) {
                        $this->db->table('komentar_pembimbing')->insert([
                            'log_id' => $latestLog['id'],
                            'pembimbing_id' => $pembimbingId,
                            'komentar' => 'Pembimbing ditugaskan untuk membimbing siswa ini',
                            'rating' => null,
                            'status' => 'pending',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
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
            // Cek di semua tabel
            $admin = $this->db->table('admin')->where('username', $username)->countAllResults();
            $pembimbing = $this->db->table('pembimbing')->where('username', $username)->countAllResults();
            $siswa = $this->db->table('siswa')->where('username', $username)->countAllResults();
            
            // Jika ada excludeId, kurangi 1 dari count yang sesuai
            if ($excludeId) {
                // Cek di tabel mana excludeId berada
                $adminCheck = $this->db->table('admin')->where('id', $excludeId)->countAllResults();
                $pembimbingCheck = $this->db->table('pembimbing')->where('id', $excludeId)->countAllResults();
                $siswaCheck = $this->db->table('siswa')->where('id', $excludeId)->countAllResults();
                
                if ($adminCheck > 0) $admin--;
                elseif ($pembimbingCheck > 0) $pembimbing--;
                elseif ($siswaCheck > 0) $siswa--;
            }
            
            return ($admin > 0 || $pembimbing > 0 || $siswa > 0);
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

