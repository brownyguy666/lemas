<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Classroom;
use App\Models\Material;
use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuruStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Aktivitas Kelas & Pengajaran Anda';

    protected function getStats(): array
    {
        $teacherId = auth()->id();

        $totalKelas = Classroom::where('teacher_id', $teacherId)->count();

        $totalMateri = Material::whereHas('classroom', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->count();

        $perluDinilai = Submission::whereHas('assignment.classroom', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->whereNull('grade')->count();

        $sudahDinilai = Submission::whereHas('assignment.classroom', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->whereNotNull('grade')->count();

        return [
            Stat::make('Kelas Saya', (string) $totalKelas)
                ->description('Kelas aktif yang Anda ampu')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Materi Pembelajaran', (string) $totalMateri)
                ->description('Total materi yang dibagikan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Perlu Dinilai', (string) $perluDinilai)
                ->description($perluDinilai > 0 ? 'Ada tugas murid menunggu nilai' : 'Semua tugas telah dinilai')
                ->descriptionIcon('heroicon-m-clock')
                ->color($perluDinilai > 0 ? 'warning' : 'gray'),

            Stat::make('Telah Dinilai', (string) $sudahDinilai)
                ->description('Jawaban murid selesai diperiksa')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}
