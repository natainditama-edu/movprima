<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewLikesTable extends Migration
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
            'review_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        // A user can only like a review once
        $this->forge->addUniqueKey(['user_id', 'review_id'], 'uniq_review_likes_user_review');

        $this->forge->createTable('review_likes', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        $this->db->query('
            ALTER TABLE review_likes
                ADD CONSTRAINT fk_rl_user
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT fk_rl_review
                    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE review_likes DROP FOREIGN KEY fk_rl_user');
        $this->db->query('ALTER TABLE review_likes DROP FOREIGN KEY fk_rl_review');
        $this->forge->dropTable('review_likes', true);
    }
}
