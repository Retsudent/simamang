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
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        // Hitung statistik dari tabel terpisah
        $totalSiswa = $this->db->table('siswa')->where('status', 'aktif')->countAllResults();
        $totalPembimbing = $this->db->table('pembimbing')->where('status', 'aktif')->countAllResults();
        $totalLog = $this->db->table('log_aktivitas')->countAllResults();
        $logPending = $this->db->table('log_aktivitas')->where('status', 'menunggu')->countAllResults();
        
        $data = [
            'title' => 'Dashboard Admin - SIMAMANG',
            'totalSiswa' => $totalSiswa,
            'totalPembimbing' => $totalPembimbing,
            'totalLog' => $totalLog,
            'logPending' => $logPending
        ];
        
        return view('admin/dashboard', $data);
    }

    public function kelolaSiswa()
    {
        $siswa = $this->db->table('siswa')->where('status', 'aktif')->get()->getResultArray();
        
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
        $request = service('request');
        
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[5]'
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
            'status' => 'aktif'
        ];
        
        if ($this->db->table('siswa')->insert($siswaData)) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan siswa');
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
            'alamat_magang' => $request->getPost('tempat_magang')
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
        
        // Soft delete - ubah status menjadi nonaktif
        if ($this->db->table('siswa')->where('id', $id)->update(['status' => 'nonaktif'])) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil dihapus');
        } else {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menghapus siswa');
        }
    }

    public function kelolaPembimbing()
    {
        $pembimbing = $this->db->table('pembimbing')->where('status', 'aktif')->get()->getResultArray();
        
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
        $request = service('request');
        
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
            'instansi' => 'required|min_length[3]',
            'jabatan' => 'required|min_length[3]'
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
            'alamat' => $request->getPost('alamat'),
            'instansi' => $request->getPost('instansi'),
            'jabatan' => $request->getPost('jabatan'),
            'bidang_keahlian' => $request->getPost('bidang_keahlian'),
            'status' => 'aktif'
        ];
        
        if ($this->db->table('pembimbing')->insert($pembimbingData)) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil ditambahkan');
        } else {
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
        
        // Soft delete - ubah status menjadi nonaktif
        if ($this->db->table('pembimbing')->where('id', $id)->update(['status' => 'nonaktif'])) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil dihapus');
        } else {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Gagal menghapus pembimbing');
        }
    }

    public function laporanMagang()
    {
        $laporan = $this->db->table('laporan_magang')
                            ->select('laporan_magang.*, siswa.nama as nama_siswa')
                            ->join('siswa', 'siswa.id = laporan_magang.siswa_id')
                            ->get()
                            ->getResultArray();
        
        $data = [
            'title' => 'Laporan Magang - SIMAMANG',
            'laporan' => $laporan
        ];
        
        return view('admin/laporan_magang', $data);
    }

    public function aturBimbingan()
    {
        // Tampilkan halaman pengaturan bimbingan
        $data = [
            'title' => 'Atur Bimbingan - SIMAMANG'
        ];
        
        return view('admin/atur_bimbingan', $data);
    }

    private function isUsernameExists($username, $excludeId = null)
    {
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
    }

    private function isNISExists($nis, $excludeId = null)
    {
        $query = $this->db->table('siswa')->where('nis', $nis);
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        return $query->countAllResults() > 0;
    }
}

