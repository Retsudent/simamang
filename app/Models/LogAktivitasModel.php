<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table      = 'log_aktivitas';
    protected $primaryKey = 'id';
    protected $useTimestamps = false; // created_at handled by DB default
    protected $allowedFields = [
        'siswa_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'uraian', 'bukti', 'status'
    ];

    protected $returnType = 'array';

    public function getLogBySiswa($siswaId, $limit = null)
    {
        $builder = $this->where('siswa_id', $siswaId)
                        ->orderBy('tanggal', 'DESC')
                        ->orderBy('jam_mulai', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function getLogByDateRange($siswaId, $startDate, $endDate)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('tanggal >=', $startDate)
                    ->where('tanggal <=', $endDate)
                    ->orderBy('tanggal', 'ASC')
                    ->orderBy('jam_mulai', 'ASC')
                    ->findAll();
    }

    public function getLogWithKomentar($logId)
    {
        $this->select('log_aktivitas.*, komentar_pembimbing.komentar, komentar_pembimbing.created_at as komentar_at, users.nama as pembimbing_nama')
              ->join('komentar_pembimbing', 'komentar_pembimbing.log_id = log_aktivitas.id', 'left')
              ->join('users', 'users.id = komentar_pembimbing.pembimbing_id', 'left')
              ->where('log_aktivitas.id', $logId);
        
        return $this->first();
    }

    public function getLogPendingByPembimbing($pembimbingId)
    {
        // Ambil log siswa yang dibimbing oleh pembimbing tertentu
        return $this->select('log_aktivitas.*, u.nama as siswa_nama, u.nis, u.tempat_magang')
                    ->join('pembimbing_siswa ps', 'ps.siswa_id = log_aktivitas.siswa_id')
                    ->join('users u', 'u.id = log_aktivitas.siswa_id')
                    ->where('ps.pembimbing_id', $pembimbingId)
                    ->where('log_aktivitas.status', 'menunggu')
                    ->orderBy('log_aktivitas.tanggal', 'DESC')
                    ->findAll();
    }

    public function countStatusByPembimbing(int $pembimbingId): array
    {
        $builder = $this->select('log_aktivitas.status, COUNT(*) as jumlah')
                        ->join('pembimbing_siswa ps', 'ps.siswa_id = log_aktivitas.siswa_id')
                        ->where('ps.pembimbing_id', $pembimbingId)
                        ->groupBy('log_aktivitas.status');
        $rows = $builder->findAll();
        $result = ['menunggu' => 0, 'disetujui' => 0, 'revisi' => 0, 'total' => 0];
        foreach ($rows as $row) {
            $status = $row['status'];
            $jumlah = (int) $row['jumlah'];
            if (isset($result[$status])) {
                $result[$status] = $jumlah;
            }
            $result['total'] += $jumlah;
        }
        return $result;
    }
}
