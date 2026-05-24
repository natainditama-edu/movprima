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
 *   3. UserSeeder       (no deps)
 *   4. MovieSeeder      (depends on genres)
 *   5. ReviewSeeder     (depends on users and movies)
 */
class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call("GenreSeeder");
    $this->call("AdminUserSeeder");
    $this->call("UserSeeder");
    $this->call("MovieSeeder");
    $this->call("ReviewSeeder");

    echo "\n[DONE] Database seeded successfully.\n";
  }
}
