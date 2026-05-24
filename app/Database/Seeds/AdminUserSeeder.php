<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class AdminUserSeeder extends Seeder
{
  public function run(): void
  {
    $now = Time::now("Asia/Jakarta", "en_US")->format("Y-m-d H:i:s");
    $adminPassword = password_hash("Admin@123", PASSWORD_BCRYPT);

    // Admin User
    $users = [
      [
        "name" => "Admin MovPrima",
        "email" => "admin@movprima.com",
        "password" => $adminPassword,
        "role" => "admin",
        "avatar" => null,
        "bio" => "Platform administrator.",
        "created_at" => $now,
        "updated_at" => $now,
      ],
    ];

    $this->db->table("users")->insertBatch($users);
    echo "  Seeded: admin user — admin@movprima.com / Admin@123\n";
  }
}
