<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
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
            'rating' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => '1-10 scale',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_spoiler' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            'likes_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
                'comment'    => 'denormalized counter',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'flagged', 'removed'],
                'null'       => false,
                'default'    => 'published',
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
        // One review per user per movie
        $this->forge->addUniqueKey(['user_id', 'movie_id'], 'uniq_reviews_user_movie');
        $this->forge->addKey('movie_id', false, false, 'idx_reviews_movie_id');
        $this->forge->addKey('rating', false, false, 'idx_reviews_rating');
        $this->forge->addKey('created_at', false, false, 'idx_reviews_created_at');

        $this->forge->createTable('reviews', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Rating check constraint + foreign keys
        $this->db->query('
            ALTER TABLE reviews
                ADD CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 10),
                ADD CONSTRAINT fk_reviews_user
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT fk_reviews_movie
                    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE reviews DROP FOREIGN KEY fk_reviews_user');
        $this->db->query('ALTER TABLE reviews DROP FOREIGN KEY fk_reviews_movie');
        $this->forge->dropTable('reviews', true);
    }
}
