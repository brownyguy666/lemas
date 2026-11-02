<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_classrooms_list(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->get('/admin/classrooms');

        $response->assertStatus(200);
        $response->assertSee('Matematika Kelas 7A');
        $response->assertSee('IPA Terpadu Kelas 7A');
        $response->assertSee('Matematika Kelas 7B');
    }

    public function test_guru_can_only_see_their_own_classrooms_in_guru_panel(): void
    {
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $response = $this->actingAs($guruSiti)->get('/guru/classrooms');

        $response->assertStatus(200);
        $response->assertSee('IPA Terpadu Kelas 7A');
        // Harusnya tidak melihat kelas milik Pak Budi
        $response->assertDontSee('Matematika Kelas 7A');
    }

    public function test_guru_cannot_edit_other_guru_classroom(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $kelasBudi = Classroom::where('teacher_id', $guruBudi->id)->first();

        // Guru Siti tidak boleh mengedit kelas Pak Budi
        $this->assertFalse($guruSiti->can('update', $kelasBudi));
        $this->assertFalse($guruSiti->can('delete', $kelasBudi));

        // Pak Budi boleh mengedit kelasnya sendiri
        $this->assertTrue($guruBudi->can('update', $kelasBudi));
        $this->assertTrue($guruBudi->can('delete', $kelasBudi));
    }

    public function test_murid_only_sees_enrolled_classrooms(): void
    {
        // Murid01 ada di Matematika 7A dan IPA Terpadu 7A, tapi TIDAK ada di Matematika 7B
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        $response = $this->actingAs($murid1)->get('/murid/classrooms');

        $response->assertStatus(200);
        $response->assertSee('Matematika Kelas 7A');
        $response->assertSee('IPA Terpadu Kelas 7A');
        $response->assertDontSee('Matematika Kelas 7B');
    }

    public function test_murid_policy_forbids_classroom_creation_and_editing(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();
        $kelas = Classroom::first();

        $this->assertFalse($murid1->can('create', Classroom::class));
        $this->assertFalse($murid1->can('update', $kelas));
        $this->assertFalse($murid1->can('delete', $kelas));
    }

    public function test_murid_can_join_classroom_using_code(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();
        $kelas7B = Classroom::where('join_code', 'MTK7B1')->first();

        // Awalnya murid1 belum terdaftar di 7B
        $this->assertFalse($kelas7B->students()->where('student_id', $murid1->id)->exists());

        // Murid bergabung dengan kode MTK7B1
        $kelas7B->students()->attach($murid1->id);

        // Sekarang murid1 sudah terdaftar
        $this->assertTrue($kelas7B->students()->where('student_id', $murid1->id)->exists());
    }
}
