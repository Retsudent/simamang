<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentarModel extends Model
{
    protected $table      = 'komentar_pembimbing';
    protected $primaryKey = 'id';
    protected $useTimestamps = false; // created_at handled by DB default
    protected $allowedFields = [
        'log_id', 'pembimbing_id', 'komentar'
    ];

    protected $returnType = 'array';

    public function getKomentarByLog($logId)
    {
        return $this->select('komentar_pembimbing.*, users.nama as pembimbing_nama')
                    ->join('users', 'users.id = komentar_pembimbing.pembimbing_id')
                    ->where('log_id', $logId)
                    ->first();
    }

    public function saveKomentar($logId, $pembimbingId, $komentar)
    {
        // Cek apakah sudah ada komentar untuk log ini
        $existing = $this->where('log_id', $logId)->first();
        
        if ($existing) {
            // Update komentar yang ada
            return $this->update($existing['id'], [
                'komentar' => $komentar
            ]);
        } else {
            // Buat komentar baru
            return $this->insert([
                'log_id' => $logId,
                'pembimbing_id' => $pembimbingId,
                'komentar' => $komentar
            ]);
        }
    }
}
