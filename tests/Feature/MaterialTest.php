<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_materials_list(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->get('/admin/materials');

        $response->assertStatus(200);
        $response->assertSee('Pengenalan Aljabar dan Variabel');
        $response->assertSee('Modul Belajar: Ekosistem dan Rantai Makanan');
    }

    public function test_guru_budi_sees_matematika_materials_and_not_ipa(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();

        $response = $this->actingAs($guruBudi)->get('/guru/materials');
        $response->assertStatus(200);
        $response->assertSee('Pengenalan Aljabar dan Variabel');
        $response->assertDontSee('Modul Belajar: Ekosistem dan Rantai Makanan');
    }

    public function test_guru_siti_sees_ipa_materials_and_not_matematika(): void
    {
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $response = $this->actingAs($guruSiti)->get('/guru/materials');
        $response->assertStatus(200);
        $response->assertSee('Modul Belajar: Ekosistem dan Rantai Makanan');
        $response->assertDontSee('Pengenalan Aljabar dan Variabel');
    }

    public function test_guru_cannot_edit_other_guru_material(): void
    {
        $guruBudi = User::where('email', 'budi.guru@lemas.sch.id')->first();
        $guruSiti = User::where('email', 'siti.guru@lemas.sch.id')->first();

        $materiBudi = Material::where('title', 'Pengenalan Aljabar dan Variabel')->first();

        $this->assertFalse($guruSiti->can('update', $materiBudi));
        $this->assertFalse($guruSiti->can('delete', $materiBudi));

        $this->assertTrue($guruBudi->can('update', $materiBudi));
        $this->assertTrue($guruBudi->can('delete', $materiBudi));
    }

    public function test_enrolled_murid_sees_published_materials(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        // Murid 1 ada di kelas Matematika 7A dan IPA 7A
        $response = $this->actingAs($murid1)->get('/murid/materials');
        $response->assertStatus(200);
        $response->assertSee('Pengenalan Aljabar dan Variabel');
        $response->assertSee('Modul Belajar: Ekosistem dan Rantai Makanan');
    }

    public function test_unenrolled_murid_does_not_see_materials(): void
    {
        // Murid 6 hanya di kelas Matematika 7B (belum ada materi)
        $murid6 = User::where('email', 'murid06@lemas.sch.id')->first();

        $response = $this->actingAs($murid6)->get('/murid/materials');
        $response->assertStatus(200);
        $response->assertDontSee('Pengenalan Aljabar dan Variabel');
        $response->assertDontSee('Modul Belajar: Ekosistem dan Rantai Makanan');
    }

    public function test_murid_cannot_see_unpublished_draft_material(): void
    {
        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        // Jadikan materi aljabar sebagai draf (is_published = false)
        $materiBudi = Material::where('title', 'Pengenalan Aljabar dan Variabel')->first();
        $materiBudi->update(['is_published' => false]);

        $response = $this->actingAs($murid1)->get('/murid/materials');
        $response->assertStatus(200);
        $response->assertDontSee('Pengenalan Aljabar dan Variabel');
    }

    public function test_enrolled_murid_can_download_material_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('materials/sample.pdf', 'dummy content');

        $materi = Material::where('type', Material::TYPE_FILE)->first();
        $materi->update(['file_path' => 'materials/sample.pdf']);

        $murid1 = User::where('email', 'murid01@lemas.sch.id')->first();

        $response = $this->actingAs($murid1)->get(route('materials.download', $materi));
        $response->assertStatus(200);
    }

    public function test_unenrolled_murid_is_forbidden_from_downloading_material_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('materials/sample.pdf', 'dummy content');

        $materi = Material::where('type', Material::TYPE_FILE)->first();
        $materi->update(['file_path' => 'materials/sample.pdf']);

        // Murid 6 tidak terdaftar di kelas IPA 7A
        $murid6 = User::where('email', 'murid06@lemas.sch.id')->first();

        $response = $this->actingAs($murid6)->get(route('materials.download', $materi));
        $response->assertStatus(403);
    }
}
