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
            redirect()->to('/login')->send();
            exit;
        }
    }

    public function dashboard()
    {
        // Load TimeHelper manually
        helper('TimeHelper');
        
        $userId = $this->session->get('user_id');
        
        // Ambil data siswa berdasarkan user_id dari session
        $siswa_info = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        
        // Ambil data pembimbing jika ada
        $pembimbing_info = null;
        if ($siswa_info && $siswa_info['pembimbing_id']) {
            $pembimbing_info = $this->db->table('pembimbing')
                                        ->where('id', $siswa_info['pembimbing_id'])
                                        ->get()
                                        ->getRowArray();
        }
        
        // Statistik log aktivitas berdasarkan siswa_id
        $siswaId = $siswa_info ? $siswa_info['id'] : 0;
        
        $totalLog = $this->db->table('log_aktivitas')
                              ->where('siswa_id', $siswaId)
                              ->countAllResults();
        
        $logApproved = $this->db->table('log_aktivitas')
                              ->where('siswa_id', $siswaId)
                              ->where('status', 'disetujui')
                              ->countAllResults();
        
        $logPending = $this->db->table('log_aktivitas')
                             ->where('siswa_id', $siswaId)
                             ->where('status', 'menunggu')
                             ->countAllResults();
        
        $logRevisi = $this->db->table('log_aktivitas')
                             ->where('siswa_id', $siswaId)
                             ->where('status', 'revisi')
                             ->countAllResults();
        
        // Ambil aktivitas terbaru (5 log terakhir)
        $recentActivities = $this->db->table('log_aktivitas')
                                      ->where('siswa_id', $siswaId)
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray();
        
        $data = [
            'title' => 'Dashboard Siswa - SIMAMANG',
            'siswa_info' => $siswa_info,
            'pembimbing_info' => $pembimbing_info,
            'totalLog' => $totalLog,
            'logApproved' => $logApproved,
            'logPending' => $logPending,
            'logRevisi' => $logRevisi,
            'recentActivities' => $recentActivities
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
            
            // Cek apakah siswa dengan user_id dari session ada
            $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
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
                'siswa_id' => $siswa['id'],
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
            
            // Ambil data siswa berdasarkan user_id dari session
            $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
            }
            
            // Build query
            $query = $this->db->table('log_aktivitas')
                              ->select('log_aktivitas.*, komentar_pembimbing.komentar, pembimbing.nama as nama_pembimbing')
                              ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                              ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                              ->where('log_aktivitas.siswa_id', $siswa['id']);
            
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
            
            // Ambil data siswa berdasarkan user_id dari session
            $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
            }
            
            // Ambil log dengan komentar pembimbing
            $log = $this->db->table('log_aktivitas')
                            ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as nama_pembimbing')
                            ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                            ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                            ->where('log_aktivitas.id', $id)
                            ->get()
                            ->getRowArray();
            
            // Pastikan log milik siswa yang sedang login
            if (!$log || $log['siswa_id'] != $siswa['id']) {
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

    public function editLog($id)
    {
        try {
            $userId = $this->session->get('user_id');
            $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->to('/siswa/riwayat')->with('error', 'Data siswa tidak ditemukan');
            }
            $log = $this->db->table('log_aktivitas')->where('id', $id)->get()->getRowArray();
            if (!$log || $log['siswa_id'] != $siswa['id']) {
                return redirect()->to('/siswa/riwayat')->with('error', 'Log tidak ditemukan');
            }
            // Ambil komentar terakhir (opsional)
            $comment = $this->db->table('komentar_pembimbing')
                                ->select('komentar, created_at as komentar_at, pembimbing_id')
                                ->where('log_id', $id)
                                ->orderBy('created_at', 'DESC')
                                ->get()->getRowArray();
            if ($comment) {
                $pemb = $this->db->table('pembimbing')->select('nama')->where('id', $comment['pembimbing_id'])->get()->getRowArray();
                $log['komentar'] = $comment['komentar'];
                $log['komentar_at'] = $comment['komentar_at'];
                $log['pembimbing_nama'] = $pemb['nama'] ?? 'Pembimbing';
            }
            return view('siswa/edit_log', [ 'title' => 'Edit Log Aktivitas', 'log' => $log ]);
        } catch (\Throwable $e) {
            log_message('error', 'Error in editLog: ' . $e->getMessage());
            return redirect()->to('/siswa/riwayat')->with('error', 'Terjadi kesalahan saat memuat halaman edit.');
        }
    }

    public function updateLog($id)
    {
        try {
            $request = service('request');
            $userId = $this->session->get('user_id');
            $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
            if (!$siswa) {
                return redirect()->to('/siswa/riwayat')->with('error', 'Data siswa tidak ditemukan');
            }
            $log = $this->db->table('log_aktivitas')->where('id', $id)->get()->getRowArray();
            if (!$log || $log['siswa_id'] != $siswa['id']) {
                return redirect()->to('/siswa/riwayat')->with('error', 'Log tidak ditemukan');
            }

            // Validasi sederhana
            $rules = [
                'tanggal' => 'required|valid_date',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'uraian' => 'required|min_length[10]'
            ];
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Handle file upload opsional
            $file = $request->getFile('bukti');
            $buktiName = $log['bukti'];
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if ($file->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()->withInput()->with('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                }
                $allowedTypes = ['image/jpeg','image/png','image/jpg','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->withInput()->with('error', 'Tipe file tidak diizinkan.');
                }
                $originalName = $file->getName();
                $timestamp = date('Y-m-d_H-i-s');
                $newName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $timestamp . '_' . $originalName);
                $uploadDir = WRITEPATH . 'uploads/bukti';
                if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
                $file->move($uploadDir, $newName, true);
                $buktiName = $newName;
            }

            // Update existing log, reset status ke menunggu agar tidak duplikasi
            $updateData = [
                'tanggal' => $request->getPost('tanggal'),
                'jam_mulai' => $request->getPost('jam_mulai'),
                'jam_selesai' => $request->getPost('jam_selesai'),
                'uraian' => $request->getPost('uraian'),
                'bukti' => $buktiName,
                'status' => 'menunggu',
            ];
            $this->db->table('log_aktivitas')->where('id', $id)->update($updateData);

            return redirect()->to('/siswa/riwayat')->with('success', 'Log berhasil diperbarui dan dikirim untuk review ulang.');
        } catch (\Throwable $e) {
            log_message('error', 'Error in updateLog: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui log.');
        }
    }

    public function laporan()
    {
        $data = [
            'title' => 'Cetak Laporan - SIMAMANG'
        ];
        
        return view('siswa/laporan', $data);
    }

    public function laporanMingguIni()
    {
        $userId = $this->session->get('user_id');
        
        // Hitung tanggal awal dan akhir minggu ini
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        // Ambil data siswa dan log aktivitas minggu ini
        $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $logs = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                         ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                         ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                         ->where('log_aktivitas.siswa_id', $siswa['id'])
                         ->where('log_aktivitas.tanggal >=', $startOfWeek)
                         ->where('log_aktivitas.tanggal <=', $endOfWeek)
                         ->orderBy('log_aktivitas.tanggal', 'ASC')
                         ->get()
                         ->getResultArray();
        
        $data = [
            'title' => 'Laporan Minggu Ini - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => $startOfWeek,
            'endDate' => $endOfWeek,
            'period' => 'Minggu Ini'
        ];
        
        return view('siswa/laporan_cepat', $data);
    }
    
    public function laporanBulanIni()
    {
        $userId = $this->session->get('user_id');
        
        // Hitung tanggal awal dan akhir bulan ini
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        // Ambil data siswa dan log aktivitas bulan ini
        $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $logs = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                         ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                         ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                         ->where('log_aktivitas.siswa_id', $siswa['id'])
                         ->where('log_aktivitas.tanggal >=', $startOfMonth)
                         ->where('log_aktivitas.tanggal <=', $endOfMonth)
                         ->orderBy('log_aktivitas.tanggal', 'ASC')
                         ->get()
                         ->getResultArray();
        
        $data = [
            'title' => 'Laporan Bulan Ini - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => $startOfMonth,
            'endDate' => $endOfMonth,
            'period' => 'Bulan Ini'
        ];
        
        return view('siswa/laporan_cepat', $data);
    }
    
    public function laporanSemuaAktivitas()
    {
        $userId = $this->session->get('user_id');
        
        // Ambil data siswa dan semua log aktivitas
        $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        if (!$siswa) {
            return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
        }
        
        $logs = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                         ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                         ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                         ->where('log_aktivitas.siswa_id', $siswa['id'])
                         ->orderBy('log_aktivitas.tanggal', 'ASC')
                         ->get()
                         ->getResultArray();
        
        $data = [
            'title' => 'Laporan Semua Aktivitas - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => null,
            'endDate' => null,
            'period' => 'Semua Aktivitas'
        ];
        
        return view('siswa/laporan_cepat', $data);
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
        
        // Ambil data siswa dan log beserta komentar (gunakan user_id -> siswa.id)
        $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        $siswaId = $siswa ? $siswa['id'] : 0;
        $logs = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                         ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                         ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                         ->where('log_aktivitas.siswa_id', $siswaId)
                         ->where('log_aktivitas.tanggal >=', $startDate)
                         ->where('log_aktivitas.tanggal <=', $endDate)
                         ->orderBy('log_aktivitas.tanggal', 'ASC')
                         ->get()
                         ->getResultArray();

        // Hitung ringkasan untuk PDF (kurangi kerja di template)
        $disetujuiCount = 0; $revisiCount = 0; $menungguCount = 0; $totalHours = 0.0;
        foreach ($logs as $lg) {
            $status = $lg['status'] ?? 'menunggu';
            if ($status === 'disetujui') $disetujuiCount++;
            elseif ($status === 'revisi') $revisiCount++;
            else $menungguCount++;
            $start = strtotime($lg['jam_mulai']);
            $end = strtotime($lg['jam_selesai']);
            if ($start && $end && $end > $start) {
                $totalHours += ($end - $start) / 3600;
            }
        }

        // Render view minimal khusus PDF (tanpa layout & asset eksternal)
        $html = view('siswa/pdf_laporan', [
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'disetujuiCount' => $disetujuiCount,
            'revisiCount' => $revisiCount,
            'menungguCount' => $menungguCount,
            'totalHours' => round($totalHours, 1)
        ]);

        // Generate PDF menggunakan Dompdf
        try {
            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', false); // hindari request eksternal agar cepat
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Pastikan tidak ada output lain yang bocor ke respons
            if (ob_get_level() > 0) { @ob_end_clean(); }

            $filename = 'Laporan_Magang_' . ($siswa['nama'] ?? 'Siswa') . '_' . date('Ymd_His') . '.pdf';
            $pdf = $dompdf->output();
            return $this->response
                ->setStatusCode(200)
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($pdf);
        } catch (\Throwable $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            // Fallback ke preview jika ada masalah PDF
            return view('siswa/preview_laporan', [
                'title' => 'Laporan Magang - ' . ($siswa['nama'] ?? 'Siswa'),
                'siswa' => $siswa,
                'logs' => $logs,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
        }
    }

    public function generateLaporanRapid()
    {
        $request = service('request');
        $userId = $this->session->get('user_id');
        $period = $request->getGet('period');
        
        // Tentukan rentang tanggal berdasarkan period
        $startDate = null;
        $endDate = null;
        $periodTitle = '';
        
        switch ($period) {
            case 'week':
                $startDate = date('Y-m-d', strtotime('monday this week'));
                $endDate = date('Y-m-d', strtotime('sunday this week'));
                $periodTitle = 'Minggu Ini';
                break;
            case 'month':
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $periodTitle = 'Bulan Ini';
                break;
            case 'all':
                $startDate = null;
                $endDate = null;
                $periodTitle = 'Semua Aktivitas';
                break;
            default:
                return redirect()->to('/siswa/laporan')->with('error', 'Periode tidak valid');
        }
        
        // Ambil data siswa dan log aktivitas (gunakan user_id -> siswa.id)
        $siswa = $this->db->table('siswa')->where('user_id', $userId)->get()->getRowArray();
        $siswaId = $siswa ? $siswa['id'] : 0;
        
        $query = $this->db->table('log_aktivitas')
                         ->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.status_validasi, pembimbing.nama as pembimbing_nama')
                         ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
                         ->join('pembimbing', 'pembimbing.id = komentar_pembimbing.pembimbing_id', 'left')
                         ->where('log_aktivitas.siswa_id', $siswaId);
        
        if ($startDate && $endDate) {
            $query->where('log_aktivitas.tanggal >=', $startDate)
                  ->where('log_aktivitas.tanggal <=', $endDate);
        }
        
        $logs = $query->orderBy('log_aktivitas.tanggal', 'ASC')
                      ->get()
                      ->getResultArray();

        // Hitung ringkasan untuk PDF
        $disetujuiCount = 0; $revisiCount = 0; $menungguCount = 0; $totalHours = 0.0;
        foreach ($logs as $lg) {
            $status = $lg['status'] ?? 'menunggu';
            if ($status === 'disetujui') $disetujuiCount++;
            elseif ($status === 'revisi') $revisiCount++;
            else $menungguCount++;
            $start = strtotime($lg['jam_mulai']);
            $end = strtotime($lg['jam_selesai']);
            if ($start && $end && $end > $start) {
                $totalHours += ($end - $start) / 3600;
            }
        }

        // Render view minimal khusus PDF
        $html = view('siswa/pdf_laporan', [
            'siswa' => $siswa,
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'disetujuiCount' => $disetujuiCount,
            'revisiCount' => $revisiCount,
            'menungguCount' => $menungguCount,
            'totalHours' => round($totalHours, 1),
            'periodTitle' => $periodTitle
        ]);

        // Generate PDF menggunakan Dompdf
        try {
            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            if (ob_get_level() > 0) { @ob_end_clean(); }

            $filename = 'Laporan_' . $periodTitle . ' - ' . ($siswa['nama'] ?? 'Siswa') . '_' . date('Ymd_His') . '.pdf';
            $pdf = $dompdf->output();
            return $this->response
                ->setStatusCode(200)
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($pdf);
        } catch (\Throwable $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            // Fallback ke preview
            return view('siswa/preview_laporan', [
                'title' => 'Laporan ' . $periodTitle . ' - ' . ($siswa['nama'] ?? 'Siswa'),
                'siswa' => $siswa,
                'logs' => $logs,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'periodTitle' => $periodTitle
            ]);
        }
    }
}
