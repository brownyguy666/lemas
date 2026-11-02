<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Material;
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
     * - 3 Materi Pelajaran Contoh (Teks, Berkas, Video)
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

        // 5. Data Materi Pembelajaran Contoh
        // Materi 1 (Teks): Pengenalan Aljabar di Matematika 7A
        Material::updateOrCreate(
            ['classroom_id' => $kelas1->id, 'title' => 'Pengenalan Aljabar dan Variabel'],
            [
                'type' => Material::TYPE_TEXT,
                'content' => '<h3>Pengertian Aljabar</h3><p>Aljabar adalah cabang matematika yang mempelajari simbol-simbol matematika dan aturan untuk memanipulasi simbol-simbol tersebut. Variabel biasanya dilambangkan dengan huruf kecil seperti <em>x</em> atau <em>y</em> untuk menyatakan suatu nilai yang belum diketahui.</p><p><strong>Contoh:</strong> 2x + 5 = 15, maka x = 5.</p>',
                'order' => 1,
                'is_published' => true,
            ]
        );

        // Materi 2 (Video): Video Edukasi Operasi Bilangan di Matematika 7A
        Material::updateOrCreate(
            ['classroom_id' => $kelas1->id, 'title' => 'Video Pembelajaran: Trik Cepat Bilangan Bulat'],
            [
                'type' => Material::TYPE_VIDEO,
                'link_url' => 'https://www.youtube.com/watch?v=kYJ_K0-zN9c',
                'order' => 2,
                'is_published' => true,
            ]
        );

        // Materi 3 (Berkas): Modul PDF Ekosistem di IPA Terpadu 7A
        Material::updateOrCreate(
            ['classroom_id' => $kelas2->id, 'title' => 'Modul Belajar: Ekosistem dan Rantai Makanan'],
            [
                'type' => Material::TYPE_FILE,
                'file_path' => 'materials/modul_ekosistem.pdf',
                'file_name' => 'Modul_Ekosistem_Kelas_7.pdf',
                'order' => 1,
                'is_published' => true,
            ]
        );
    }
}
