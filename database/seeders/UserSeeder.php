<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed / update user passwords menjadi bcrypt hash.
     * Data user sesuai dengan yang sudah ada di siappro_db.sql.
     */
    public function run(): void
    {
        $users = [
            [
                'id_user' => 1,
                'username' => 'superadmin',
                'password' => Hash::make('Spradmin123#'),
                'nama_lengkap' => 'Super Administrator',
                'role' => 'super_admin',
                'is_active' => 1,
            ],
            [
                'id_user' => 2,
                'username' => 'admin',
                'password' => Hash::make('Admin123#'),
                'nama_lengkap' => 'Administrator',
                'role' => 'admin',
                'is_active' => 1,
            ],
            [
                'id_user' => 3,
                'username' => 'eksternal',
                'password' => Hash::make('Eksternal123#'),
                'nama_lengkap' => 'User Eksternal',
                'role' => 'eksternal',
                'is_active' => 1,
            ],
        ];

        foreach ($users as $user) {
            DB::table('tb_user')->updateOrInsert(
                ['id_user' => $user['id_user']],
                $user
            );
        }

        // Pastikan user_details juga ada untuk user eksternal
        DB::table('user_details')->updateOrInsert(
            ['user_id' => 3],
            [
                'jabatan' => 'User Eksternal',
                'unit_kerja' => 'Eksternal',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
