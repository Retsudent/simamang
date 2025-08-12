<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;
use App\Models\KomentarModel;
use App\Models\UserModel;

class Pembimbing extends BaseController
{
    protected $session;
    protected $logModel;
    protected $komentarModel;
    protected $userModel;
    protected $pembimbingSiswaModel;

    public function __construct()
    {
        $this->session = session();
        $this->logModel = new LogAktivitasModel();
        $this->komentarModel = new KomentarModel();
        $this->userModel = new UserModel();
        $this->pembimbingSiswaModel = new \App\Models\PembimbingSiswaModel();
        
        // Cek apakah user sudah login dan role-nya pembimbing
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'pembimbing') {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        $userId = $this->session->get('user_id');

        // Ambil log menunggu & statistik status
        $pendingLogs = $this->logModel->getLogPendingByPembimbing($userId);
        $statusCounts = $this->logModel->countStatusByPembimbing($userId);
        $assignedIds = $this->pembimbingSiswaModel->getSiswaIdsForPembimbing($userId);

        $data = [
            'title' => 'Dashboard Pembimbing - SIMAMANG',
            'pendingLogs' => $pendingLogs,
            'statusCounts' => $statusCounts,
            'assignedCount' => count($assignedIds),
        ];

        return view('pembimbing/dashboard', $data);
    }

    public function aktivitasSiswa()
    {
        // Ambil siswa yang dibimbing oleh pembimbing yang login
        $pembimbingId = $this->session->get('user_id');
        $siswaIds = $this->pembimbingSiswaModel->getSiswaIdsForPembimbing($pembimbingId);
        $siswa = [];
        if (!empty($siswaIds)) {
            $siswa = $this->userModel->where('role', 'siswa')->whereIn('id', $siswaIds)->findAll();
        }
        
        $data = [
            'title' => 'Aktivitas Siswa - SIMAMANG',
            'siswa' => $siswa
        ];
        
        return view('pembimbing/aktivitas_siswa', $data);
    }

    public function logSiswa($siswaId)
    {
        $siswa = $this->userModel->find($siswaId);
        if (!$siswa || $siswa['role'] !== 'siswa') {
            return redirect()->to('/pembimbing/aktivitas-siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Pastikan siswa ini memang dibimbing oleh pembimbing yang login
        $pembimbingId = $this->session->get('user_id');
        $allowed = in_array((int)$siswaId, $this->pembimbingSiswaModel->getSiswaIdsForPembimbing($pembimbingId), true);
        if (!$allowed) {
            return redirect()->to('/pembimbing/aktivitas-siswa')->with('error', 'Anda tidak membimbing siswa tersebut');
        }
        
        $logs = $this->logModel->getLogBySiswa($siswaId);
        
        $data = [
            'title' => 'Log Aktivitas Siswa - SIMAMANG',
            'siswa' => $siswa,
            'logs' => $logs
        ];
        
        return view('pembimbing/log_siswa', $data);
    }

    public function detailLog($logId)
    {
        $log = $this->logModel->getLogWithKomentar($logId);
        if (!$log) {
            return redirect()->to('/pembimbing/dashboard')->with('error', 'Log tidak ditemukan');
        }
        
        $siswa = $this->userModel->find($log['siswa_id']);
        // Pastikan akses log milik siswa yang dibimbing
        $pembimbingId = $this->session->get('user_id');
        $allowed = in_array((int)$log['siswa_id'], $this->pembimbingSiswaModel->getSiswaIdsForPembimbing($pembimbingId), true);
        if (!$allowed) {
            return redirect()->to('/pembimbing/dashboard')->with('error', 'Anda tidak membimbing siswa tersebut');
        }
        
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
        
        // Simpan komentar
        if ($this->komentarModel->saveKomentar($logId, $pembimbingId, $komentar)) {
            // Update status log
            $this->logModel->update($logId, ['status' => $status]);
            
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
        if ($this->logModel->update($logId, ['status' => $status])) {
            return redirect()->back()->with('success', 'Status log berhasil diupdate');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate status log');
        }
    }
}
