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
        
        // Ambil data siswa
        $siswa_info = $this->db->table('siswa')->where('id', $userId)->get()->getRowArray();
        
        // Ambil data pembimbing jika ada
        $pembimbing_info = null;
        if ($siswa_info && $siswa_info['pembimbing_id']) {
            $pembimbing_info = $this->db->table('pembimbing')
                                        ->where('id', $siswa_info['pembimbing_id'])
                                        ->get()
                                        ->getRowArray();
        }
        
        // Statistik log aktivitas
        $total_log = $this->db->table('log_aktivitas')
                              ->where('siswa_id', $userId)
                              ->countAllResults();
        
        $log_bulan_ini = $this->db->table('log_aktivitas')
                                  ->where('siswa_id', $userId)
                                  ->where('EXTRACT(MONTH FROM tanggal)', date('m'))
                                  ->where('EXTRACT(YEAR FROM tanggal)', date('Y'))
                                  ->countAllResults();
        
        $disetujui = $this->db->table('log_aktivitas')
                              ->where('siswa_id', $userId)
                              ->where('status', 'disetujui')
                              ->countAllResults();
        
        $menunggu = $this->db->table('log_aktivitas')
                             ->where('siswa_id', $userId)
                             ->where('status', 'menunggu')
                             ->countAllResults();
        
        // Ambil aktivitas terbaru (5 log terakhir)
        $recent_activities = $this->db->table('log_aktivitas')
                                      ->where('siswa_id', $userId)
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray();
        
        $data = [
            'title' => 'Dashboard Siswa - SIMAMANG',
            'siswa_info' => $siswa_info,
            'pembimbing_info' => $pembimbing_info,
            'total_log' => $total_log,
            'log_bulan_ini' => $log_bulan_ini,
            'disetujui' => $disetujui,
            'menunggu' => $menunggu,
            'recent_activities' => $recent_activities
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
        try {
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
            
            // Cek apakah siswa dengan ID tersebut ada
            $siswa = $this->db->table('siswa')->where('id', $userId)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->back()->withInput()->with('error', 'Data siswa tidak ditemukan');
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
                // Validasi ukuran file (max 2MB)
                if ($file->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()->withInput()->with('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                }
                
                // Validasi tipe file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->withInput()->with('error', 'Tipe file tidak diizinkan. Gunakan JPG, PNG, PDF, atau DOC.');
                }
                
                // Gunakan nama asli file dengan timestamp untuk menghindari konflik
                $originalName = $file->getName();
                $extension = $file->getExtension();
                $timestamp = date('Y-m-d_H-i-s');
                $newName = $timestamp . '_' . $originalName;
                
                // Bersihkan nama file dari karakter yang tidak aman
                $newName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $newName);
                
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
            
            $result = $this->db->table('log_aktivitas')->insert($logData);
            
            if ($result) {
                return redirect()->to('/siswa/dashboard')->with('success', 'Log aktivitas berhasil disimpan');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan log aktivitas');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving log: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function riwayat()
    {
        try {
            $userId = $this->session->get('user_id');
            $request = service('request');
            
            // Build query
            $query = $this->db->table('log_aktivitas')
                              ->select('log_aktivitas.*, komentar_pembimbing.komentar, pembimbing.nama as nama_pembimbing')
                              ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                              ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                              ->where('log_aktivitas.siswa_id', $userId);
            
            // Apply filters
            $status = $request->getGet('status');
            if ($status) {
                $query->where('log_aktivitas.status', $status);
            }
            
            $startDate = $request->getGet('start_date');
            if ($startDate) {
                $query->where('log_aktivitas.tanggal >=', $startDate);
            }
            
            $endDate = $request->getGet('end_date');
            if ($endDate) {
                $query->where('log_aktivitas.tanggal <=', $endDate);
            }
            
            $logs = $query->orderBy('log_aktivitas.created_at', 'DESC')
                          ->get()
                          ->getResultArray();
            
            $data = [
                'title' => 'Riwayat Aktivitas - SIMAMANG',
                'logs' => $logs
            ];
            
            return view('siswa/riwayat', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in riwayat: ' . $e->getMessage());
            return redirect()->to('/siswa/dashboard')->with('error', 'Terjadi kesalahan saat memuat riwayat aktivitas.');
        }
    }

    public function detailLog($id)
    {
        try {
            $userId = $this->session->get('user_id');
            
            // Ambil log dengan komentar pembimbing
            $log = $this->db->table('log_aktivitas')
                            ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as nama_pembimbing')
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
            
        } catch (\Exception $e) {
            log_message('error', 'Error in detailLog: ' . $e->getMessage());
            return redirect()->to('/siswa/riwayat')->with('error', 'Terjadi kesalahan saat memuat detail log.');
        }
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
