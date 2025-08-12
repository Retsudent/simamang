<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Siswa extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
        
        // Cek apakah user sudah login dan role-nya siswa
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        $userId = $this->session->get('user_id');
        $table = $this->session->get('table');
        
        // Ambil data user dari tabel yang sesuai
        $user = $this->db->table($table)->where('id', $userId)->get()->getRowArray();
        
        // Ambil log terbaru (5 log terakhir)
        $recentLogs = $this->db->table('log_aktivitas')
                                ->where('siswa_id', $userId)
                                ->orderBy('created_at', 'DESC')
                                ->limit(5)
                                ->get()
                                ->getResultArray();
        
        $data = [
            'title' => 'Dashboard Siswa - SIMAMANG',
            'user' => $user,
            'recentLogs' => $recentLogs
        ];
        
        return view('siswa/dashboard', $data);
    }

    public function inputLog()
    {
        $data = [
            'title' => 'Input Log Aktivitas - SIMAMANG'
        ];
        
        return view('siswa/input_log', $data);
    }

    public function saveLog()
    {
        $request = service('request');
        $userId = $this->session->get('user_id');
        
        // Validasi input
        $rules = [
            'tanggal' => 'required|valid_date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'uraian' => 'required|min_length[10]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Pastikan direktori upload ada
        $uploadDir = WRITEPATH . 'uploads/bukti';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        // Handle file upload
        $bukti = null;
        $file = $request->getFile('bukti');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $bukti = $newName;
        }
        
        // Simpan log
        $logData = [
            'siswa_id' => $userId,
            'tanggal' => $request->getPost('tanggal'),
            'jam_mulai' => $request->getPost('jam_mulai'),
            'jam_selesai' => $request->getPost('jam_selesai'),
            'uraian' => $request->getPost('uraian'),
            'bukti' => $bukti,
            'status' => 'menunggu'
        ];
        
        if ($this->db->table('log_aktivitas')->insert($logData)) {
            return redirect()->to('/siswa/dashboard')->with('success', 'Log aktivitas berhasil disimpan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan log aktivitas');
        }
    }

    public function riwayat()
    {
        $userId = $this->session->get('user_id');
        $logs = $this->db->table('log_aktivitas')
                         ->where('siswa_id', $userId)
                         ->orderBy('created_at', 'DESC')
                         ->get()
                         ->getResultArray();
        
        $data = [
            'title' => 'Riwayat Aktivitas - SIMAMANG',
            'logs' => $logs
        ];
        
        return view('siswa/riwayat', $data);
    }

    public function detailLog($id)
    {
        $userId = $this->session->get('user_id');
        
        // Ambil log dengan komentar pembimbing
        $log = $this->db->table('log_aktivitas')
                        ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.rating, pembimbing.nama as nama_pembimbing')
                        ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                        ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                        ->where('log_aktivitas.id', $id)
                        ->get()
                        ->getRowArray();
        
        // Pastikan log milik siswa yang sedang login
        if (!$log || $log['siswa_id'] != $userId) {
            return redirect()->to('/siswa/riwayat')->with('error', 'Log tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Log Aktivitas - SIMAMANG',
            'log' => $log
        ];
        
        return view('siswa/detail_log', $data);
    }

    public function laporan()
    {
        $data = [
            'title' => 'Cetak Laporan - SIMAMANG'
        ];
        
        return view('siswa/laporan', $data);
    }

    public function generateLaporan()
    {
        $request = service('request');
        $userId = $this->session->get('user_id');
        
        $startDate = $request->getPost('start_date');
        $endDate = $request->getPost('end_date');
        
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir harus diisi');
        }
        
        $table = $this->session->get('table');
        $user = $this->db->table($table)->where('id', $userId)->get()->getRowArray();
        $logs = $this->db->table('log_aktivitas')
                         ->where('siswa_id', $userId)
                         ->where('tanggal >=', $startDate)
                         ->where('tanggal <=', $endDate)
                         ->orderBy('tanggal', 'ASC')
                         ->get()
                         ->getResultArray();
        
        // Generate PDF (akan diimplementasikan nanti)
        $data = [
            'user' => $user,
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        // Untuk sementara, tampilkan preview
        return view('siswa/preview_laporan', $data);
    }
}
