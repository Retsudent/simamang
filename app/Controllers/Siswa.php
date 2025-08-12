<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class Siswa extends BaseController
{
    protected $session;
    protected $logModel;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->logModel = new LogAktivitasModel();
        $this->userModel = new UserModel();
        
        // Cek apakah user sudah login dan role-nya siswa
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Ambil log terbaru (5 log terakhir)
        $recentLogs = $this->logModel->getLogBySiswa($userId, 5);
        
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
        
        if ($this->logModel->insert($logData)) {
            return redirect()->to('/siswa/dashboard')->with('success', 'Log aktivitas berhasil disimpan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan log aktivitas');
        }
    }

    public function riwayat()
    {
        $userId = $this->session->get('user_id');
        $logs = $this->logModel->getLogBySiswa($userId);
        
        $data = [
            'title' => 'Riwayat Aktivitas - SIMAMANG',
            'logs' => $logs
        ];
        
        return view('siswa/riwayat', $data);
    }

    public function detailLog($id)
    {
        $userId = $this->session->get('user_id');
        $log = $this->logModel->getLogWithKomentar($id);
        
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
        
        $user = $this->userModel->find($userId);
        $logs = $this->logModel->getLogByDateRange($userId, $startDate, $endDate);
        
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
