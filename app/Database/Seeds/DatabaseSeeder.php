<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder — master seeder.
 * Run: php spark db:seed DatabaseSeeder
 *
 * Order matters:
 *   1. GenreSeeder      (no deps)
 *   2. AdminUserSeeder  (no deps)
 *   3. MovieSeeder      (depends on genres)
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('GenreSeeder');
        $this->call('AdminUserSeeder');
        $this->call('MovieSeeder');

        echo "\n[DONE] Database seeded successfully.\n";
    }
}
