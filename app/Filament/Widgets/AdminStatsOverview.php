<?php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Sistem LEMAS';

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalGuru = User::where('role', User::ROLE_GURU)->count();
        $totalMurid = User::where('role', User::ROLE_MURID)->count();

        $totalKelas = Classroom::count();
        $totalMateri = Material::count();
        $totalTugas = Assignment::count();

        return [
            Stat::make('Total Pengguna', (string) $totalUsers)
                ->description("{$totalGuru} Guru • {$totalMurid} Murid")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([3, 5, 8, 10, 13]),

            Stat::make('Ruang Kelas', (string) $totalKelas)
                ->description('Kelas belajar aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->chart([1, 2, 2, 3, 3]),

            Stat::make('Materi Pelajaran', (string) $totalMateri)
                ->description('Teks, dokumen, & video')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info')
                ->chart([1, 1, 2, 2, 3]),

            Stat::make('Tugas Sekolah', (string) $totalTugas)
                ->description('Total penugasan aktif')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning')
                ->chart([1, 1, 1, 2, 2]),
        ];
    }
}
