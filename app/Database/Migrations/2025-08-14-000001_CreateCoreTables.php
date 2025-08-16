<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up(): void
    {
        // users (unified login, optional but used by Auth first)
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'nama' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
                'username' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'unique' => true ],
                'password' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
                'role' => [ 'type' => 'ENUM("admin","pembimbing","siswa")' ],
                'status' => [ 'type' => 'ENUM("aktif","nonaktif")', 'default' => 'aktif' ],
                'foto_profil' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('users', true);

        // admin
        if (true) {
            $this->forge->addField([
                'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
                'nama' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
                'username' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'unique' => true ],
                'password' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
                'status' => [ 'type' => 'ENUM("aktif","nonaktif")', 'default' => 'aktif' ],
                'foto_profil' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('admin', true);
        }

        // pembimbing
        if (true) {
            $this->forge->addField([
                'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
                'nama' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
                'username' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'unique' => true ],
                'password' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
                'status' => [ 'type' => 'ENUM("aktif","nonaktif")', 'default' => 'aktif' ],
                'foto_profil' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('pembimbing', true);
        }

        // siswa
        if (true) {
            $this->forge->addField([
                'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
                'nama' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
                'username' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'unique' => true ],
                'password' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
                'nis' => [ 'type' => 'VARCHAR', 'constraint' => 50 ],
                'tempat_magang' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
                'alamat_magang' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
                'pembimbing_id' => [ 'type' => 'INT', 'constraint' => 11, 'null' => true, 'unsigned' => true ],
                'tanggal_mulai_magang' => [ 'type' => 'DATE', 'null' => true ],
                'tanggal_selesai_magang' => [ 'type' => 'DATE', 'null' => true ],
                'status' => [ 'type' => 'ENUM("aktif","nonaktif")', 'default' => 'aktif' ],
                'foto_profil' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('pembimbing_id', 'pembimbing', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('siswa', true);
        }

        // log_aktivitas
        if (true) {
            $this->forge->addField([
                'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
                'siswa_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
                'tanggal' => [ 'type' => 'DATE' ],
                'jam_mulai' => [ 'type' => 'TIME', 'null' => true ],
                'jam_selesai' => [ 'type' => 'TIME', 'null' => true ],
                'uraian' => [ 'type' => 'TEXT' ],
                'status' => [ 'type' => 'ENUM("menunggu","disetujui","revisi","ditolak")', 'default' => 'menunggu' ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('siswa_id');
            $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('log_aktivitas', true);
        }

        // komentar_pembimbing
        if (true) {
            $this->forge->addField([
                'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
                'log_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
                'pembimbing_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
                'komentar' => [ 'type' => 'TEXT', 'null' => true ],
                'status_validasi' => [ 'type' => 'ENUM("menunggu","disetujui","revisi")', 'default' => 'menunggu' ],
                'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
                'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('log_id');
            $this->forge->addKey('pembimbing_id');
            $this->forge->addForeignKey('log_id', 'log_aktivitas', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('pembimbing_id', 'pembimbing', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('komentar_pembimbing', true);
        }
    }

    public function down(): void
    {
        $tables = [
            'komentar_pembimbing',
            'log_aktivitas',
            'siswa',
            'pembimbing',
            'admin',
            'users',
        ];
        foreach ($tables as $t) {
            if ($this->db->tableExists($t)) {
                $this->forge->dropTable($t, true);
            }
        }
    }
}


