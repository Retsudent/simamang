<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateSiswaDates extends Seeder
{
    public function run()
    {
        // Update siswa yang belum punya tanggal magang
        $this->db->query("
            UPDATE siswa 
            SET tanggal_mulai_magang = CURRENT_DATE - INTERVAL '1 month',
                tanggal_selesai_magang = CURRENT_DATE + INTERVAL '2 months'
            WHERE tanggal_mulai_magang IS NULL 
            OR tanggal_selesai_magang IS NULL
        ");
        
        echo "Updated siswa dates successfully!\n";
    }
}
