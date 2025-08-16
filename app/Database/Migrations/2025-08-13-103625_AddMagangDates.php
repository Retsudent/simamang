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
                'default' => null
            ],
            'tanggal_selesai_magang' => [
                'type' => 'DATE',
                'null' => true,
                'default' => null
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
