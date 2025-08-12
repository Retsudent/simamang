<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Pembimbing extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
        
        // Cek apakah user sudah login dan role-nya pembimbing
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'pembimbing') {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        $userId = $this->session->get('user_id');

        // Ambil log menunggu untuk pembimbing ini
        $pendingLogs = $this->db->table('log_aktivitas')
                                ->select('log_aktivitas.*, siswa.nama as siswa_nama, siswa.nis, siswa.tempat_magang')
                                ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                ->where('log_aktivitas.status', 'menunggu')
                                ->get()
                                ->getResultArray();

        // Hitung statistik status
        $statusCountsRaw = $this->db->table('log_aktivitas')
                                ->select('status, COUNT(*) as total')
                                ->groupBy('status')
                                ->get()
                                ->getResultArray();
        
        // Convert ke format yang diharapkan View
        $statusCounts = [
            'menunggu' => 0,
            'disetujui' => 0,
            'revisi' => 0,
            'ditolak' => 0
        ];
        foreach ($statusCountsRaw as $item) {
            $statusCounts[$item['status']] = (int)$item['total'];
        }

        // Hitung total siswa yang ada log aktivitas
        $assignedCount = $this->db->table('log_aktivitas')
                                 ->select('DISTINCT(siswa_id)')
                                 ->get()
                                 ->getResultArray();

        $data = [
            'title' => 'Dashboard Pembimbing - SIMAMANG',
            'pendingLogs' => $pendingLogs,
            'statusCounts' => $statusCounts,
            'assignedCount' => count($assignedCount),
        ];

        return view('pembimbing/dashboard', $data);
    }

    public function aktivitasSiswa()
    {
        // Ambil semua siswa yang ada log aktivitas (karena tidak ada tabel pembimbing_siswa)
        $siswa = $this->db->table('siswa')
                          ->select('siswa.*, COUNT(log_aktivitas.id) as total_log')
                          ->join('log_aktivitas', 'log_aktivitas.siswa_id = siswa.id', 'left')
                          ->where('siswa.status', 'aktif')
                          ->groupBy('siswa.id')
                          ->get()
                          ->getResultArray();
        
        $data = [
            'title' => 'Aktivitas Siswa - SIMAMANG',
            'siswa' => $siswa
        ];
        
        return view('pembimbing/aktivitas_siswa', $data);
    }

    public function logSiswa($siswaId)
    {
        $siswa = $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/pembimbing/aktivitas-siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Ambil log aktivitas siswa
        $logs = $this->db->table('log_aktivitas')
                         ->where('siswa_id', $siswaId)
                         ->orderBy('created_at', 'DESC')
                         ->get()
                         ->getResultArray();
        
        $data = [
            'title' => 'Log Aktivitas Siswa - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs
        ];
        
        return view('pembimbing/log_siswa', $data);
    }

    public function detailLog($logId)
    {
        $log = $this->db->table('log_aktivitas')
                        ->select('log_aktivitas.*, siswa.nama as nama_siswa')
                        ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                        ->where('log_aktivitas.id', $logId)
                        ->get()
                        ->getRowArray();
        
        if (!$log) {
            return redirect()->to('/pembimbing/dashboard')->with('error', 'Log tidak ditemukan');
        }
        
        $siswa = $this->db->table('siswa')->where('id', $log['siswa_id'])->get()->getRowArray();
        
        $data = [
            'title' => 'Detail Log Aktivitas - SIMAMANG',
            'log' => $log,
            'siswa' => $siswa
        ];
        
        return view('pembimbing/detail_log', $data);
    }

    public function beriKomentar()
    {
        $request = service('request');
        $pembimbingId = $this->session->get('user_id');
        
        $logId = $request->getPost('log_id');
        $komentar = $request->getPost('komentar');
        $status = $request->getPost('status');
        
        if (!$logId || !$komentar || !$status) {
            return redirect()->back()->with('error', 'Semua field harus diisi');
        }
        
        // Validasi status
        if (!in_array($status, ['disetujui', 'revisi'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }
        
        // Simpan komentar ke tabel komentar_pembimbing
        $komentarData = [
            'log_id' => $logId,
            'pembimbing_id' => $pembimbingId,
            'komentar' => $komentar,
            'rating' => $request->getPost('rating') ?? null,
            'status' => 'dibaca'
        ];
        
        if ($this->db->table('komentar_pembimbing')->insert($komentarData)) {
            // Update status log
            $this->db->table('log_aktivitas')->where('id', $logId)->update(['status' => $status]);
            
            return redirect()->back()->with('success', 'Komentar berhasil disimpan');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan komentar');
        }
    }

    public function validasiLog()
    {
        $request = service('request');
        $logId = $request->getPost('log_id');
        $status = $request->getPost('status');
        
        if (!$logId || !$status) {
            return redirect()->back()->with('error', 'Data tidak lengkap');
        }
        
        // Validasi status
        if (!in_array($status, ['disetujui', 'revisi'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }
        
        // Update status log
        if ($this->db->table('log_aktivitas')->where('id', $logId)->update(['status' => $status])) {
            return redirect()->back()->with('success', 'Status log berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate status log');
        }
    }
}
