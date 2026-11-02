<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with initial accounts:
     * - 1 Administrator
     * - 2 Guru
     * - 10 Murid
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@lemas.sch.id'],
            [
                'name' => 'Administrator LEMAS',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Guru
        $gurus = [
            ['name' => 'Budi Santoso, S.Pd.', 'email' => 'budi.guru@lemas.sch.id'],
            ['name' => 'Siti Rahmawati, M.Pd.', 'email' => 'siti.guru@lemas.sch.id'],
        ];

        foreach ($gurus as $guru) {
            User::updateOrCreate(
                ['email' => $guru['email']],
                [
                    'name' => $guru['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_GURU,
                    'email_verified_at' => now(),
                ]
            );
        }

        // 3. Akun Murid (10 murid contoh)
        $murids = [
            ['name' => 'Ahmad Dahlan', 'email' => 'murid01@lemas.sch.id'],
            ['name' => 'Annisa Tri Utami', 'email' => 'murid02@lemas.sch.id'],
            ['name' => 'Bagas Pratama', 'email' => 'murid03@lemas.sch.id'],
            ['name' => 'Citra Lestari', 'email' => 'murid04@lemas.sch.id'],
            ['name' => 'Daffa Ramadhan', 'email' => 'murid05@lemas.sch.id'],
            ['name' => 'Eka Nurhaliza', 'email' => 'murid06@lemas.sch.id'],
            ['name' => 'Fajar Nugraha', 'email' => 'murid07@lemas.sch.id'],
            ['name' => 'Gita Safitri', 'email' => 'murid08@lemas.sch.id'],
            ['name' => 'Hendra Wijaya', 'email' => 'murid09@lemas.sch.id'],
            ['name' => 'Indah Permata', 'email' => 'murid10@lemas.sch.id'],
        ];

        foreach ($murids as $murid) {
            User::updateOrCreate(
                ['email' => $murid['email']],
                [
                    'name' => $murid['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_MURID,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
