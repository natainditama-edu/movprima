<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWatchlistTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'movie_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['want_to_watch', 'watching', 'watched'],
                'null'       => false,
                'default'    => 'want_to_watch',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        // A user can only add a movie to watchlist once
        $this->forge->addUniqueKey(['user_id', 'movie_id'], 'uniq_watchlist_user_movie');

        $this->forge->createTable('watchlist', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        $this->db->query('
            ALTER TABLE watchlist
                ADD CONSTRAINT fk_wl_user
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT fk_wl_movie
                    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE watchlist DROP FOREIGN KEY fk_wl_user');
        $this->db->query('ALTER TABLE watchlist DROP FOREIGN KEY fk_wl_movie');
        $this->forge->dropTable('watchlist', true);
    }
}
