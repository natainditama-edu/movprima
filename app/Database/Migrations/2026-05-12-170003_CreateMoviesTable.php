<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMoviesTable extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 220,
                'null'       => false,
                'comment'    => 'URL-friendly title',
            ],
            'synopsis' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'director' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'default'    => null,
            ],
            'release_year' => [
                'type' => 'YEAR',
                'null' => false,
            ],
            'duration' => [
                'type'       => 'SMALLINT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'in minutes',
            ],
            'poster' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'comment'    => 'path or URL',
            ],
            'backdrop' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'trailer_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'comment'    => 'YouTube embed URL',
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'English',
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
                'default'    => null,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['published', 'draft'],
                'null'       => false,
                'default'    => 'published',
            ],
            'avg_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'null'       => false,
                'default'    => 0.00,
                'comment'    => 'denormalized, updated on review CUD',
            ],
            'review_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
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
        $this->forge->addUniqueKey('slug', 'uniq_movies_slug');
        $this->forge->addKey('status', false, false, 'idx_movies_status');
        $this->forge->addKey('release_year', false, false, 'idx_movies_release_year');
        $this->forge->addKey('avg_rating', false, false, 'idx_movies_avg_rating');

        $this->forge->createTable('movies', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('movies', true);
    }
}
