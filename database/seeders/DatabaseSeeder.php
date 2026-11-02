<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database:
     * - 1 Administrator
     * - 2 Guru
     * - 10 Murid
     * - 3 Ruang Kelas Contoh
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
        $guruBudi = User::updateOrCreate(
            ['email' => 'budi.guru@lemas.sch.id'],
            [
                'name' => 'Budi Santoso, S.Pd.',
                'password' => Hash::make('password'),
                'role' => User::ROLE_GURU,
                'email_verified_at' => now(),
            ]
        );

        $guruSiti = User::updateOrCreate(
            ['email' => 'siti.guru@lemas.sch.id'],
            [
                'name' => 'Siti Rahmawati, M.Pd.',
                'password' => Hash::make('password'),
                'role' => User::ROLE_GURU,
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Murid (10 murid contoh)
        $muridUsers = [];
        $muridsData = [
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

        foreach ($muridsData as $data) {
            $muridUsers[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_MURID,
                    'email_verified_at' => now(),
                ]
            );
        }

        // 4. Data Kelas Contoh
        // Kelas 1: Matematika 7A (Pak Budi, Murid 1-5)
        $kelas1 = Classroom::updateOrCreate(
            ['join_code' => 'MTK7A1'],
            [
                'name' => 'Matematika Kelas 7A',
                'description' => 'Kelas pembelajaran Matematika semester ganjil tahun ajaran 2026/2027.',
                'teacher_id' => $guruBudi->id,
                'academic_year' => '2026/2027',
                'is_active' => true,
            ]
        );
        $muridGroup1 = array_slice($muridUsers, 0, 5);
        $kelas1->students()->sync(array_column($muridGroup1, 'id'));

        // Kelas 2: IPA Terpadu 7A (Bu Siti, Murid 1-5)
        $kelas2 = Classroom::updateOrCreate(
            ['join_code' => 'IPA7A1'],
            [
                'name' => 'IPA Terpadu Kelas 7A',
                'description' => 'Mempelajari fisika dan biologi dasar, eksperimen ilmiah sederhana.',
                'teacher_id' => $guruSiti->id,
                'academic_year' => '2026/2027',
                'is_active' => true,
            ]
        );
        $kelas2->students()->sync(array_column($muridGroup1, 'id'));

        // Kelas 3: Matematika 7B (Pak Budi, Murid 6-10)
        $kelas3 = Classroom::updateOrCreate(
            ['join_code' => 'MTK7B1'],
            [
                'name' => 'Matematika Kelas 7B',
                'description' => 'Kelas pembelajaran Matematika Kelas 7B semester ganjil.',
                'teacher_id' => $guruBudi->id,
                'academic_year' => '2026/2027',
                'is_active' => true,
            ]
        );
        $muridGroup2 = array_slice($muridUsers, 5, 5);
        $kelas3->students()->sync(array_column($muridGroup2, 'id'));
    }
}
