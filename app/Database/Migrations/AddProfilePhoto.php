<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilePhoto extends Migration
{
    public function up()
    {
        // Tambahkan field foto_profil ke tabel admin
        $this->forge->addColumn('admin', [
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'alamat'
            ]
        ]);

        // Tambahkan field foto_profil ke tabel pembimbing
        $this->forge->addColumn('pembimbing', [
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'alamat'
            ]
        ]);

        // Tambahkan field foto_profil ke tabel siswa
        $this->forge->addColumn('siswa', [
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'alamat'
            ]
        ]);
    }

    public function down()
    {
        // Hapus field foto_profil dari semua tabel
        $this->forge->dropColumn('admin', 'foto_profil');
        $this->forge->dropColumn('pembimbing', 'foto_profil');
        $this->forge->dropColumn('siswa', 'foto_profil');
    }
}
