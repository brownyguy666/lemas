<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_assignments_list(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->get('/admin/assignments');

        $response->assertStatus(200);
        $response->assertSee('Tugas 1: Menyelesaikan Persamaan Aljabar');
        $response->assertSee('Laporan Pengamatan Rantai Makanan');
    }

    public function test_guru_budi_only_sees_their_own_assignments(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();

        $response = $this->actingAs($guruBudi)->get('/guru/assignments');

        $response->assertStatus(200);
        $response->assertSee('Tugas 1: Menyelesaikan Persamaan Aljabar');
        $response->assertDontSee('Laporan Pengamatan Rantai Makanan');
    }

    public function test_guru_siti_only_sees_their_own_assignments(): void
    {
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $response = $this->actingAs($guruSiti)->get('/guru/assignments');

        $response->assertStatus(200);
        $response->assertSee('Laporan Pengamatan Rantai Makanan');
        $response->assertDontSee('Tugas 1: Menyelesaikan Persamaan Aljabar');
    }

    public function test_guru_cannot_edit_other_guru_assignment(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $tugasBudi = Assignment::where('title', 'Tugas 1: Menyelesaikan Persamaan Aljabar')->first();

        $this->assertFalse($guruSiti->can('update', $tugasBudi));
        $this->assertFalse($guruSiti->can('delete', $tugasBudi));

        $this->assertTrue($guruBudi->can('update', $tugasBudi));
        $this->assertTrue($guruBudi->can('delete', $tugasBudi));
    }

    public function test_enrolled_murid_sees_assignments(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        // Murid 1 terdaftar di Matematika 7A dan IPA 7A
        $response = $this->actingAs($murid1)->get('/murid/assignments');
        $response->assertStatus(200);
        $response->assertSee('Tugas 1: Menyelesaikan Persamaan Aljabar');
        $response->assertSee('Laporan Pengamatan Rantai Makanan');
    }

    public function test_unenrolled_murid_does_not_see_assignments(): void
    {
        // Murid 6 di Matematika 7B (belum ada tugas)
        $murid6 = User::where('email', 'murid06@lemas.sch.id')->first();

        $response = $this->actingAs($murid6)->get('/murid/assignments');
        $response->assertStatus(200);
        $response->assertDontSee('Tugas 1: Menyelesaikan Persamaan Aljabar');
        $response->assertDontSee('Laporan Pengamatan Rantai Makanan');
    }

    public function test_submission_is_late_when_submitted_past_due_date(): void
    {
        $tugas = Assignment::where('title', 'Laporan Pengamatan Rantai Makanan di Sekitar Rumah')->first();
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        $submission = Submission::where('assignment_id', $tugas->id)
            ->where('student_id', $murid1->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertTrue($submission->is_late);
    }

    public function test_guru_can_grade_student_submission(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();
        $murid2 = User::where('email', 'murid02@lemas.sch.id')->first();
        $tugas = Assignment::where('title', 'Tugas 1: Menyelesaikan Persamaan Aljabar')->first();

        $submission = Submission::where('assignment_id', $tugas->id)
            ->where('student_id', $murid2->id)
            ->first();

        $this->assertTrue($guruBudi->can('update', $submission));

        $submission->update([
            'points' => 90,
            'teacher_feedback' => 'Bagus sekali pekerjaannya!',
            'graded_at' => now(),
        ]);

        $this->assertEquals(90, $submission->fresh()->points);
        $this->assertTrue($submission->fresh()->isGraded());
    }

    public function test_murid_cannot_see_other_students_submission_privately(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();
        $murid2 = User::where('email', 'murid02@lemas.sch.id')->first();

        $tugas = Assignment::where('title', 'Tugas 1: Menyelesaikan Persamaan Aljabar')->first();

        $subMurid1 = Submission::where('assignment_id', $tugas->id)
            ->where('student_id', $murid1->id)
            ->first();

        // Murid 1 boleh melihat pengumpulannya sendiri
        $this->assertTrue($murid1->can('view', $subMurid1));

        // Murid 2 TIDAK boleh melihat pengumpulan milik Murid 1 (privasi terjaga)
        $this->assertFalse($murid2->can('view', $subMurid1));
    }

    public function test_student_can_download_their_own_submission_file_but_not_others(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('submissions/jawaban_murid1.pdf', 'konten tugas');

        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();
        $murid2 = User::where('email', 'murid02@lemas.sch.id')->first();

        $sub = Submission::where('student_id', $murid1->id)->first();
        $sub->update(['file_path' => 'submissions/jawaban_murid1.pdf']);

        // Murid 1 bisa mengunduh berkas miliknya
        $response1 = $this->actingAs($murid1)->get(route('submissions.download', $sub));
        $response1->assertStatus(200);

        // Murid 2 dilarang mengunduh berkas Murid 1
        $response2 = $this->actingAs($murid2)->get(route('submissions.download', $sub));
        $response2->assertStatus(403);
    }
}
