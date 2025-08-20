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
            redirect()->to('/login')->send();
            exit;
        }
    }

    public function dashboard()
    {
        // Load TimeHelper manually
        helper('TimeHelper');
        
        $userId = $this->session->get('user_id');

        // Ambil data pembimbing berdasarkan user_id dari session
        $pembimbingData = $this->db->table('pembimbing')->where('user_id', $userId)->get()->getRowArray();
        if (!$pembimbingData) {
            return redirect()->to('/login')->with('error', 'Data pembimbing tidak ditemukan');
        }
        $pembimbingId = $pembimbingData['id'];
        
        // Ambil log menunggu untuk siswa yang dibimbing oleh pembimbing ini
        $pendingLogs = $this->db->table('log_aktivitas')
                                ->select('log_aktivitas.*, siswa.nama as siswa_nama, siswa.nis, siswa.tempat_magang')
                                ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                ->where('siswa.pembimbing_id', $pembimbingId)
                                ->where('log_aktivitas.status', 'menunggu')
                                ->get()
                                ->getResultArray();

        // Ambil 5 aktivitas terbaru (semua status) untuk siswa bimbingan pembimbing ini
        $recentActivities = $this->db->table('log_aktivitas')
                                    ->select('log_aktivitas.*, siswa.nama as siswa_nama')
                                    ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                    ->where('siswa.pembimbing_id', $pembimbingId)
                                    ->orderBy('log_aktivitas.created_at', 'DESC')
                                    ->limit(5)
                                    ->get()
                                    ->getResultArray();

        // Hitung statistik status untuk siswa yang dibimbing oleh pembimbing ini
        $statusCountsRaw = $this->db->table('log_aktivitas')
                                ->select('log_aktivitas.status, COUNT(*) as total')
                                ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                                ->where('siswa.pembimbing_id', $pembimbingId)
                                ->groupBy('log_aktivitas.status')
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

        // Hitung total siswa yang dibimbing oleh pembimbing ini
        $assignedCount = $this->db->table('siswa')
                                 ->where('pembimbing_id', $pembimbingId)
                                 ->where('status', 'aktif')
                                 ->countAllResults();

        // Total log untuk seluruh siswa bimbingan
        $totalLog = $this->db->table('log_aktivitas')
                             ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                             ->where('siswa.pembimbing_id', $pembimbingId)
                             ->countAllResults();

        $logPending = (int) ($statusCounts['menunggu'] ?? 0);
        $logApproved = (int) ($statusCounts['disetujui'] ?? 0);
        $logRevisi = (int) ($statusCounts['revisi'] ?? 0);

        $data = [
            'title' => 'Dashboard Pembimbing - SIMAMANG',
            'pendingLogs' => $pendingLogs,
            'statusCounts' => $statusCounts,
            'assignedCount' => $assignedCount,
            // Tambahkan alias sesuai yang dipakai View
            'totalSiswa' => $assignedCount,
            'totalLog' => $totalLog,
            'logPending' => $logPending,
            'logApproved' => $logApproved,
            'logRevisi' => $logRevisi,
            'recentActivities' => $recentActivities,
        ];

        return view('pembimbing/dashboard', $data);
    }

    public function aktivitasSiswa()
    {
        $userId = $this->session->get('user_id');

        // Ambil data pembimbing berdasarkan user_id dari session
        $pembimbingData = $this->db->table('pembimbing')->where('user_id', $userId)->get()->getRowArray();
        if (!$pembimbingData) {
            return redirect()->to('/login')->with('error', 'Data pembimbing tidak ditemukan');
        }
        $pembimbingId = $pembimbingData['id'];

        $request = service('request');
        $search = $request->getGet('search');
        $statusFilter = $request->getGet('status');
        $tempatMagang = $request->getGet('tempat_magang');
        
        $builder = $this->db->table('siswa')
            ->select(
                "siswa.id, siswa.nama, siswa.username, siswa.nis, siswa.tempat_magang, siswa.status, " .
                "COUNT(log_aktivitas.id) as total_log, " .
                "SUM(CASE WHEN log_aktivitas.status = 'menunggu' THEN 1 ELSE 0 END) as menunggu_count, " .
                "SUM(CASE WHEN log_aktivitas.status = 'disetujui' THEN 1 ELSE 0 END) as disetujui_count, " .
                "SUM(CASE WHEN log_aktivitas.status = 'revisi' THEN 1 ELSE 0 END) as revisi_count"
            )
            ->join('log_aktivitas', 'log_aktivitas.siswa_id = siswa.id', 'left')
            ->where('siswa.pembimbing_id', $pembimbingId)
            ->where('siswa.status', 'aktif')
            ->groupBy('siswa.id, siswa.nama, siswa.username, siswa.nis, siswa.tempat_magang, siswa.status');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('siswa.nama', $search)
                ->orLike('siswa.nis', $search)
                ->orLike('siswa.tempat_magang', $search)
            ->groupEnd();
        }

        if (!empty($tempatMagang)) {
            $builder->where('siswa.tempat_magang', $tempatMagang);
        }

        if (!empty($statusFilter) && in_array($statusFilter, ['menunggu', 'disetujui', 'revisi'])) {
            $havingExpr = "SUM(CASE WHEN log_aktivitas.status = '" . $statusFilter . "' THEN 1 ELSE 0 END) > 0";
            $builder->having($havingExpr, null, false);
        }

        $siswa = $builder->get()->getResultArray();
        
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

        $userId = $this->session->get('user_id');
        $request = service('request');
        $status = $request->getGet('status');
        $startDate = $request->getGet('start_date');
        $endDate = $request->getGet('end_date');

        // Ambil data pembimbing berdasarkan user_id dari session
        $pembimbingData = $this->db->table('pembimbing')->where('user_id', $userId)->get()->getRowArray();
        if (!$pembimbingData) {
            return redirect()->to('/login')->with('error', 'Data pembimbing tidak ditemukan');
        }
        $pembimbingId = $pembimbingData['id'];
        
        $builder = $this->db->table('log_aktivitas')
                             ->select('log_aktivitas.*, kp.komentar, kp.status_validasi, kp.created_at as komentar_at')
                             ->join('komentar_pembimbing kp', 'kp.log_id = log_aktivitas.id AND kp.pembimbing_id = ' . (int) $pembimbingId, 'left')
                             ->where('log_aktivitas.siswa_id', $siswaId);

        if (!empty($status)) {
            $builder->where('log_aktivitas.status', $status);
        }
        if (!empty($startDate)) {
            $builder->where('log_aktivitas.tanggal >=', $startDate);
        }
        if (!empty($endDate)) {
            $builder->where('log_aktivitas.tanggal <=', $endDate);
        }

        $logs = $builder->orderBy('log_aktivitas.created_at', 'DESC')->get()->getResultArray();

        $data = [
            'title' => 'Log Aktivitas Siswa - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs
        ];

        return view('pembimbing/log_siswa', $data);
    }

    public function detailLog($logId)
    {
        try {
            $log = $this->db->table('log_aktivitas')
                            ->select('log_aktivitas.*, siswa.nama as nama_siswa, komentar_pembimbing.komentar, komentar_pembimbing.created_at as komentar_at')
                            ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                            ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
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
            
        } catch (\Exception $e) {
            log_message('error', 'Error in detailLog: ' . $e->getMessage());
            return redirect()->to('/pembimbing/dashboard')->with('error', 'Terjadi kesalahan saat memuat detail log.');
        }
    }

    public function beriKomentar()
    {
        try {
            $request = service('request');
            $userId = $this->session->get('user_id');
            
            // Ambil data pembimbing berdasarkan user_id dari session
            $pembimbingData = $this->db->table('pembimbing')->where('user_id', $userId)->get()->getRowArray();
            if (!$pembimbingData) {
                return redirect()->back()->with('error', 'Data pembimbing tidak ditemukan');
            }
            $pembimbingId = $pembimbingData['id'];
            
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
            
            // Cek apakah log sudah ada komentar
            $existingComment = $this->db->table('komentar_pembimbing')
                                       ->where('log_id', $logId)
                                       ->where('pembimbing_id', $pembimbingId)
                                       ->get()
                                       ->getRowArray();
            
            if ($existingComment) {
                // Update komentar yang sudah ada
                $this->db->table('komentar_pembimbing')
                         ->where('log_id', $logId)
                         ->where('pembimbing_id', $pembimbingId)
                         ->update([
                             'komentar' => $komentar,
                             'status_validasi' => $status
                         ]);
            } else {
                // Insert komentar baru
                $komentarData = [
                    'log_id' => $logId,
                    'pembimbing_id' => $pembimbingId,
                    'komentar' => $komentar,
                    'status_validasi' => $status
                ];
                
                $this->db->table('komentar_pembimbing')->insert($komentarData);
            }
            
            // Update status log
            $this->db->table('log_aktivitas')->where('id', $logId)->update(['status' => $status]);
            
            return redirect()->back()->with('success', 'Komentar berhasil disimpan');
            
        } catch (\Exception $e) {
            log_message('error', 'Error in beriKomentar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
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

    public function komentar()
    {
        $userId = $this->session->get('user_id');
        
        // Ambil data pembimbing berdasarkan user_id dari session
        $pembimbingData = $this->db->table('pembimbing')->where('user_id', $userId)->get()->getRowArray();
        if (!$pembimbingData) {
            return redirect()->to('/login')->with('error', 'Data pembimbing tidak ditemukan');
        }
        $pembimbingId = $pembimbingData['id'];
        
        // Tampilkan log yang berstatus menunggu untuk siswa bimbingan pembimbing ini
        $logs = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, siswa.nama as siswa_nama, siswa.nis')
                         ->join('siswa', 'siswa.id = log_aktivitas.siswa_id')
                         ->where('siswa.pembimbing_id', $pembimbingId)
                         ->where('log_aktivitas.status', 'menunggu')
                         ->orderBy('log_aktivitas.created_at', 'DESC')
                         ->get()
                         ->getResultArray();

        return view('pembimbing/komentar', [
            'title' => 'Komentar & Validasi - SIMAMANG',
            'logs' => $logs
        ]);
    }
}
