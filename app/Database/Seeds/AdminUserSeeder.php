<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Default password: Admin@123
        $data = [
            'name'       => 'Admin MovPrima',
            'email'      => 'admin@movprima.com',
            'password'   => password_hash('Admin@123', PASSWORD_BCRYPT),
            'role'       => 'admin',
            'avatar'     => null,
            'bio'        => 'Platform administrator.',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->db->table('users')->insert($data);
        echo "  Seeded: admin user — admin@movprima.com / Admin@123\n";
    }
}
