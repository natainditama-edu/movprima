<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMovieGenresTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'movie_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'genre_id' => [
                'type'     => 'SMALLINT',
                'constraint' => 5,
                'unsigned' => true,
                'null'     => false,
            ],
        ]);

        // Composite primary key
        $this->forge->addPrimaryKey(['movie_id', 'genre_id']);

        $this->forge->createTable('movie_genres', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Add foreign keys after table creation
        $this->db->query('
            ALTER TABLE movie_genres
                ADD CONSTRAINT fk_mg_movie
                    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT fk_mg_genre
                    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE movie_genres DROP FOREIGN KEY fk_mg_movie');
        $this->db->query('ALTER TABLE movie_genres DROP FOREIGN KEY fk_mg_genre');
        $this->forge->dropTable('movie_genres', true);
    }
}
