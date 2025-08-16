<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMagangDates extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'tanggal_mulai_magang' => [
                'type' => 'DATE',
                'null' => true,
                'default' => null,
                'after' => 'alamat_magang'
            ],
            'tanggal_selesai_magang' => [
                'type' => 'DATE',
                'null' => true,
                'default' => null,
                'after' => 'tanggal_mulai_magang'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', ['tanggal_mulai_magang', 'tanggal_selesai_magang']);
    }
}
