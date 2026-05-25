<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $now = Time::now("Asia/Jakarta", "en_US")->format("Y-m-d H:i:s");
    $password = password_hash("User@123", PASSWORD_BCRYPT);
    $users = [];

    // Normal Users
    $names = [
      "Budi Santoso",
      "Siti Aminah",
      "Joko Widodo",
      "Ayu Lestari",
      "Rizky Pratama",
      "Dewi Sartika",
      "Andi Wijaya",
      "Rina Nose",
      "Fajar Hidayat",
      "Maya Septiani",
      "Dedi Corbuzier",
      "Raffi Ahmad",
      "Agnes Monica",
      "Iwan Fals",
      "Ariel Noah",
    ];

    foreach ($names as $i => $name) {
      $email = strtolower(str_replace(" ", ".", $name)) . "@movprima.com";

      $users[] = [
        "name" => $name,
        "email" => $email,
        "password" => $password,
        "role" => "user",
        "bio" => "Halo, saya " . $name . " seorang pecinta film.",
        "created_at" => $now,
        "updated_at" => $now,
      ];
    }

    $this->db->table("users")->insertBatch($users);
    echo "  Seeded: " . count($users) . " normal users\n";
  }
}
