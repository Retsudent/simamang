<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateSimamangTables extends Migration
{
    public function up()
    {
        // Tabel users
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'siswa',
            ],
            'nis' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tempat_magang' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // Tambahkan CHECK constraint untuk role (PostgreSQL)
        $this->db->query("ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('admin', 'siswa', 'pembimbing'))");

        // Tabel log_aktivitas
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam_mulai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'jam_selesai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'uraian' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'bukti' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'menunggu',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('log_aktivitas');

        // Tambahkan CHECK constraint untuk status
        $this->db->query("ALTER TABLE log_aktivitas ADD CONSTRAINT chk_status CHECK (status IN ('menunggu', 'disetujui', 'revisi'))");

        // Tabel komentar_pembimbing
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'log_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pembimbing_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'komentar' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('komentar_pembimbing');

        // Jika mau bisa tambahkan foreign key constraints di sini manual
        // Contoh:
        // $this->db->query("ALTER TABLE log_aktivitas ADD CONSTRAINT fk_siswa FOREIGN KEY (siswa_id) REFERENCES users(id) ON DELETE CASCADE");
        // $this->db->query("ALTER TABLE komentar_pembimbing ADD CONSTRAINT fk_log FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE");
        // $this->db->query("ALTER TABLE komentar_pembimbing ADD CONSTRAINT fk_pembimbing FOREIGN KEY (pembimbing_id) REFERENCES users(id) ON DELETE CASCADE");
    }

    public function down()
    {
        $this->forge->dropTable('komentar_pembimbing');
        $this->forge->dropTable('log_aktivitas');
        $this->forge->dropTable('users');
    }
}
