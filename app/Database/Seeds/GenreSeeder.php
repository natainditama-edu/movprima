<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['name' => 'Action',      'slug' => 'action'],
            ['name' => 'Comedy',      'slug' => 'comedy'],
            ['name' => 'Drama',       'slug' => 'drama'],
            ['name' => 'Horror',      'slug' => 'horror'],
            ['name' => 'Sci-Fi',      'slug' => 'sci-fi'],
            ['name' => 'Romance',     'slug' => 'romance'],
            ['name' => 'Animation',   'slug' => 'animation'],
            ['name' => 'Thriller',    'slug' => 'thriller'],
            ['name' => 'Documentary', 'slug' => 'documentary'],
            ['name' => 'Fantasy',     'slug' => 'fantasy'],
        ];

        $now = date('Y-m-d H:i:s');

        foreach ($genres as &$genre) {
            $genre['created_at'] = $now;
            $genre['updated_at'] = $now;
        }

        $this->db->table('genres')->insertBatch($genres);
        echo "  Seeded: " . count($genres) . " genres\n";
    }
}
