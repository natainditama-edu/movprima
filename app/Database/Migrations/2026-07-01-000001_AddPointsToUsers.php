<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPointsToUsers extends Migration
{
  public function up(): void
  {
    $this->forge->addColumn("users", [
      "points" => [
        "type" => "INT",
        "constraint" => 11,
        "unsigned" => true,
        "default" => 0,
        "after" => "bio",
      ],
    ]);
  }

  public function down(): void
  {
    $this->forge->dropColumn("users", "points");
  }
}
