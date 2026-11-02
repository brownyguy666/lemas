<?php

namespace Tests\Feature;

use App\Filament\Guru\Widgets\GuruStatsOverview;
use App\Filament\Murid\Widgets\MuridStatsOverview;
use App\Filament\Widgets\AdminStatsOverview;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_stats_overview_renders(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $this->actingAs($admin);

        Livewire::test(AdminStatsOverview::class)
            ->assertSuccessful()
            ->assertSee('Ringkasan Sistem LEMAS')
            ->assertSee('Total Pengguna')
            ->assertSee('Ruang Kelas')
            ->assertSee('Materi Pelajaran')
            ->assertSee('Tugas Sekolah');
    }

    public function test_guru_stats_overview_renders(): void
    {
        $guru = User::where('email', 'budi.guru@lemas.sch.id')->first();

        $this->actingAs($guru);

        Livewire::test(GuruStatsOverview::class)
            ->assertSuccessful()
            ->assertSee('Aktivitas Kelas & Pengajaran Anda')
            ->assertSee('Kelas Saya')
            ->assertSee('Materi Pembelajaran')
            ->assertSee('Perlu Dinilai');
    }

    public function test_murid_stats_overview_renders(): void
    {
        $murid = User::where('email', 'murid01@lemas.sch.id')->first();

        $this->actingAs($murid);

        Livewire::test(MuridStatsOverview::class)
            ->assertSuccessful()
            ->assertSee('Ruang Belajar & Progres Tugas Anda')
            ->assertSee('Kelas Diikuti')
            ->assertSee('Materi Tersedia')
            ->assertSee('Rata-rata Nilai Saya');
    }
}
