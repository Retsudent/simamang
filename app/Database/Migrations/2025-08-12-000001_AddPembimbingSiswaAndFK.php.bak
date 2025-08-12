<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddPembimbingSiswaAndFK extends Migration
{
    public function up()
    {
        // Create pembimbing_siswa mapping table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pembimbing_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['pembimbing_id', 'siswa_id']);
        $this->forge->createTable('pembimbing_siswa');

        // Add foreign keys (PostgreSQL safe raw queries)
        $this->db->query("ALTER TABLE pembimbing_siswa ADD CONSTRAINT fk_pembimbing_siswa_pembimbing FOREIGN KEY (pembimbing_id) REFERENCES users(id) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE pembimbing_siswa ADD CONSTRAINT fk_pembimbing_siswa_siswa FOREIGN KEY (siswa_id) REFERENCES users(id) ON DELETE CASCADE");

        // Add foreign keys for existing tables
        $this->db->query("ALTER TABLE log_aktivitas ADD CONSTRAINT fk_log_siswa FOREIGN KEY (siswa_id) REFERENCES users(id) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE komentar_pembimbing ADD CONSTRAINT fk_komen_log FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE komentar_pembimbing ADD CONSTRAINT fk_komen_pembimbing FOREIGN KEY (pembimbing_id) REFERENCES users(id) ON DELETE CASCADE");
    }

    public function down()
    {
        // Drop FKs from existing tables
        $this->db->query("ALTER TABLE komentar_pembimbing DROP CONSTRAINT IF EXISTS fk_komen_pembimbing");
        $this->db->query("ALTER TABLE komentar_pembimbing DROP CONSTRAINT IF EXISTS fk_komen_log");
        $this->db->query("ALTER TABLE log_aktivitas DROP CONSTRAINT IF EXISTS fk_log_siswa");

        // Drop mapping table and its FKs
        $this->db->query("ALTER TABLE pembimbing_siswa DROP CONSTRAINT IF EXISTS fk_pembimbing_siswa_pembimbing");
        $this->db->query("ALTER TABLE pembimbing_siswa DROP CONSTRAINT IF EXISTS fk_pembimbing_siswa_siswa");
        $this->forge->dropTable('pembimbing_siswa');
    }
}


