<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogAktivitasModel;

class Admin extends BaseController
{
    protected $session;
    protected $userModel;
    protected $logModel;
    protected $pembimbingSiswaModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->logModel = new LogAktivitasModel();
        $this->pembimbingSiswaModel = new \App\Models\PembimbingSiswaModel();
        
        // Cek apakah user sudah login dan role-nya admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        // Hitung statistik
        $totalSiswa = $this->userModel->where('role', 'siswa')->countAllResults();
        $totalPembimbing = $this->userModel->where('role', 'pembimbing')->countAllResults();
        $totalLog = $this->logModel->countAllResults();
        $logPending = $this->logModel->where('status', 'menunggu')->countAllResults();
        
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
        $siswa = $this->userModel->where('role', 'siswa')->findAll();
        
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
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[5]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Hash password
        $password = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        
        $userData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $password,
            'role' => 'siswa',
            'nis' => $request->getPost('nis'),
            'tempat_magang' => $request->getPost('tempat_magang')
        ];
        
        if ($this->userModel->insert($userData)) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan siswa');
        }
    }

    public function editSiswa($id)
    {
        $siswa = $this->userModel->find($id);
        if (!$siswa || $siswa['role'] !== 'siswa') {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Siswa - SIMAMANG',
            'siswa' => $siswa
        ];
        
        return view('admin/form_siswa', $data);
    }

    public function updateSiswa($id)
    {
        $request = service('request');
        $siswa = $this->userModel->find($id);
        
        if (!$siswa || $siswa['role'] !== 'siswa') {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }
        
        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]',
            'nis' => 'required|min_length[5]',
            'tempat_magang' => 'required|min_length[5]'
        ];
        
        // Cek username unik kecuali untuk user yang sedang diedit
        if ($request->getPost('username') !== $siswa['username']) {
            $rules['username'] .= '|is_unique[users.username]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'nis' => $request->getPost('nis'),
            'tempat_magang' => $request->getPost('tempat_magang')
        ];
        
        // Update password jika diisi
        if ($request->getPost('password')) {
            $userData['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        if ($this->userModel->update($id, $userData)) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Data siswa berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data siswa');
        }
    }

    public function hapusSiswa($id)
    {
        $siswa = $this->userModel->find($id);
        if (!$siswa || $siswa['role'] !== 'siswa') {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Siswa tidak ditemukan');
        }
        
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/kelola-siswa')->with('success', 'Siswa berhasil dihapus');
        } else {
            return redirect()->to('/admin/kelola-siswa')->with('error', 'Gagal menghapus siswa');
        }
    }

    public function kelolaPembimbing()
    {
        $pembimbing = $this->userModel->where('role', 'pembimbing')->findAll();
        
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
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Hash password
        $password = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
        
        $userData = [
            'nama' => $request->getPost('nama'),
            'username' => $request->getPost('username'),
            'password' => $password,
            'role' => 'pembimbing'
        ];
        
        if ($this->userModel->insert($userData)) {
            return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Pembimbing berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pembimbing');
        }
    }

    public function aturBimbingan($pembimbingId)
    {
        $pembimbing = $this->userModel->find($pembimbingId);
        if (!$pembimbing || $pembimbing['role'] !== 'pembimbing') {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Pembimbing tidak ditemukan');
        }

        $semuaSiswa = $this->userModel->where('role', 'siswa')->findAll();
        $assignedIds = $this->pembimbingSiswaModel->getSiswaIdsForPembimbing($pembimbingId);

        $data = [
            'title' => 'Atur Bimbingan - SIMAMANG',
            'pembimbing' => $pembimbing,
            'semuaSiswa' => $semuaSiswa,
            'assignedIds' => $assignedIds,
        ];

        return view('admin/atur_bimbingan', $data);
    }

    public function simpanAturBimbingan($pembimbingId)
    {
        $pembimbing = $this->userModel->find($pembimbingId);
        if (!$pembimbing || $pembimbing['role'] !== 'pembimbing') {
            return redirect()->to('/admin/kelola-pembimbing')->with('error', 'Pembimbing tidak ditemukan');
        }

        $request = service('request');
        $siswaIds = $request->getPost('siswa_ids') ?? [];

        // Reset assignment: hapus semua lalu insert yang dipilih
        $this->pembimbingSiswaModel->where('pembimbing_id', $pembimbingId)->delete();
        foreach ($siswaIds as $sid) {
            $this->pembimbingSiswaModel->assignSiswaToPembimbing((int)$pembimbingId, (int)$sid);
        }

        return redirect()->to('/admin/kelola-pembimbing')->with('success', 'Bimbingan berhasil diperbarui');
    }

    public function laporanMagang()
    {
        $siswa = $this->userModel->where('role', 'siswa')->findAll();
        
        $data = [
            'title' => 'Laporan Magang - SIMAMANG',
            'siswa' => $siswa
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
        
        $siswa = $this->userModel->find($siswaId);
        $logs = $this->logModel->getLogByDateRange($siswaId, $startDate, $endDate);
        
        $data = [
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        // Untuk sementara, tampilkan preview
        return view('admin/preview_laporan', $data);
    }
}
