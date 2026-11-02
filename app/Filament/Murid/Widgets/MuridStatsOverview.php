<?php

namespace App\Filament\Murid\Widgets;

use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MuridStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ruang Belajar & Progres Tugas Anda';

    protected function getStats(): array
    {
        $student = auth()->user();
        $studentId = $student ? $student->id : 0;

        $classIds = $student ? $student->enrolledClassrooms()->pluck('classrooms.id') : collect();

        $totalKelas = $classIds->count();

        $totalMateri = Material::whereIn('classroom_id', $classIds)
            ->where('is_published', true)
            ->count();

        $tugasBelumKumpul = Assignment::whereIn('classroom_id', $classIds)
            ->where('is_published', true)
            ->whereDoesntHave('submissions', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->count();

        $avgGrade = Submission::where('student_id', $studentId)
            ->whereNotNull('grade')
            ->avg('grade');

        $formattedGrade = $avgGrade !== null ? number_format((float) $avgGrade, 1, ',', '.').' / 100' : 'Belum Ada';

        return [
            Stat::make('Kelas Diikuti', (string) $totalKelas)
                ->description('Kelas belajar aktif Anda')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Materi Tersedia', (string) $totalMateri)
                ->description('Modul bacaan, berkas, & video')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Tugas Perlu Dikerjakan', (string) $tugasBelumKumpul)
                ->description($tugasBelumKumpul > 0 ? 'Tugas menunggu pengumpulan' : 'Semua tugas telah dikumpulkan!')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color($tugasBelumKumpul > 0 ? 'warning' : 'success'),

            Stat::make('Rata-rata Nilai Saya', $formattedGrade)
                ->description('Dari tugas yang telah dinilai guru')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}
