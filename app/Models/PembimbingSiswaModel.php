<?php

namespace App\Models;

use CodeIgniter\Model;

class PembimbingSiswaModel extends Model
{
    protected $table = 'pembimbing_siswa';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = ['pembimbing_id', 'siswa_id'];
    protected $returnType = 'array';

    public function getSiswaIdsForPembimbing(int $pembimbingId): array
    {
        $rows = $this->select('siswa_id')->where('pembimbing_id', $pembimbingId)->findAll();
        return array_map(static fn($r) => (int)$r['siswa_id'], $rows);
    }

    public function assignSiswaToPembimbing(int $pembimbingId, int $siswaId): bool
    {
        // Avoid duplicate by unique key
        $exists = $this->where(['pembimbing_id' => $pembimbingId, 'siswa_id' => $siswaId])->first();
        if ($exists) {
            return true;
        }
        return (bool)$this->insert(['pembimbing_id' => $pembimbingId, 'siswa_id' => $siswaId]);
    }

    public function removeSiswaFromPembimbing(int $pembimbingId, int $siswaId): bool
    {
        return (bool)$this->where(['pembimbing_id' => $pembimbingId, 'siswa_id' => $siswaId])->delete();
    }
}


